<?php $__env->startSection('title', 'Formulir Online'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="container">
        <h1>Formulir Online</h1>
        <p class="mb-0 opacity-75">Isi formulir surat dan pendataan desa — tetap bisa diisi saat tidak ada sinyal</p>
    </div>
</div>

<div class="container py-5">
    <div class="offline-ready-note mb-4" id="offlineReady" hidden>
        <i class="bi bi-cloud-check-fill"></i>
        <span><b>Semua formulir di bawah sudah tersimpan untuk offline.</b> Anda bisa membuka dan mengisinya meski tanpa sinyal.</span>
    </div>

    <div class="row g-4">
        <?php $__empty_1 = true; $__currentLoopData = $formulirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-md-6 col-lg-4 reveal" style="transition-delay: <?php echo e(($i % 3) * 100); ?>ms">
                <a href="<?php echo e($f->isDinamis() ? route('formulir.show', $f->slug) : $f->link); ?>" class="form-tile" <?php if (! ($f->isDinamis())): ?> target="_blank" rel="noopener" <?php endif; ?>
                   <?php if($f->isDinamis()): ?> data-offline-url="<?php echo e(route('formulir.show', $f->slug)); ?>" <?php endif; ?>>
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="form-tile-icon"><i class="bi <?php echo e($f->isSurat() ? 'bi-envelope-paper' : ($f->isDinamis() ? 'bi-ui-checks' : 'bi-link-45deg')); ?>"></i></span>
                        <span class="d-flex gap-1 flex-wrap justify-content-end">
                            <?php if($f->isSurat()): ?><span class="badge badge-soft-info">Surat</span><?php elseif($f->isDinamis()): ?><span class="badge badge-soft-success">Pendataan</span><?php else: ?><span class="badge badge-soft-secondary">Tautan luar</span><?php endif; ?>
                            <?php if($f->butuh_login): ?><span class="badge badge-soft-warning"><i class="bi bi-lock"></i> Login</span><?php endif; ?>
                        </span>
                    </div>
                    <h6><?php echo e($f->judul); ?></h6>
                    <p><?php echo e(\Illuminate\Support\Str::limit($f->deskripsi, 110) ?: 'Isi formulir ini secara online.'); ?></p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="link-biru"><?php echo e($f->isDinamis() ? 'Isi Formulir' : 'Buka Tautan'); ?> <i class="bi bi-arrow-right"></i></span>
                        <?php if($f->isDinamis()): ?><span class="offline-chip" hidden><i class="bi bi-cloud-check"></i> Offline</span><?php endif; ?>
                    </div>
                </a>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12"><div class="alert alert-light border text-center text-muted">Belum ada formulir yang tersedia.</div></div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views/formulir/index.blade.php ENDPATH**/ ?>