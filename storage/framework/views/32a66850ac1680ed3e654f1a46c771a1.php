<?php $__env->startSection('title', 'Berita dan Pengumuman'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="container">
        <h1>Berita & Pengumuman</h1>
        <p class="mb-0 opacity-75">Informasi terbaru seputar kegiatan dan pengumuman desa</p>
    </div>
</div>

<div class="container py-5">
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="<?php echo e(route('berita.index')); ?>" class="btn btn-sm <?php echo e(!$kategori ? 'btn-hijau' : 'btn-outline-secondary'); ?>">Semua</a>
        <a href="<?php echo e(route('berita.index', ['kategori' => 'berita'])); ?>" class="btn btn-sm <?php echo e($kategori === 'berita' ? 'btn-hijau' : 'btn-outline-secondary'); ?>">Berita</a>
        <a href="<?php echo e(route('berita.index', ['kategori' => 'pengumuman'])); ?>" class="btn btn-sm <?php echo e($kategori === 'pengumuman' ? 'btn-hijau' : 'btn-outline-secondary'); ?>">Pengumuman</a>
    </div>

    <div class="row g-4">
        <?php $__empty_1 = true; $__currentLoopData = $beritas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $berita): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-md-6 col-lg-4">
                <a href="<?php echo e(route('berita.show', $berita->slug)); ?>" class="card h-100 text-decoration-none text-dark">
                    <?php if($berita->gambar): ?>
                        <img src="<?php echo e(asset('storage/' . $berita->gambar)); ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="<?php echo e($berita->judul); ?>">
                    <?php else: ?>
                        <div class="bg-success-subtle d-grid" style="height: 200px; place-items: center;">
                            <i class="bi bi-newspaper fs-1 text-success"></i>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <span class="badge badge-soft-success mb-2 text-capitalize"><?php echo e($berita->kategori); ?></span>
                        <h6 class="fw-bold"><?php echo e($berita->judul); ?></h6>
                        <p class="small text-muted mb-0"><?php echo e(\Illuminate\Support\Str::limit(strip_tags($berita->isi), 110)); ?></p>
                    </div>
                    <div class="card-footer bg-white border-0 small text-muted">
                        <i class="bi bi-calendar3 me-1"></i><?php echo e(($berita->published_at ?? $berita->created_at)->translatedFormat('d F Y')); ?>

                    </div>
                </a>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12"><div class="alert alert-light border text-center text-muted">Belum ada berita pada kategori ini.</div></div>
        <?php endif; ?>
    </div>

    <div class="mt-4"><?php echo e($beritas->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sipades\resources\views/berita/index.blade.php ENDPATH**/ ?>