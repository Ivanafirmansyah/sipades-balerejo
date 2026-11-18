/* =========================================================
   SIPADES — PWA Offline-First (sisi halaman)
   - Daftarkan service worker
   - Formulir dinamis: isi tanpa sinyal -> simpan di perangkat -> kirim otomatis saat online
   - Koreksi isian berversi: perbaiki isian yang sudah dikirim (juga saat offline),
     dengan deteksi & penyelesaian konflik (optimistic concurrency control)
   - Draf otomatis, indikator sinyal, panel antrian, tombol pasang aplikasi
   ========================================================= */
(function () {
    'use strict';

    var A = window.SipadesAntrian;
    var $ = function (s, r) { return (r || document).querySelector(s); };
    var dukungSW = 'serviceWorker' in navigator && window.isSecureContext;

    function esc(s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; });
    }
    function waktu(ts) {
        if (!ts) return '-';
        var d = new Date(ts);
        return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }) + ' ' + d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    }

    /* ---------------- Service worker ---------------- */
    var swSiap = dukungSW
        ? navigator.serviceWorker.register('/sw.js').then(function () { return navigator.serviceWorker.ready; }).catch(function () { return null; })
        : Promise.resolve(null);

    function daftarSync() {
        return swSiap.then(function (reg) { if (reg && reg.sync) return reg.sync.register('kirim-antrian').catch(function () {}); });
    }

    /* ---------------- Toast ---------------- */
    function toast(pesan, jenis) {
        var box = $('#pwaToasts'); if (!box) return;
        var el = document.createElement('div');
        el.className = 'pwa-toast ' + (jenis || 'ok');
        el.innerHTML = '<i class="bi ' + ({ ok: 'bi-check-circle-fill', warn: 'bi-cloud-arrow-up-fill', err: 'bi-exclamation-triangle-fill' }[jenis || 'ok']) + '"></i><span></span>';
        el.lastChild.textContent = pesan;
        box.appendChild(el);
        setTimeout(function () { el.classList.add('hide'); setTimeout(function () { el.remove(); }, 400); }, 4500);
    }

    /* ---------------- Indikator sinyal ---------------- */
    function perbaruiSinyal() {
        var off = !navigator.onLine;
        document.documentElement.classList.toggle('is-offline', off);
        var b = $('#netBanner'); if (b) b.hidden = !off;
    }
    window.addEventListener('online', function () { perbaruiSinyal(); toast('Sinyal kembali. Mengirim data yang tertunda...', 'ok'); sinkron(); });
    window.addEventListener('offline', perbaruiSinyal);
    perbaruiSinyal();

    /* ---------------- Panel antrian ---------------- */
    var LABEL = {
        menunggu: ['Menunggu sinyal', 'warn'], perlu_login: ['Perlu login', 'warn'],
        gagal: ['Ditolak', 'err'], konflik: ['Konflik', 'err']
    };

    function renderAntrian() {
        if (!A) return Promise.resolve();
        return A.semua().then(function (items) {
            var dock = $('#antrianDock'); if (!dock) return;
            var tunggu = items.filter(function (i) { return i.status === 'menunggu' || i.status === 'perlu_login'; }).length;
            dock.hidden = items.length === 0;
            $('#antrianCount').textContent = items.length;
            $('#antrianJudul').textContent = tunggu ? tunggu + ' data menunggu dikirim' : items.length + ' data perlu diperiksa';
            dock.classList.toggle('has-error', tunggu === 0 && items.length > 0);
            $('#antrianLogin').hidden = !items.some(function (i) { return i.status === 'perlu_login'; });
            var ul = $('#antrianList'); ul.innerHTML = '';
            items.forEach(function (i) {
                var l = LABEL[i.status] || LABEL.menunggu;
                var li = document.createElement('li');
                li.innerHTML = '<div class="ai-main"><b></b><small></small></div><span class="ai-status ' + l[1] + '">' + l[0] + '</span>' +
                    (i.status === 'konflik' ? '<a class="ai-fix" title="Selesaikan konflik"><i class="bi bi-tools"></i></a>' : '') +
                    '<button type="button" class="ai-del" title="Hapus dari perangkat"><i class="bi bi-x-lg"></i></button>';
                li.querySelector('b').textContent = (i.op === 'ubah' ? 'Koreksi: ' : '') + (i.judul || 'Formulir');
                li.querySelector('small').textContent = (i.op === 'ubah' ? 'Diubah ' + waktu(i.diubah_pada) : 'Diisi ' + waktu(i.dibuat)) +
                    (i.pesan && i.status !== 'menunggu' ? ' — ' + i.pesan : '');
                var fix = li.querySelector('.ai-fix'); if (fix) fix.href = '/formulir/' + i.slug + '#konflik';
                li.querySelector('.ai-del').addEventListener('click', function () {
                    if (confirm('Hapus "' + (i.judul || '') + '" dari perangkat? Data ini belum tersimpan di server.')) A.hapus(i.uuid);
                });
                ul.appendChild(li);
            });
        });
    }

    function sinkron() {
        if (!A || !navigator.onLine) return Promise.resolve();
        return A.sinkron().then(function (r) {
            if (r && r.terkirim) toast(r.terkirim + ' data yang tersimpan offline berhasil terkirim.', 'ok');
            if (r && r.konflik) toast(r.konflik + ' koreksi bentrok dengan data terbaru. Periksa panel antrian.', 'err');
            if (r && r.gagal) toast(r.gagal + ' data ditolak server. Periksa panel antrian.', 'err');
        }).catch(function () {}).then(function () {
            document.dispatchEvent(new Event('sipades:berubah')); // BroadcastChannel tidak mengirim ke tab pengirimnya sendiri
            return renderAntrian();
        });
    }

    if (A) {
        if (A.channel) A.channel.addEventListener('message', function () { renderAntrian(); document.dispatchEvent(new Event('sipades:berubah')); });
        document.addEventListener('DOMContentLoaded', function () {
            var t = $('#antrianToggle'); if (t) t.addEventListener('click', function () { $('#antrianDock').classList.toggle('open'); });
            var k = $('#antrianKirim'); if (k) k.addEventListener('click', function () {
                if (!navigator.onLine) { toast('Masih offline. Data akan dikirim otomatis saat sinyal kembali.', 'warn'); return; }
                sinkron();
            });
            renderAntrian().then(sinkron);
        });
        // cadangan bila Background Sync tidak didukung browser
        setInterval(function () {
            if (navigator.onLine && document.visibilityState === 'visible') A.semua().then(function (x) { if (x.some(function (i) { return i.status === 'menunggu' || i.status === 'perlu_login'; })) sinkron(); });
        }, 20000);
        document.addEventListener('visibilitychange', function () { if (document.visibilityState === 'visible') sinkron(); });
    }

    /* ---------------- Simpan halaman formulir untuk offline ---------------- */
    function simpanUntukOffline(urls) {
        return swSiap.then(function (reg) {
            if (!reg || !reg.active || !urls.length) return;
            return new Promise(function (resolve) {
                var selesai = function (e) {
                    if (e.data && e.data.type === 'halaman-tersimpan') { navigator.serviceWorker.removeEventListener('message', selesai); resolve(e.data.urls || []); }
                };
                navigator.serviceWorker.addEventListener('message', selesai);
                reg.active.postMessage({ type: 'simpan-halaman', urls: urls });
                setTimeout(function () { resolve([]); }, 15000);
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        var tiles = document.querySelectorAll('[data-offline-url]');
        var urls = [].map.call(tiles, function (a) { return a.dataset.offlineUrl; });
        if (dukungSW && navigator.onLine && urls.length) {
            simpanUntukOffline(urls.concat([location.pathname])).then(function (ok) {
                if (!ok || !ok.length) return;
                tiles.forEach(function (a) { var chip = a.querySelector('.offline-chip'); if (chip && ok.indexOf(a.dataset.offlineUrl) > -1) chip.hidden = false; });
                var note = $('#offlineReady'); if (note) note.hidden = false;
            });
        }
        if ($('[data-offline-form]') && dukungSW) swSiap.then(function (reg) { if (reg) { var n = $('#offlineReady'); if (n) n.hidden = false; } });
    });

    /* =========================================================
       FORMULIR DINAMIS + KOREKSI BERVERSI
       ========================================================= */
    var PESAN = {
        valueMissing: 'Wajib diisi.', typeMismatch: 'Format tidak valid.', patternMismatch: null,
        rangeUnderflow: 'Nilai terlalu kecil.', rangeOverflow: 'Nilai terlalu besar.', tooLong: 'Terlalu panjang.', badInput: 'Format tidak valid.'
    };

    function initForm(form) {
        var slug = form.dataset.slug, judul = form.dataset.judul;
        var kunciDraf = 'sipades-draf:' + slug;
        var alertBox = $('#formAlert');
        var sukses = $('#formSuccess');
        var btn = $('#btnKirim', form);
        var labelKirimAsli = btn.querySelector('.label-kirim').innerHTML;
        var koreksi = null;          // isian yang sedang dikoreksi: {uuid, versi, kunci_edit, nomor, ...}
        var terakhirDikirim = null;  // untuk tombol "Koreksi Isian Ini"
        var dariServer = [];
        try { dariServer = JSON.parse(($('#isianSayaData') || {}).textContent || '[]') || []; } catch (e) {}

        /* ---- label isian untuk tampilan perbandingan ---- */
        var LABEL_ISIAN = {};
        form.querySelectorAll('[data-field]').forEach(function (w) {
            var lb = w.querySelector('label'); LABEL_ISIAN[w.dataset.field] = lb ? lb.textContent.replace('*', '').trim() : w.dataset.field;
        });

        function kumpulkan() {
            var data = {};
            new FormData(form).forEach(function (v, k) {
                var m = k.match(/^data\[([^\]]+)\](\[\])?$/); if (!m) return;
                if (m[2]) (data[m[1]] = data[m[1]] || []).push(v); else data[m[1]] = v;
            });
            form.querySelectorAll('.pilihan-group input[type=checkbox]').forEach(function (c) {
                var m = c.name.match(/^data\[([^\]]+)\]/); if (m && !data[m[1]]) data[m[1]] = [];
            });
            return data;
        }

        function isi(data) {
            form.querySelectorAll('[name^="data["]').forEach(function (el) {
                var m = el.name.match(/^data\[([^\]]+)\]/); if (!m) return;
                var v = (data || {})[m[1]];
                if (el.type === 'checkbox') el.checked = Array.isArray(v) && v.indexOf(el.value) > -1;
                else if (el.type === 'radio') el.checked = el.value === v;
                else el.value = v == null ? '' : v;
            });
        }

        function kosongkan() { isi({}); }

        function bersihkanError() {
            form.querySelectorAll('.is-invalid').forEach(function (el) { el.classList.remove('is-invalid'); });
            form.querySelectorAll('.field-error').forEach(function (el) { el.textContent = ''; });
            alertBox.hidden = true;
        }

        function tampilkanError(errors) {
            var pertama = null;
            Object.keys(errors).forEach(function (key) {
                var nama = key.replace(/^data\./, '').split('.')[0];
                var wrap = form.querySelector('[data-field="' + nama + '"]');
                if (wrap) {
                    wrap.querySelector('.field-error').textContent = [].concat(errors[key])[0];
                    wrap.querySelectorAll('input, select, textarea').forEach(function (el) { el.classList.add('is-invalid'); });
                    pertama = pertama || wrap;
                } else { alertBox.textContent = [].concat(errors[key])[0]; alertBox.hidden = false; pertama = pertama || alertBox; }
            });
            if (pertama) pertama.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        function validasi() {
            bersihkanError();
            var errors = {};
            form.querySelectorAll('[data-field]').forEach(function (wrap) {
                var nama = wrap.dataset.field;
                var grup = wrap.querySelector('[data-required-group]');
                if (grup && !grup.querySelector('input:checked')) { errors[nama] = 'Pilih minimal satu.'; return; }
                var el = [].find.call(wrap.querySelectorAll('input, select, textarea'), function (x) { return !x.validity.valid; });
                if (el) {
                    var v = el.validity, msg = 'Isian tidak valid.';
                    for (var k in PESAN) if (v[k]) { msg = PESAN[k] || el.title || 'Format tidak valid.'; break; }
                    if (v.rangeUnderflow && el.min) msg = 'Minimal ' + el.min + '.';
                    if (v.rangeOverflow && el.max) msg = 'Maksimal ' + el.max + '.';
                    errors[nama] = msg;
                }
            });
            if (Object.keys(errors).length) { tampilkanError(errors); return false; }
            return true;
        }

        function sibuk(on) {
            btn.disabled = on;
            btn.querySelector('.label-kirim').hidden = on;
            btn.querySelector('.label-proses').hidden = !on;
        }

        function tampilSukses(jdl, teks, offline) {
            form.hidden = true; sukses.hidden = false;
            sukses.classList.toggle('is-offline', !!offline);
            sukses.querySelector('.success-icon i').className = 'bi ' + (offline ? 'bi-cloud-arrow-up' : 'bi-check-lg');
            $('#successTitle').textContent = jdl;
            $('#successText').textContent = teks;
            $('#koreksiBaru').hidden = !terakhirDikirim;
            sukses.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        /* ---- Mode koreksi ---- */
        function mulaiKoreksi(rec, dataAwal, versiDasar) {
            koreksi = Object.assign({}, rec, { versi_dasar: versiDasar || rec.versi || 1 });
            isi(dataAwal || rec.data);
            bersihkanError();
            form.hidden = false; sukses.hidden = true;
            $('#modeKoreksi').hidden = false;
            $('#modeKoreksiInfo').textContent = 'isian' + (rec.nomor ? ' #' + rec.nomor : '') + (rec.terkirim === false ? ' (belum terkirim)' : ', versi ' + koreksi.versi_dasar);
            btn.querySelector('.label-kirim').innerHTML = '<i class="bi bi-check2-circle me-1"></i> Simpan Perubahan';
            form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        function selesaiKoreksi() {
            koreksi = null;
            $('#modeKoreksi').hidden = true;
            btn.querySelector('.label-kirim').innerHTML = labelKirimAsli;
        }
        $('#batalKoreksi').addEventListener('click', function () { selesaiKoreksi(); kosongkan(); bersihkanError(); });

        /* ---- Daftar "Isian Anda": gabungan catatan perangkat + akun (server) ---- */
        function renderIsianSaya() {
            if (!A) return;
            Promise.all([A.kiriman(slug), A.semua()]).then(function (hasil) {
                var lokal = hasil[0], antre = hasil[1], peta = {};
                dariServer.forEach(function (r) { peta[r.uuid] = Object.assign({ terkirim: true, slug: slug, judul: judul }, r); });
                lokal.forEach(function (r) {
                    var s = peta[r.uuid];
                    peta[r.uuid] = !s || (r.versi || 0) >= (s.versi || 0) ? Object.assign({}, s || {}, r) : Object.assign({}, r, s, { kunci_edit: r.kunci_edit });
                });
                // isian yang masih di antrian (belum terkirim) juga bisa dikoreksi
                antre.forEach(function (i) {
                    if (i.op === 'kirim' && i.slug === slug && !peta[i.uuid]) peta[i.uuid] = { uuid: i.uuid, slug: slug, judul: judul, data: i.data, kunci_edit: i.kunci_edit, dibuat: i.dibuat, terkirim: false };
                });
                var list = Object.keys(peta).map(function (k) { return peta[k]; })
                    .sort(function (a, b) { return new Date(b.dikirim || b.dibuat || 0) - new Date(a.dikirim || a.dibuat || 0); });
                var box = $('#isianSaya'), ul = $('#isianList');
                if (!box) return;
                box.hidden = list.length === 0;
                ul.innerHTML = '';
                list.slice(0, 10).forEach(function (r) {
                    var pending = antre.find(function (i) { return (i.op === 'ubah' && i.target === r.uuid) || (i.op === 'kirim' && i.uuid === r.uuid); });
                    var bisa = r.terkirim === false || r.bisa_dikoreksi !== false;
                    var li = document.createElement('li');
                    li.innerHTML = '<div class="il-main"><b></b><small></small></div>' +
                        (pending ? '<span class="ai-status ' + (pending.status === 'konflik' ? 'err">Konflik' : 'warn">Belum terkirim') + '</span>' : '') +
                        (bisa ? '<button type="button" class="btn btn-sm btn-outline-biru rounded-pill"><i class="bi bi-pencil-square me-1"></i>Koreksi</button>'
                              : '<span class="small text-muted"><i class="bi bi-lock me-1"></i>' + esc(r.status || 'Diproses') + '</span>');
                    // sengaja tanpa nama/NIK: perangkat tidak menyimpan data pribadi isian yang sudah terkirim
                    li.querySelector('b').textContent = r.nomor ? 'Isian #' + r.nomor : 'Isian belum terkirim';
                    li.querySelector('small').textContent = (r.terkirim === false ? 'Disimpan ' + waktu(r.dibuat) : 'Dikirim ' + waktu(r.dikirim || r.dibuat)) +
                        (r.versi ? ' · versi ' + r.versi : '') + (r.diedit_kali ? ' · dikoreksi ' + r.diedit_kali + '×' : '');
                    var b = li.querySelector('button');
                    if (b) b.addEventListener('click', function () { bukaKoreksi(r, pending, b); });
                    ul.appendChild(li);
                });
                renderKonflik(antre);
            });
        }

        /**
         * Buka mode koreksi.
         * - Data yang BELUM tersinkron (antrian kirim / koreksi tertunda) masih ada di perangkat -> bisa offline.
         * - Isian yang SUDAH terkirim: perangkat tidak menyimpan datanya (pelindungan data pribadi),
         *   jadi data terbaru diambil dari server -> perlu sinyal.
         */
        function bukaKoreksi(r, pending, tombol) {
            if (pending && pending.op === 'kirim') return mulaiKoreksi(r, pending.data, null);
            if (pending && pending.op === 'ubah' && pending.status !== 'konflik') return mulaiKoreksi(r, pending.data, pending.versi_dasar);
            if (!navigator.onLine) {
                toast('Perlu sinyal untuk membuka isian yang sudah terkirim. Data pribadi tidak disimpan di perangkat.', 'warn');
                return;
            }
            var asli = tombol ? tombol.innerHTML : '';
            if (tombol) { tombol.disabled = true; tombol.innerHTML = '<span class="spinner-border spinner-border-sm"></span>'; }
            var h = { 'Accept': 'application/json' };
            if (r.kunci_edit) h['X-Kunci-Edit'] = r.kunci_edit;
            fetch(form.dataset.urlIsian + '/' + r.uuid, { credentials: 'same-origin', headers: h, cache: 'no-store' })
                .then(function (res) { return res.json().then(function (j) { return { ok: res.ok, j: j }; }); })
                .then(function (x) {
                    if (!x.ok) { toast(x.j.pesan || 'Isian tidak dapat dibuka.', 'err'); return; }
                    A.simpanKiriman({ uuid: r.uuid, versi: x.j.versi, status: x.j.status, bisa_dikoreksi: x.j.bisa_dikoreksi });
                    if (!x.j.bisa_dikoreksi) { toast('Isian sudah diproses petugas sehingga tidak bisa dikoreksi lagi.', 'warn'); renderIsianSaya(); return; }
                    mulaiKoreksi(Object.assign({}, r, { nomor: x.j.nomor }), x.j.data, x.j.versi);
                })
                .catch(function () { toast('Gagal terhubung ke server. Coba lagi saat sinyal stabil.', 'err'); })
                .then(function () { if (tombol) { tombol.disabled = false; tombol.innerHTML = asli; } });
        }

        /* ---- Penyelesaian konflik: bandingkan data saya vs data terbaru di server ---- */
        function teks(v) { return Array.isArray(v) ? (v.length ? v.join(', ') : '-') : (v == null || v === '' ? '-' : String(v)); }

        function renderKonflik(antre) {
            var box = $('#konflikBox'); if (!box) return;
            var k = antre.filter(function (i) { return i.op === 'ubah' && i.slug === slug && i.status === 'konflik'; })[0];
            box.hidden = !k;
            if (!k) return;
            var srv = k.server || {}, terkunci = k.konflik === 'terkunci' || srv.bisa_dikoreksi === false;
            var baris = Object.keys(LABEL_ISIAN).map(function (n) {
                var a = teks((k.data || {})[n]), b = teks((srv.data || {})[n]);
                return '<tr class="' + (a !== b ? 'beda' : '') + '"><th>' + esc(LABEL_ISIAN[n]) + '</th><td>' + esc(a) + '</td><td>' + esc(b) + '</td></tr>';
            }).join('');
            box.id = 'konflikBox';
            box.innerHTML =
                '<div class="d-flex gap-2 align-items-start mb-2" id="konflik"><i class="bi bi-exclamation-octagon-fill"></i><div>' +
                '<b>Koreksi Anda bentrok dengan data terbaru.</b><div class="small">' + esc(k.pesan || '') + '</div></div></div>' +
                '<div class="table-responsive"><table class="table table-sm konflik-tabel mb-2"><thead><tr><th>Isian</th><th>Koreksi Anda</th><th>Data di server' + (srv.versi ? ' (versi ' + srv.versi + ')' : '') + '</th></tr></thead><tbody>' + baris + '</tbody></table></div>' +
                '<div class="small text-muted mb-2">Baris berwarna = isian yang berbeda.</div>' +
                '<div class="d-flex flex-wrap gap-2">' +
                (terkunci
                    ? '<button type="button" class="btn btn-sm btn-outline-secondary" data-aksi="buang">Tutup &amp; buang koreksi saya</button>'
                    : '<button type="button" class="btn btn-sm btn-kuning" data-aksi="tinjau"><i class="bi bi-pencil-square me-1"></i>Tinjau &amp; terapkan koreksi saya</button>' +
                      '<button type="button" class="btn btn-sm btn-outline-secondary" data-aksi="server">Pakai data server</button>') +
                '</div>';
            box.querySelectorAll('[data-aksi]').forEach(function (b) {
                b.addEventListener('click', function () {
                    var aksi = b.dataset.aksi;
                    var rec = { uuid: k.target, slug: slug, judul: judul, kunci_edit: k.kunci_edit, nomor: srv.nomor, versi: srv.versi, terkirim: true };
                    A.hapus(k.uuid).then(function () {
                        if (srv.data) A.simpanKiriman({ uuid: k.target, data: srv.data, versi: srv.versi, bisa_dikoreksi: srv.bisa_dikoreksi });
                        // "rebase": koreksi saya diterapkan di atas versi terbaru server, setelah warga meninjaunya
                        if (aksi === 'tinjau') mulaiKoreksi(rec, k.data, srv.versi);
                        else toast(aksi === 'server' ? 'Data terbaru dari server dipakai.' : 'Koreksi dibuang.', 'ok');
                    });
                });
            });
            if (location.hash === '#konflik') box.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        document.addEventListener('sipades:berubah', renderIsianSaya);

        /* ---- Draf otomatis (hanya untuk isian baru) ---- */
        try {
            var draf = JSON.parse(localStorage.getItem(kunciDraf) || 'null');
            if (draf && !form.querySelector('.field-error:not(:empty)')) { isi(draf); $('#draftInfo').innerHTML = '<i class="bi bi-arrow-counterclockwise me-1"></i>Isian sebelumnya dipulihkan dari perangkat'; }
        } catch (e) {}
        var simpanDraf = function () { if (!koreksi) try { localStorage.setItem(kunciDraf, JSON.stringify(kumpulkan())); } catch (e) {} };
        form.addEventListener('input', simpanDraf);
        form.addEventListener('change', simpanDraf);

        $('#isiLagi').addEventListener('click', function () {
            selesaiKoreksi(); kosongkan(); form.hidden = false; sukses.hidden = true; bersihkanError();
            form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
        // data isian terakhir hanya disimpan di memori halaman (hilang saat halaman ditutup), bukan di perangkat
        $('#koreksiBaru').addEventListener('click', function () { if (terakhirDikirim) mulaiKoreksi(terakhirDikirim, terakhirDikirim.data, terakhirDikirim.versi); });

        /* ---- Kirim / simpan koreksi ---- */
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (form.hasAttribute('data-locked')) return;
            if (!validasi()) return;
            if (!A) { form.submit(); return; }
            sibuk(true);
            (koreksi ? simpanKoreksi() : kirimBaru()).catch(function () {
                sibuk(false);
                tampilSukses('Tersimpan di perangkat', 'Data disimpan dan akan dikirim otomatis.', true);
            });
        });

        function kirimBaru() {
            var item = {
                op: 'kirim', slug: slug, judul: judul, url: form.action, kunci_edit: A.kunciAcak(),
                data: kumpulkan(), diisi_pada: new Date().toISOString(), offline: !navigator.onLine
            };
            // 1) simpan dulu di perangkat (outbox) agar tidak pernah hilang
            return A.tambah(item).then(function (t) {
                try { localStorage.removeItem(kunciDraf); } catch (x) {}
                terakhirDikirim = { uuid: t.uuid, slug: slug, judul: judul, kunci_edit: t.kunci_edit, data: t.data, dibuat: t.dibuat, terkirim: false };
                return navigator.onLine ? A.kirim(t) : { hasil: 'offline' };   // 2) coba kirim sekarang
            }).then(function (r) {
                sibuk(false);
                if (r.hasil === 'terkirim') {
                    Object.assign(terakhirDikirim, { terkirim: true, versi: r.server.versi, nomor: r.server.nomor, bisa_dikoreksi: true });
                    tampilSukses('Terkirim!', (r.pesan || form.dataset.pesan) + ' Ada yang salah isi? Anda masih bisa mengoreksinya selama belum diproses petugas.', false);
                } else if (r.hasil === 'offline') {
                    daftarSync();
                    tampilSukses('Tersimpan di perangkat', 'Tidak ada sinyal saat ini. Isian aman tersimpan dan akan terkirim otomatis begitu sinyal kembali — Anda boleh menutup halaman ini.', true);
                } else if (r.hasil === 'perlu_login') {
                    daftarSync();
                    tampilSukses('Tersimpan — perlu login', 'Sesi Anda sudah berakhir. Isian tersimpan di perangkat dan akan dikirim otomatis setelah Anda masuk kembali.', true);
                } else if (r.hasil === 'ditolak') {
                    terakhirDikirim = null;
                    A.hapus(item.uuid);
                    try { localStorage.setItem(kunciDraf, JSON.stringify(item.data)); } catch (x) {}
                    if (r.errors && Object.keys(r.errors).length) tampilkanError(r.errors); else { alertBox.textContent = r.pesan; alertBox.hidden = false; }
                }
                renderAntrian(); renderIsianSaya();
            });
        }

        function simpanKoreksi() {
            var data = kumpulkan(), rec = koreksi;
            return A.antreUbah(rec, { url: form.dataset.urlUbah, data: data, versi_dasar: rec.versi_dasar }).then(function (h) {
                // Isian aslinya belum terkirim: koreksi cukup mengganti data di antrian
                if (h.gabung === 'kirim') return { hasil: 'gabung', item: h.item };
                return navigator.onLine ? A.kirim(h.item).then(function (r) { r.item = h.item; return r; }) : { hasil: 'offline', item: h.item };
            }).then(function (r) {
                sibuk(false);
                terakhirDikirim = Object.assign({}, rec, { data: data, versi: r.server ? r.server.versi : rec.versi_dasar });
                if (r.hasil === 'terkirim') {
                    selesaiKoreksi();
                    tampilSukses('Perubahan tersimpan', 'Isian sudah dikoreksi (sekarang versi ' + r.server.versi + '). Data sebelumnya tetap tersimpan sebagai riwayat.', false);
                } else if (r.hasil === 'gabung') {
                    selesaiKoreksi();
                    tampilSukses('Perubahan disimpan di perangkat', 'Isian ini belum terkirim, jadi perubahan langsung diterapkan dan akan ikut terkirim saat sinyal ada.', true);
                } else if (r.hasil === 'offline' || r.hasil === 'perlu_login') {
                    selesaiKoreksi(); daftarSync();
                    tampilSukses('Koreksi tersimpan di perangkat', 'Perubahan akan dikirim otomatis saat sinyal kembali. Bila isian sudah diubah di tempat lain, Anda akan diminta memilih.', true);
                } else if (r.hasil === 'konflik') {
                    selesaiKoreksi(); kosongkan();
                    toast('Koreksi bentrok dengan data terbaru. Silakan tinjau.', 'err');
                } else if (r.hasil === 'ditolak') {
                    A.hapus(r.item.uuid);
                    if (r.errors && Object.keys(r.errors).length) tampilkanError(r.errors); else { alertBox.textContent = r.pesan; alertBox.hidden = false; }
                }
                renderAntrian(); renderIsianSaya();
            });
        }

        renderIsianSaya();
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-offline-form]').forEach(initForm);
    });

    /* ---------------- Logout: hapus data akun di perangkat ----------------
       Catatan isian, draf, dan halaman tersimpan dihapus agar tidak terbaca pengguna lain
       di perangkat bersama. Antrian yang BELUM terkirim tetap disimpan agar data tidak hilang. */
    document.addEventListener('submit', function (e) {
        var f = e.target;
        if (!A || !f.action || !/\/logout$/.test(f.action) || f.dataset.dibersihkan) return;
        e.preventDefault();
        f.dataset.dibersihkan = '1';
        var lanjut = function () { HTMLFormElement.prototype.submit.call(f); };
        Promise.race([A.bersihkanPerangkat(), new Promise(function (r) { setTimeout(r, 2000); })]).then(lanjut, lanjut);
    }, true);

    /* ---------------- Tombol pasang aplikasi ---------------- */
    var promptPasang = null;
    window.addEventListener('beforeinstallprompt', function (e) {
        e.preventDefault(); promptPasang = e;
        document.querySelectorAll('[data-pasang-app]').forEach(function (b) { b.hidden = false; });
    });
    document.addEventListener('click', function (e) {
        var b = e.target.closest('[data-pasang-app]'); if (!b || !promptPasang) return;
        promptPasang.prompt();
        promptPasang.userChoice.finally(function () { promptPasang = null; document.querySelectorAll('[data-pasang-app]').forEach(function (x) { x.hidden = true; }); });
    });
    window.addEventListener('appinstalled', function () { toast('Aplikasi berhasil dipasang di perangkat Anda.', 'ok'); });
})();
