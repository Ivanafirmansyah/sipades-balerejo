/* =========================================================
   SIPADES — Antrian kiriman offline (IndexedDB)
   Dipakai bersama oleh halaman (window) dan service worker (self).

   Pola "outbox": setiap operasi disimpan dulu di perangkat, lalu dikirim
   ke server. Jika tidak ada sinyal, operasi tetap aman di perangkat dan
   dikirim ulang otomatis saat online.

   Jenis operasi:
   - kirim : isian baru (idempoten lewat client_uuid)
   - ubah  : koreksi isian yang sudah terkirim, membawa versi_dasar
             (optimistic concurrency control). Bila versi di server sudah
             berubah, server membalas 409 -> operasi ditandai "konflik"
             dan TIDAK menimpa data siapa pun.

   Store:
   - antrian : operasi yang belum berhasil dikirim (berisi data, karena belum tersimpan di server)
   - kiriman : "kartu identitas" isian yang sudah terkirim dari perangkat ini: nomor, versi,
               kunci edit. TANPA data pribadi (nama, NIK, alamat) — data diambil dari server
               saat warga menekan Koreksi (pelindungan data pribadi, UU No. 27/2022).
   ========================================================= */
(function (root) {
    'use strict';

    var DB_NAME = 'sipades';
    var DB_VERSI = 3;
    var channel = typeof BroadcastChannel !== 'undefined' ? new BroadcastChannel('sipades-antrian') : null;
    var sedangSinkron = false;

    function buka() {
        return new Promise(function (resolve, reject) {
            var req = indexedDB.open(DB_NAME, DB_VERSI);
            req.onupgradeneeded = function (e) {
                var db = req.result;
                if (!db.objectStoreNames.contains('antrian')) db.createObjectStore('antrian', { keyPath: 'uuid' });
                if (!db.objectStoreNames.contains('kiriman')) db.createObjectStore('kiriman', { keyPath: 'uuid' });
                // v3: hapus salinan data pribadi yang tersimpan oleh versi sebelumnya
                if (e.oldVersion > 0 && e.oldVersion < 3) {
                    var cur = req.transaction.objectStore('kiriman').openCursor();
                    cur.onsuccess = function () {
                        var c = cur.result; if (!c) return;
                        var v = c.value; delete v.data; c.update(v); c.continue();
                    };
                }
            };
            req.onsuccess = function () { resolve(req.result); };
            req.onerror = function () { reject(req.error); };
        });
    }

    function tx(store, mode, fn) {
        return buka().then(function (db) {
            return new Promise(function (resolve, reject) {
                var t = db.transaction(store, mode);
                var hasil = fn(t.objectStore(store));
                t.oncomplete = function () { db.close(); resolve(hasil && 'result' in hasil ? hasil.result : hasil); };
                t.onerror = function () { db.close(); reject(t.error); };
            });
        });
    }

    function patch(store, id, ubah) {
        return tx(store, 'readwrite', function (s) {
            var req = s.get(id);
            req.onsuccess = function () { if (req.result) s.put(Object.assign(req.result, ubah)); };
        });
    }

    function umumkan(jenis, detail) {
        if (channel) channel.postMessage({ jenis: jenis, detail: detail || {} });
    }

    function uuid() {
        if (root.crypto && root.crypto.randomUUID) return root.crypto.randomUUID();
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            var r = Math.random() * 16 | 0; return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
        });
    }

    /** Kunci rahasia acak untuk mengoreksi isian dari perangkat ini (server hanya menyimpan hash-nya). */
    function kunciAcak() {
        var a = new Uint8Array(24);
        (root.crypto || {}).getRandomValues ? root.crypto.getRandomValues(a) : a.forEach(function (_, i) { a[i] = Math.random() * 256 | 0; });
        return Array.prototype.map.call(a, function (b) { return ('0' + b.toString(16)).slice(-2); }).join('');
    }

    var MENUNGGU = ['menunggu', 'perlu_login'];

    var Antrian = {
        uuid: uuid,
        kunciAcak: kunciAcak,
        channel: channel,

        /* ---------- Antrian operasi ---------- */
        tambah: function (item) {
            item.uuid = item.uuid || uuid();
            item.op = item.op || 'kirim';
            item.dibuat = item.dibuat || Date.now();
            item.status = item.status || 'menunggu';
            item.percobaan = item.percobaan || 0;
            return tx('antrian', 'readwrite', function (s) { s.put(item); }).then(function () { umumkan('berubah'); return item; });
        },

        semua: function () {
            return tx('antrian', 'readonly', function (s) { return s.getAll(); }).then(function (rows) {
                return (rows || []).sort(function (a, b) { return a.dibuat - b.dibuat; });
            });
        },

        hapus: function (id) {
            return tx('antrian', 'readwrite', function (s) { s.delete(id); }).then(function () { umumkan('berubah'); });
        },

        perbarui: function (id, ubah) {
            return patch('antrian', id, ubah).then(function () { umumkan('berubah'); });
        },

        /* ---------- Isian yang pernah dikirim dari perangkat ini ---------- */
        simpanKiriman: function (rec) {
            rec = Object.assign({}, rec);
            delete rec.data; // jangan pernah menyimpan isian (data pribadi) yang sudah tersinkron
            return tx('kiriman', 'readwrite', function (s) {
                var req = s.get(rec.uuid);
                req.onsuccess = function () { s.put(Object.assign(req.result || {}, rec)); };
            }).then(function () { umumkan('kiriman'); });
        },

        kiriman: function (slug) {
            return tx('kiriman', 'readonly', function (s) { return s.getAll(); }).then(function (rows) {
                return (rows || []).filter(function (r) { return !slug || r.slug === slug; })
                    .sort(function (a, b) { return (b.dibuat || 0) - (a.dibuat || 0); });
            });
        },

        hapusKiriman: function (id) {
            return tx('kiriman', 'readwrite', function (s) { s.delete(id); }).then(function () { umumkan('kiriman'); });
        },

        /**
         * Dipanggil saat logout: hapus semua data akun di perangkat (catatan isian, draf, halaman tersimpan)
         * KECUALI antrian yang belum terkirim — agar data warga tidak hilang sebelum tersimpan di server.
         */
        bersihkanPerangkat: function () {
            var tugas = [tx('kiriman', 'readwrite', function (s) { s.clear(); })];
            try {
                Object.keys(localStorage).forEach(function (k) { if (k.indexOf('sipades-draf:') === 0) localStorage.removeItem(k); });
            } catch (e) {}
            if (root.caches) {
                tugas.push(caches.keys().then(function (keys) {
                    return Promise.all(keys.filter(function (k) { return /-halaman$/.test(k); }).map(function (k) { return caches.delete(k); }));
                }));
            }
            return Promise.all(tugas).then(function () { umumkan('kiriman'); });
        },

        /**
         * Antrekan koreksi untuk isian `target`.
         * - Isian aslinya masih di antrian (belum terkirim)  -> cukup ganti datanya di antrian (tanpa konflik).
         * - Sudah ada koreksi yang belum terkirim            -> digabung: data terbaru, versi_dasar tetap,
         *                                                       id operasi baru (agar koreksi lama yang mungkin
         *                                                       sudah sampai tidak dianggap sama).
         * - Selain itu                                        -> operasi "ubah" baru.
         */
        antreUbah: function (target, info) {
            return Antrian.semua().then(function (items) {
                var asli = items.find(function (i) { return i.op === 'kirim' && i.uuid === target.uuid && MENUNGGU.indexOf(i.status) > -1; });
                if (asli) {
                    return Antrian.perbarui(asli.uuid, { data: info.data }).then(function () {
                        return { gabung: 'kirim', item: Object.assign(asli, { data: info.data }) };
                    });
                }
                var lama = items.find(function (i) { return i.op === 'ubah' && i.target === target.uuid && i.status !== 'gagal'; });
                var item = {
                    op: 'ubah', target: target.uuid, slug: target.slug, judul: target.judul, url: info.url,
                    kunci_edit: target.kunci_edit || null, data: info.data, diubah_pada: new Date().toISOString(),
                    versi_dasar: lama && lama.status !== 'konflik' ? lama.versi_dasar : info.versi_dasar,
                    offline: !navigator.onLine
                };
                return (lama ? Antrian.hapus(lama.uuid) : Promise.resolve()).then(function () {
                    return Antrian.tambah(item).then(function (it) { return { gabung: lama ? 'ubah' : null, item: it }; });
                });
            });
        },

        /**
         * Kirim satu operasi. Hasil:
         *  {hasil:'terkirim'}                -> berhasil, dihapus dari antrian
         *  {hasil:'konflik', server}         -> versi berubah / sudah diproses (409), menunggu keputusan warga
         *  {hasil:'ditolak', errors}         -> isian tidak valid / tidak berhak / formulir ditutup
         *  {hasil:'perlu_login'}             -> sesi habis, dicoba lagi setelah login
         *  {hasil:'offline'}                 -> jaringan gagal, dicoba lagi nanti
         */
        kirim: function (item) {
            var body = item.op === 'ubah'
                ? { client_uuid: item.target, op_uuid: item.uuid, versi_dasar: item.versi_dasar, kunci_edit: item.kunci_edit,
                    diubah_pada: item.diubah_pada, offline: !!item.offline, data: item.data }
                : { client_uuid: item.uuid, kunci_edit: item.kunci_edit, diisi_pada: item.diisi_pada, offline: !!item.offline, data: item.data };

            return fetch(item.url, {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify(body)
            }).then(function (res) {
                return res.json().catch(function () { return {}; }).then(function (json) {
                    var target = item.op === 'ubah' ? item.target : item.uuid;

                    if (res.ok) {
                        // perbarui catatan isian di perangkat dengan versi terbaru dari server
                        // hanya metadata — data isian TIDAK disimpan lagi di perangkat setelah tersinkron
                        var catat = { uuid: target, slug: item.slug, judul: item.judul, versi: json.versi, nomor: json.nomor,
                                      status: json.status, bisa_dikoreksi: json.bisa_dikoreksi, dikirim: json.dikirim, terkirim: true };
                        if (item.op === 'kirim') { catat.kunci_edit = item.kunci_edit; catat.dibuat = item.dibuat; catat.url_ubah = item.url.replace(/\/kirim$/, '/ubah'); }
                        return Antrian.simpanKiriman(catat).then(function () { return Antrian.hapus(item.uuid); }).then(function () {
                            umumkan('terkirim', { judul: item.judul, op: item.op, pesan: json.pesan });
                            return { hasil: 'terkirim', pesan: json.pesan, server: json };
                        });
                    }
                    if (res.status === 409) {
                        return Antrian.perbarui(item.uuid, { status: 'konflik', konflik: json.konflik || 'versi', pesan: json.pesan, server: json })
                            .then(function () {
                                return Antrian.simpanKiriman({ uuid: target, versi: json.versi, bisa_dikoreksi: json.bisa_dikoreksi, status: json.status });
                            })
                            .then(function () { umumkan('konflik', { judul: item.judul }); return { hasil: 'konflik', pesan: json.pesan, server: json }; });
                    }
                    if (res.status === 401 || res.status === 419) {
                        return Antrian.perbarui(item.uuid, { status: 'perlu_login', pesan: json.pesan || 'Silakan login kembali.' })
                            .then(function () { return { hasil: 'perlu_login', pesan: json.pesan }; });
                    }
                    if ([403, 404, 410, 422].indexOf(res.status) > -1) {
                        var pesan = json.message || json.pesan || 'Ditolak server.';
                        return Antrian.perbarui(item.uuid, { status: 'gagal', pesan: pesan, errors: json.errors || null })
                            .then(function () { return { hasil: 'ditolak', pesan: pesan, errors: json.errors || {} }; });
                    }
                    // 429 / 5xx: sementara, coba lagi nanti
                    return Antrian.perbarui(item.uuid, { percobaan: (item.percobaan || 0) + 1 }).then(function () { return { hasil: 'offline' }; });
                });
            }, function () {
                return Antrian.perbarui(item.uuid, { offline: true, percobaan: (item.percobaan || 0) + 1 }).then(function () { return { hasil: 'offline' }; });
            });
        },

        /** Kirim semua operasi yang menunggu, berurutan (isian baru sebelum koreksinya). */
        sinkron: function () {
            if (sedangSinkron) return Promise.resolve(null);
            sedangSinkron = true;
            var ringkas = { terkirim: 0, sisa: 0, gagal: 0, konflik: 0 };
            return Antrian.semua().then(function (items) {
                var antre = items.filter(function (i) { return MENUNGGU.indexOf(i.status) > -1; });
                return antre.reduce(function (p, item) {
                    return p.then(function (berhenti) {
                        if (berhenti) { ringkas.sisa++; return true; }
                        return Antrian.kirim(item).then(function (r) {
                            if (r.hasil === 'terkirim') ringkas.terkirim++;
                            else if (r.hasil === 'ditolak') ringkas.gagal++;
                            else if (r.hasil === 'konflik') ringkas.konflik++;
                            else { ringkas.sisa++; return r.hasil === 'offline'; } // jaringan putus: hentikan sisanya
                            return false;
                        });
                    });
                }, Promise.resolve(false));
            }).then(function () {
                sedangSinkron = false;
                umumkan('selesai', ringkas);
                return ringkas;
            }, function (e) { sedangSinkron = false; throw e; });
        }
    };

    root.SipadesAntrian = Antrian;
})(typeof self !== 'undefined' ? self : window);
