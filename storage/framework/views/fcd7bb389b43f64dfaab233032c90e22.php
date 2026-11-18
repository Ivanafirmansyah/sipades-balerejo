

<?php $__env->startSection('title', 'Manajemen Admin'); ?>
<?php $__env->startSection('subtitle', 'Kelola akun admin dan warga'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('partials.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h6 class="fw-bold mb-0">Daftar Akun</h6>
            <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-sm btn-hijau"><i class="bi bi-person-plus me-1"></i> Tambah Akun</a>
        </div>

        <form action="<?php echo e(route('admin.users.index')); ?>" method="GET" class="row g-2 mb-3">
            <div class="col-md-5">
                <input type="text" name="q" value="<?php echo e(request('q')); ?>" class="form-control form-control-sm" placeholder="Cari nama / email...">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select form-select-sm">
                    <option value="">Semua Role</option>
                    <option value="admin" <?php if(request('role') === 'admin'): echo 'selected'; endif; ?>>Admin</option>
                    <option value="user" <?php if(request('role') === 'user'): echo 'selected'; endif; ?>>Warga</option>
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-sm btn-hijau w-100">Filter</button></div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr><th>Nama</th><th>Email</th><th>No. HP</th><th>Role</th><th>Dibuat</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="fw-semibold"><?php echo e($user->name); ?> <?php if($user->id === auth()->id()): ?><span class="badge badge-soft-info">Anda</span><?php endif; ?></td>
                            <td class="small"><?php echo e($user->email ?? ''); ?><?php if($user->nik): ?><div class="text-muted font-monospace"><i class="bi bi-person-vcard me-1"></i><?php echo e($user->nik); ?></div><?php endif; ?></td>
                            <td class="small"><?php echo e($user->phone ?? '-'); ?></td>
                            <td>
                                <span class="badge badge-soft-<?php echo e($user->role === 'admin' ? 'success' : 'secondary'); ?>">
                                    <?php echo e($user->role === 'admin' ? 'Admin' : 'Warga'); ?>

                                </span>
                            </td>
                            <td class="small"><?php echo e($user->created_at->format('d/m/Y')); ?></td>
                            <td class="text-end text-nowrap">
                                <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <?php if($user->id !== auth()->id()): ?>
                                    <form action="<?php echo e(route('admin.users.destroy', $user)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus akun <?php echo e($user->name); ?>?')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada akun.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php echo e($users->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views\admin\users\index.blade.php ENDPATH**/ ?>