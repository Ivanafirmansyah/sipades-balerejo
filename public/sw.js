/* =========================================================
   SIPADES — Service Worker (Progressive Web App, offline-first)

   - Menyimpan tampilan dasar (CSS, JS, ikon, font) agar situs terbuka tanpa sinyal.
   - Halaman publik & formulir: ambil dari jaringan, simpan salinan; saat offline pakai salinan.
   - Halaman admin/dashboard tidak disimpan (data pribadi).
   - Background Sync: antrian isian formulir dikirim otomatis saat sinyal kembali,
     bahkan bila halaman sudah ditutup (di browser yang mendukung).
   ========================================================= */
importScripts('/js/antrian.js');

var VERSI = 'sipades-v5';
var CACHE_DASAR = VERSI + '-dasar';
var CACHE_HALAMAN = VERSI + '-halaman';
var CACHE_ASET = VERSI + '-aset';

var PRECACHE = [
    '/offline',
    '/formulir',
    '/css/sipades-theme.css',
    '/css/sipades-fitur.css',
    '/js/antrian.js',
    '/js/pwa.js',
    '/icons/icon-192.png',
    '/icons/icon-512.png',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css',
    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/fonts/bootstrap-icons.woff2'
];

// Halaman yang tidak boleh disimpan (berisi data pribadi / aksi)
var JANGAN_SIMPAN = /^\/(admin|dashboard|akun|login|register|logout)(\/|$)/;
var HOST_ASET = ['cdn.jsdelivr.net', 'fonts.googleapis.com', 'fonts.gstatic.com', 'images.unsplash.com'];

self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open(CACHE_DASAR).then(function (cache) {
            // Satu per satu agar satu kegagalan tidak menggagalkan semuanya
            return Promise.all(PRECACHE.map(function (url) {
                return fetch(new Request(url, { cache: 'reload', credentials: 'same-origin' }))
                    .then(function (res) { if (res.ok) return cache.put(url, res); })
                    .catch(function () {});
            }));
        }).then(function () { return self.skipWaiting(); })
    );
});

self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function (keys) {
            return Promise.all(keys.filter(function (k) { return k.indexOf(VERSI) !== 0; }).map(function (k) { return caches.delete(k); }));
        }).then(function () { return self.clients.claim(); })
    );
});

function cocokkan(request) {
    return caches.match(request).then(function (hit) {
        return hit || caches.match(request, { ignoreSearch: true });
    });
}

// Jaringan dulu, salinan sebagai cadangan (untuk halaman)
function jaringanDulu(request, boleSimpan) {
    return fetch(request).then(function (res) {
        if (boleSimpan && res.ok && res.type === 'basic') {
            var salin = res.clone();
            caches.open(CACHE_HALAMAN).then(function (c) { c.put(request, salin); });
        }
        return res;
    }).catch(function () {
        return (boleSimpan ? caches.match(request) : Promise.resolve(null)).then(function (hit) {
            return hit || caches.match('/offline');
        });
    });
}

// Aset: URL yang sama persis (termasuk ?v=) -> pakai salinan, perbarui diam-diam di belakang.
// URL versi baru (?v= berubah) belum ada salinannya -> ambil dari jaringan dulu, agar perubahan
// CSS/JS langsung berlaku. Salinan versi lama (abaikan ?v=) hanya dipakai saat offline.
function salinanDulu(request) {
    return caches.match(request).then(function (persis) {
        var ambil = fetch(request).then(function (res) {
            if (res && (res.ok || res.type === 'opaque')) {
                var salin = res.clone();
                caches.open(CACHE_ASET).then(function (c) { c.put(request, salin); });
            }
            return res;
        });
        if (persis) {
            ambil.catch(function () {});
            return persis;
        }
        return ambil.catch(function () { return cocokkan(request); });
    });
}

self.addEventListener('fetch', function (event) {
    var req = event.request;
    if (req.method !== 'GET') return;
    var url = new URL(req.url);

    if (req.mode === 'navigate') {
        var boleh = url.origin === self.location.origin && !JANGAN_SIMPAN.test(url.pathname);
        event.respondWith(jaringanDulu(req, boleh));
        return;
    }

    var asetLokal = url.origin === self.location.origin &&
        (/\.(css|js|png|jpe?g|webp|gif|svg|ico|woff2?)$/i.test(url.pathname) || url.pathname.indexOf('/storage/') === 0);
    if (url.pathname === '/sw.js') return;
    if (asetLokal || HOST_ASET.indexOf(url.hostname) > -1) {
        event.respondWith(salinanDulu(req));
    }
});

// Halaman meminta daftar URL (mis. semua formulir) disimpan untuk dipakai offline
self.addEventListener('message', function (event) {
    var msg = event.data || {};
    if (msg.type === 'simpan-halaman' && Array.isArray(msg.urls)) {
        event.waitUntil(
            caches.open(CACHE_HALAMAN).then(function (cache) {
                return Promise.all(msg.urls.map(function (u) {
                    return fetch(u, { credentials: 'same-origin' }).then(function (res) {
                        if (res.ok) { return cache.put(u, res).then(function () { return u; }); }
                    }).catch(function () { return null; });
                }));
            }).then(function (hasil) {
                var ok = hasil.filter(Boolean);
                if (event.source) event.source.postMessage({ type: 'halaman-tersimpan', urls: ok });
            })
        );
    }
    if (msg.type === 'sinkron') {
        event.waitUntil(self.SipadesAntrian.sinkron());
    }
});

// Background Sync: kirim antrian saat sinyal kembali, walau halaman sudah ditutup
self.addEventListener('sync', function (event) {
    if (event.tag === 'kirim-antrian') {
        event.waitUntil(self.SipadesAntrian.sinkron().then(function (r) {
            if (r && r.terkirim && self.registration.showNotification && self.Notification && Notification.permission === 'granted') {
                return self.registration.showNotification('Formulir terkirim', {
                    body: r.terkirim + ' isian formulir yang diisi saat offline sudah terkirim.',
                    icon: '/icons/icon-192.png', badge: '/icons/icon-192.png', tag: 'sipades-sync'
                });
            }
        }));
    }
});
