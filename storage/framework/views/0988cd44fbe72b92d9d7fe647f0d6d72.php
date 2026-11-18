<?php $__env->startSection('title', 'Riwayat Pengaduan'); ?>
<?php $__env->startSection('subtitle', 'Daftar pengaduan dan tanggapan'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('partials.sidebar-user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-end mb-3">
    <a href="<?php echo e(route('user.pengaduan.create')); ?>" class="btn btn-hijau btn-sm"><i class="bi bi-plus-lg me-1"></i> Buat Pengaduan</a>
</div>

<div class="row g-3">
    <?php $__empty_1 = true; $__currentLoopData = $pengaduans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pengaduan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-12">
            <div class="card">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between gap-2">
                        <div>
                            <span class="badge badge-soft-secondary"><?php echo e($pengaduan->kategori); ?></span>
                            <?php ($badge = ['baru' => 'warning', 'diproses' => 'info', 'selesai' => 'success'][$pengaduan->status]); ?>
                            <span class="badge badge-soft-<?php echo e($badge); ?>"><?php echo e($pengaduan->statusLabel()); ?></span>
                        </div>
                        <div class="small text-muted"><?php echo e($pengaduan->created_at->translatedFormat('d F Y, H:i')); ?></div>
                    </div>
                    <p class="mt-2 mb-1"><?php echo e($pengaduan->isi); ?></p>
                    <?php if($pengaduan->lokasi): ?>
                        <div class="small text-muted"><i class="bi bi-geo-alt me-1"></i><?php echo e($pengaduan->lokasi); ?></div>
                    <?php endif; ?>
                    <?php if($pengaduan->tanggapan): ?>
                        <div class="alert alert-success mt-3 mb-0 small">
                            <div class="fw-semibold mb-1"><i class="bi bi-reply-fill me-1"></i>Tanggapan Admin</div>
                            <?php echo e($pengaduan->tanggapan); ?>

                            <div class="text-muted mt-1"><?php echo e($pengaduan->tanggapan_at?->translatedFormat('d F Y, H:i')); ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
            <div class="alert alert-light border text-center text-muted">Belum ada pengaduan. <a href="<?php echo e(route('user.pengaduan.create')); ?>">Buat pengaduan</a>.</div>
        </div>
    <?php endif; ?>
</div>

<div class="mt-3"><?php echo e($pengaduans->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sipades\resources\views/user/pengaduan/index.blade.php ENDPATH**/ ?>