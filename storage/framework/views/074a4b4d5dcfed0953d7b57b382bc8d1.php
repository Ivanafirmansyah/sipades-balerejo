<?php $__env->startSection('title', 'Struktur Organisasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="container">
        <h1>Struktur Organisasi</h1>
        <p class="mb-0 opacity-75">Susunan pemerintahan <?php echo e($profil->nama_desa); ?></p>
    </div>
</div>

<div class="container py-5">
    <?php if($akar->isEmpty() && $mitra->isEmpty()): ?>
        <div class="alert alert-light border text-center text-muted">Data perangkat desa belum tersedia.</div>
    <?php else: ?>
        <div class="card">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <span class="small text-muted"><i class="bi bi-people me-1"></i><?php echo e($jumlah); ?> anggota</span>
                    <div class="pohon-legenda small">
                        <span><i class="garis-utuh"></i> Garis komando (atasan langsung)</span>
                        <?php if($mitra->isNotEmpty()): ?><span><i class="garis-putus"></i> Garis koordinasi (lembaga mitra)</span><?php endif; ?>
                    </div>
                </div>

                <div class="pohon" id="pohon">
                    <ul>
                        <?php $__currentLoopData = $akar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo $__env->make('struktur._simpul', ['p' => $p, 'level' => 0, 'mitra' => $i === 0 ? $mitra : collect()], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        </div>

        <p class="small text-muted text-center mt-3 mb-0">
            Susunan mengacu pada Permendagri No. 84 Tahun 2015 tentang Susunan Organisasi dan Tata Kerja Pemerintah Desa.
        </p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sipades\resources\views/struktur.blade.php ENDPATH**/ ?>