/* =========================================================
   SIPADES — Peta Fasilitas Desa (Leaflet + OpenStreetMap)
   Fungsi bersama untuk halaman peta warga & admin.
   ========================================================= */
(function () {
    'use strict';

    function esc(s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; });
    }

    var PetaDesa = {
        esc: esc,

        /** Peta dasar OpenStreetMap. */
        buat: function (el, pusat, zoom) {
            var peta = L.map(el, { scrollWheelZoom: false }).setView(pusat, zoom || 16);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a>'
            }).addTo(peta);
            // gulir peta hanya setelah peta diklik (agar tidak "tersangkut" saat menggulir halaman)
            peta.on('click focus', function () { peta.scrollWheelZoom.enable(); });
            peta.on('mouseout', function () { peta.scrollWheelZoom.disable(); });
            return peta;
        },

        /** Ikon penanda bulat biru-kuning sesuai kategori. */
        ikon: function (ikonBi, utama, aktif) {
            return L.divIcon({
                className: 'pin-desa' + (utama ? ' utama' : '') + (aktif ? ' aktif' : ''),
                html: '<span class="pin-bulat"><i class="bi ' + esc(ikonBi) + '"></i></span>',
                iconSize: [38, 46], iconAnchor: [19, 44], popupAnchor: [0, -40]
            });
        },

        /** Isi popup sebuah lokasi. */
        popup: function (l) {
            return '<div class="popup-desa">' +
                (l.foto ? '<img src="' + esc(l.foto) + '" alt="' + esc(l.nama) + '">' : '') +
                '<div class="pd-isi"><span class="pd-kat"><i class="bi ' + esc(l.ikon) + '"></i> ' + esc(l.label) + '</span>' +
                '<b>' + esc(l.nama) + '</b>' +
                (l.alamat ? '<div class="pd-alamat"><i class="bi bi-geo-alt"></i> ' + esc(l.alamat) + '</div>' : '') +
                (l.keterangan ? '<div class="pd-ket">' + esc(l.keterangan) + '</div>' : '') +
                '<a class="pd-arah" href="' + esc(l.arah) + '" target="_blank" rel="noopener"><i class="bi bi-sign-turn-right-fill"></i> Petunjuk arah</a></div></div>';
        }
    };

    window.PetaDesa = PetaDesa;
})();
