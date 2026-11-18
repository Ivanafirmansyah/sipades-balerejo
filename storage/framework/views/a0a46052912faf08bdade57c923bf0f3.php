<?php $__env->startSection('title', 'Berita & Pengumuman'); ?>
<?php $__env->startSection('subtitle', 'Kelola berita dan pengumuman desa'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('partials.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h6 class="fw-bold mb-0">Daftar Berita</h6>
            <a href="<?php echo e(route('admin.berita.create')); ?>" class="btn btn-sm btn-hijau"><i class="bi bi-plus-lg me-1"></i> Tambah Berita</a>
        </div>

        <form action="<?php echo e(route('admin.berita.index')); ?>" method="GET" class="row g-2 mb-3">
            <div class="col-md-5">
                <input type="text" name="q" value="<?php echo e(request('q')); ?>" class="form-control form-control-sm" placeholder="Cari judul...">
            </div>
            <div class="col-md-3">
                <select name="kategori" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    <option value="berita" <?php if(request('kategori') === 'berita'): echo 'selected'; endif; ?>>Berita</option>
                    <option value="pengumuman" <?php if(request('kategori') === 'pengumuman'): echo 'selected'; endif; ?>>Pengumuman</option>
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-sm btn-hijau w-100">Filter</button></div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr><th>Judul</th><th>Kategori</th><th>Penulis</th><th>Tanggal</th><th>Status</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $beritas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $berita): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if($berita->gambar): ?>
                                        <img src="<?php echo e(asset('storage/' . $berita->gambar)); ?>" width="56" height="42" class="rounded" style="object-fit: cover;" alt="">
                                    <?php else: ?>
                                        <div class="bg-success-subtle rounded d-grid" style="width: 56px; height: 42px; place-items: center;"><i class="bi bi-newspaper text-success"></i></div>
                                    <?php endif; ?>
                                    <div class="fw-semibold"><?php echo e(\Illuminate\Support\Str::limit($berita->judul, 50)); ?></div>
                                </div>
                            </td>
                            <td><span class="badge badge-soft-success text-capitalize"><?php echo e($berita->kategori); ?></span></td>
                            <td class="small"><?php echo e($berita->penulis ?? '-'); ?></td>
                            <td class="small"><?php echo e($berita->published_at?->format('d/m/Y') ?? '-'); ?></td>
                            <td>
                                <span class="badge badge-soft-<?php echo e($berita->is_published ? 'success' : 'secondary'); ?>">
                                    <?php echo e($berita->is_published ? 'Terbit' : 'Draft'); ?>

                                </span>
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="<?php echo e(route('berita.show', $berita->slug)); ?>" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                <a href="<?php echo e(route('admin.berita.edit', $berita)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form action="<?php echo e(route('admin.berita.destroy', $berita)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus berita ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada berita.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php echo e($beritas->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sipades\resources\views/admin/berita/index.blade.php ENDPATH**/ ?>