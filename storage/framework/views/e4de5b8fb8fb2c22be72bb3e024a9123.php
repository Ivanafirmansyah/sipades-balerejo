

<?php $__env->startSection('title', 'Perangkat Desa'); ?>
<?php $__env->startSection('subtitle', 'Kelola struktur dan perangkat desa'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('partials.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Daftar Perangkat</h6>
            <a href="<?php echo e(route('admin.perangkat.create')); ?>" class="btn btn-sm btn-hijau"><i class="bi bi-plus-lg me-1"></i> Tambah Perangkat</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr><th>Nama</th><th>Jabatan</th><th>Atasan Langsung</th><th>Keterangan</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $perangkats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perangkat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if($perangkat->foto): ?>
                                        <img src="<?php echo e(asset('storage/' . $perangkat->foto)); ?>" width="46" height="46" class="rounded-circle" style="object-fit: cover;" alt="">
                                    <?php else: ?>
                                        <span class="avatar-inisial"><?php echo e(strtoupper(substr($perangkat->nama, 0, 1))); ?></span>
                                    <?php endif; ?>
                                    <span class="fw-semibold"><?php echo e($perangkat->nama); ?></span>
                                </div>
                            </td>
                            <td class="small"><?php echo e($perangkat->jabatan); ?></td>
                            <td class="small">
                                <?php if($perangkat->is_mitra): ?><span class="badge badge-soft-info">Lembaga mitra</span>
                                <?php elseif($perangkat->atasan): ?><?php echo e($perangkat->atasan->jabatan); ?>

                                <?php else: ?><span class="badge badge-soft-warning"><i class="bi bi-star-fill"></i> Puncak struktur</span><?php endif; ?>
                            </td>
                            <td class="small text-muted"><?php echo e($perangkat->keterangan ?? '-'); ?></td>
                            <td class="text-end text-nowrap">
                                <a href="<?php echo e(route('admin.perangkat.edit', $perangkat)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form action="<?php echo e(route('admin.perangkat.destroy', $perangkat)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus perangkat ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data perangkat desa.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sipades\resources\views/admin/perangkat/index.blade.php ENDPATH**/ ?>