
<div class="net-banner" id="netBanner" hidden role="status">
    <i class="bi bi-wifi-off"></i> Anda sedang offline — formulir tetap bisa diisi dan akan terkirim otomatis saat sinyal kembali.
</div>

<div class="antrian-dock" id="antrianDock" hidden>
    <button type="button" class="antrian-toggle" id="antrianToggle" aria-label="Lihat antrian kiriman">
        <span class="antrian-ic"><i class="bi bi-cloud-arrow-up-fill"></i><span class="antrian-count" id="antrianCount">0</span></span>
        <span class="antrian-judul" id="antrianJudul">Isian menunggu dikirim</span>
        <i class="bi bi-chevron-up antrian-chev"></i>
    </button>
    <div class="antrian-panel">
        <div class="small text-muted mb-2">Isian ini tersimpan aman di perangkat Anda dan akan terkirim otomatis saat ada sinyal.</div>
        <div class="alert alert-warning small py-2 mb-2" id="antrianLogin" hidden>
            <i class="bi bi-lock me-1"></i> Beberapa isian perlu login. <a href="<?php echo e(route('login')); ?>" class="fw-bold">Masuk sekarang</a>
        </div>
        <ul class="antrian-list" id="antrianList"></ul>
        <button type="button" class="btn btn-biru btn-sm w-100 mt-2" id="antrianKirim"><i class="bi bi-send me-1"></i> Kirim Sekarang</button>
    </div>
</div>

<div class="pwa-toasts" id="pwaToasts" aria-live="polite"></div>

<script src="<?php echo e(asset('js/antrian.js')); ?>?v=3"></script>
<script src="<?php echo e(asset('js/pwa.js')); ?>?v=4"></script>
<?php /**PATH E:\laragon\www\sipades\resources\views\partials\pwa-ui.blade.php ENDPATH**/ ?>