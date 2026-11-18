<?php $__env->startSection('title', $berita->judul); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="container">
        <span class="badge badge-soft-success text-capitalize mb-2"><?php echo e($berita->kategori); ?></span>
        <h1><?php echo e($berita->judul); ?></h1>
        <p class="mb-0 opacity-75">
            <i class="bi bi-person me-1"></i><?php echo e($berita->penulis ?? 'Admin Desa'); ?>

            &middot; <i class="bi bi-calendar3 me-1"></i><?php echo e(($berita->published_at ?? $berita->created_at)->translatedFormat('d F Y')); ?>

        </p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <?php if($berita->gambar): ?>
                        <img src="<?php echo e(asset('storage/' . $berita->gambar)); ?>" class="img-fluid rounded mb-4 w-100" style="max-height: 420px; object-fit: cover;" alt="<?php echo e($berita->judul); ?>">
                    <?php endif; ?>
                    <div style="white-space: pre-line; text-align: justify;" class="text-muted"><?php echo e($berita->isi); ?></div>
                    <a href="<?php echo e(route('berita.index')); ?>" class="btn btn-outline-success btn-sm mt-4"><i class="bi bi-arrow-left me-1"></i> Kembali ke daftar</a>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Berita Lainnya</h6>
                    <div class="d-grid gap-3">
                        <?php $__empty_1 = true; $__currentLoopData = $lainnya; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <a href="<?php echo e(route('berita.show', $item->slug)); ?>" class="text-decoration-none text-dark d-flex gap-2">
                                <?php if($item->gambar): ?>
                                    <img src="<?php echo e(asset('storage/' . $item->gambar)); ?>" width="70" height="60" class="rounded object-fit-cover" style="object-fit: cover;" alt="">
                                <?php else: ?>
                                    <div class="bg-success-subtle rounded d-grid" style="width: 70px; height: 60px; place-items: center;"><i class="bi bi-newspaper text-success"></i></div>
                                <?php endif; ?>
                                <div>
                                    <div class="small fw-semibold"><?php echo e(\Illuminate\Support\Str::limit($item->judul, 60)); ?></div>
                                    <div class="small text-muted"><?php echo e($item->created_at->format('d/m/Y')); ?></div>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="small text-muted">Belum ada berita lainnya.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views\berita\show.blade.php ENDPATH**/ ?>