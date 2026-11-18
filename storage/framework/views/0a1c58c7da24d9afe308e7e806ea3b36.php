<?php $__env->startSection('title', 'Peta Desa'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="container">
        <h1>Peta Desa</h1>
        <p class="mb-0 opacity-75">Lokasi fasilitas umum di <?php echo e($profil->nama_desa); ?></p>
    </div>
</div>

<div class="container py-5">
    <?php if($lokasis->isNotEmpty()): ?>
        
        <div class="d-flex flex-wrap gap-2 mb-3 reveal" id="filterPeta">
            <button type="button" class="filter-chip aktif" data-kat="">Semua <span><?php echo e($lokasis->count()); ?></span></button>
            <?php $__currentLoopData = \App\Models\LokasiDesa::KATEGORI; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => [$label, $ikon]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($jumlah[$k] ?? 0): ?>
                    <button type="button" class="filter-chip" data-kat="<?php echo e($k); ?>"><i class="bi <?php echo e($ikon); ?>"></i> <?php echo e($label); ?> <span><?php echo e($jumlah[$k]); ?></span></button>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card peta-publik"><div id="petaDesa" class="peta-kanvas"></div></div>
                <div class="small text-muted mt-2"><i class="bi bi-wifi-off me-1"></i> Gambar peta memerlukan koneksi internet. Daftar fasilitas di samping tetap bisa dilihat saat offline.</div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-body p-3">
                        <h6 class="fw-bold px-1 mb-2"><i class="bi bi-list-ul me-1"></i> Daftar Fasilitas</h6>
                        <ul class="daftar-lokasi" id="daftarLokasi">
                            <?php $__currentLoopData = $lokasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li data-kat="<?php echo e($l->kategori); ?>">
                                    <button type="button" class="lokasi-item" data-id="<?php echo e($l->id); ?>">
                                        <span class="li-ikon <?php if($l->is_utama): ?> utama <?php endif; ?>"><i class="bi <?php echo e($l->ikon); ?>"></i></span>
                                        <span class="flex-grow-1 min-w-0 text-start">
                                            <b><?php echo e($l->nama); ?></b>
                                            <small><?php echo e($l->kategori_label); ?><?php if($l->alamat): ?> · <?php echo e($l->alamat); ?><?php endif; ?></small>
                                        </span>
                                        <i class="bi bi-chevron-right li-panah"></i>
                                    </button>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php elseif($profil->peta_embed): ?>
        <div class="card"><div class="card-body p-3"><div class="ratio" style="--bs-aspect-ratio: 55%;"><?php echo $profil->peta_embed; ?></div></div></div>
    <?php else: ?>
        <div class="card"><div class="card-body p-5 text-center text-muted"><i class="bi bi-map fs-1 d-block mb-2"></i>Peta desa belum diatur oleh admin.</div></div>
    <?php endif; ?>

    <div class="card mt-4">
        <div class="card-body p-4">
            <h5 class="section-title mb-3">Alamat Kantor Desa</h5>
            <p class="mb-1"><i class="bi bi-geo-alt text-hijau me-2"></i><?php echo e($profil->alamat_kantor ?? '-'); ?></p>
            <p class="mb-1"><i class="bi bi-telephone text-hijau me-2"></i><?php echo e($profil->telepon ?? '-'); ?></p>
            <p class="mb-0"><i class="bi bi-envelope text-hijau me-2"></i><?php echo e($profil->email ?? '-'); ?></p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php if($lokasis->isNotEmpty()): ?>
<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="<?php echo e(asset('js/peta-desa.js')); ?>?v=1"></script>
<script>
    (function () {
        var data = <?php echo json_encode($data, 15, 512) ?>;
        if (!window.L) return; // offline & Leaflet belum tersimpan: daftar tetap tampil
        var peta = PetaDesa.buat('petaDesa', <?php echo json_encode($pusat, 15, 512) ?>, 16);
        var penanda = {};
        data.forEach(function (l) {
            penanda[l.id] = { l: l, m: L.marker([l.lat, l.lng], { icon: PetaDesa.ikon(l.ikon, l.utama), title: l.nama }).bindPopup(PetaDesa.popup(l), { maxWidth: 260 }) };
        });

        function tampilkan(kat) {
            var terlihat = [];
            Object.keys(penanda).forEach(function (id) {
                var p = penanda[id], ok = !kat || p.l.kategori === kat;
                if (ok) { p.m.addTo(peta); terlihat.push(p.m); } else peta.removeLayer(p.m);
            });
            document.querySelectorAll('#daftarLokasi li').forEach(function (li) { li.hidden = !!kat && li.dataset.kat !== kat; });
            if (terlihat.length > 1) peta.fitBounds(L.featureGroup(terlihat).getBounds().pad(0.25), { maxZoom: 17 });
            else if (terlihat.length === 1) peta.setView(terlihat[0].getLatLng(), 17);
        }

        document.querySelectorAll('#filterPeta .filter-chip').forEach(function (b) {
            b.addEventListener('click', function () {
                document.querySelectorAll('#filterPeta .filter-chip').forEach(function (x) { x.classList.toggle('aktif', x === b); });
                tampilkan(b.dataset.kat);
            });
        });

        document.querySelectorAll('.lokasi-item').forEach(function (b) {
            b.addEventListener('click', function () {
                var p = penanda[b.dataset.id];
                peta.setView(p.m.getLatLng(), 18);
                p.m.openPopup();
                if (window.innerWidth < 992) document.getElementById('petaDesa').scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        });

        tampilkan('');
    })();
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views\peta.blade.php ENDPATH**/ ?>