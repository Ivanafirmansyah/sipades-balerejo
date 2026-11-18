<?php $__env->startSection('title', 'Peta Fasilitas'); ?>
<?php $__env->startSection('subtitle', 'Kelola titik fasilitas umum desa yang tampil di halaman Peta'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('partials.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="card mb-4 peta-admin">
    <div id="petaRingkas" class="peta-kanvas" style="height: 340px;"></div>
</div>

<div class="card">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div class="d-flex flex-wrap gap-2">
                <a href="<?php echo e(route('admin.lokasi.index')); ?>" class="btn btn-sm rounded-pill <?php echo e(request('kategori') ? 'btn-outline-secondary' : 'btn-biru'); ?>">Semua (<?php echo e($jumlah->sum()); ?>)</a>
                <?php $__currentLoopData = \App\Models\LokasiDesa::KATEGORI; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => [$label, $ikon]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($jumlah[$k] ?? 0): ?>
                        <a href="<?php echo e(route('admin.lokasi.index', ['kategori' => $k])); ?>" class="btn btn-sm rounded-pill <?php echo e(request('kategori') === $k ? 'btn-biru' : 'btn-outline-secondary'); ?>"><i class="bi <?php echo e($ikon); ?> me-1"></i><?php echo e($label); ?> (<?php echo e($jumlah[$k]); ?>)</a>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <a href="<?php echo e(route('admin.lokasi.create')); ?>" class="btn btn-sm btn-biru"><i class="bi bi-plus-lg me-1"></i> Tambah Lokasi</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Foto</th><th>Nama</th><th>Kategori</th><th>Koordinat</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $lokasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <?php if($l->foto): ?><img src="<?php echo e($l->foto_url); ?>" alt="" class="agenda-thumb">
                                <?php else: ?><span class="agenda-thumb agenda-thumb-kosong"><i class="bi <?php echo e($l->ikon); ?>"></i></span><?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-semibold"><?php echo e($l->nama); ?> <?php if($l->is_utama): ?><span class="badge badge-soft-warning"><i class="bi bi-star-fill"></i> Utama</span><?php endif; ?></div>
                                <div class="small text-muted"><?php echo e($l->alamat ?: \Illuminate\Support\Str::limit($l->keterangan, 50)); ?></div>
                            </td>
                            <td class="small"><i class="bi <?php echo e($l->ikon); ?> me-1"></i><?php echo e($l->kategori_label); ?></td>
                            <td class="small font-monospace"><a href="#" class="lihat-titik text-decoration-none" data-lat="<?php echo e($l->lat); ?>" data-lng="<?php echo e($l->lng); ?>"><?php echo e(number_format($l->lat, 5)); ?>, <?php echo e(number_format($l->lng, 5)); ?></a></td>
                            <td><span class="badge badge-soft-<?php echo e($l->is_aktif ? 'success' : 'secondary'); ?>"><?php echo e($l->is_aktif ? 'Tampil' : 'Disembunyikan'); ?></span></td>
                            <td class="text-end text-nowrap">
                                <a href="<?php echo e(route('admin.lokasi.edit', $l)); ?>" class="btn btn-sm btn-outline-primary" title="Ubah"><i class="bi bi-pencil"></i></a>
                                <form action="<?php echo e(route('admin.lokasi.destroy', $l)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus lokasi <?php echo e($l->nama); ?> dari peta?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="text-center text-muted py-5"><i class="bi bi-geo-alt fs-2 d-block mb-2"></i>Belum ada lokasi. Mulai dengan menambahkan kantor/balai desa sebagai titik utama.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="<?php echo e(asset('js/peta-desa.js')); ?>?v=1"></script>
<script>
    (function () {
        var data = <?php echo json_encode($semua, 15, 512) ?>;
        var peta = PetaDesa.buat('petaRingkas', <?php echo json_encode($pusat, 15, 512) ?>, 16);
        var grup = [];
        data.forEach(function (l) {
            grup.push(L.marker([l.lat, l.lng], { icon: PetaDesa.ikon(l.ikon, l.utama) }).bindPopup(PetaDesa.popup(l)).addTo(peta));
        });
        if (grup.length > 1) peta.fitBounds(L.featureGroup(grup).getBounds().pad(0.2));
        document.querySelectorAll('.lihat-titik').forEach(function (a) {
            a.addEventListener('click', function (e) { e.preventDefault(); peta.setView([+a.dataset.lat, +a.dataset.lng], 18); window.scrollTo({ top: 0, behavior: 'smooth' }); });
        });
    })();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sipades\resources\views/admin/lokasi/index.blade.php ENDPATH**/ ?>