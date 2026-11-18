<?php $__env->startSection('title', 'Pengaduan Warga'); ?>
<?php $__env->startSection('subtitle', 'Kelola laporan dan pengaduan masyarakat'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('partials.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3 mb-4">
    <?php $__currentLoopData = ['baru' => ['Pengaduan Baru', 'bi-inbox-fill'], 'diproses' => ['Sedang Diproses', 'bi-arrow-repeat'], 'selesai' => ['Selesai', 'bi-check2-circle']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-6 col-lg-3">
            <a href="<?php echo e(route('admin.pengaduan.index', ['status' => $key])); ?>" class="text-decoration-none">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon"><i class="bi <?php echo e($item[1]); ?>"></i></div>
                        <div>
                            <div class="fs-4 fw-bold lh-1 mb-1"><?php echo e($stats[$key]); ?></div>
                            <div class="small text-muted"><?php echo e($item[0]); ?></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <div class="col-6 col-lg-3">
        <a href="<?php echo e(route('admin.pengaduan.export', request()->only('status'))); ?>" class="export-card">
            <span class="export-icon"><i class="bi bi-file-earmark-pdf-fill"></i></span>
            <span class="flex-grow-1 lh-sm">
                <span class="d-block fw-bold">Export PDF</span>
                <span class="d-block small export-sub">
                    <?php echo e(request('status') ? 'Pengaduan ' . ucfirst(request('status')) : 'Semua pengaduan'); ?>

                </span>
            </span>
            <i class="bi bi-download export-dl"></i>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="<?php echo e(route('admin.pengaduan.index')); ?>" method="GET" class="row g-2 mb-3">
            <div class="col-md-5">
                <input type="text" name="q" value="<?php echo e(request('q')); ?>" class="form-control form-control-sm" placeholder="Cari isi / pelapor...">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <?php $__currentLoopData = ['baru' => 'Baru', 'diproses' => 'Diproses', 'selesai' => 'Selesai']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php if(request('status') === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-sm btn-hijau"><i class="bi bi-search me-1"></i> Cari</button>
                <a href="<?php echo e(route('admin.pengaduan.index')); ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr><th>#</th><th>Pelapor</th><th>Kategori</th><th>Isi</th><th>Tanggal</th><th>Status</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $pengaduans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pengaduan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($pengaduan->id); ?></td>
                            <td class="small fw-semibold"><?php echo e($pengaduan->nama_pelapor ?? $pengaduan->user->name ?? '-'); ?></td>
                            <td class="small"><?php echo e($pengaduan->kategori); ?></td>
                            <td class="small text-muted"><?php echo e(\Illuminate\Support\Str::limit($pengaduan->isi, 60)); ?></td>
                            <td class="small"><?php echo e($pengaduan->created_at->format('d/m/Y')); ?></td>
                            <td>
                                <?php ($badge = ['baru' => 'warning', 'diproses' => 'info', 'selesai' => 'success'][$pengaduan->status]); ?>
                                <span class="badge badge-soft-<?php echo e($badge); ?>"><?php echo e($pengaduan->statusLabel()); ?></span>
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="<?php echo e(route('admin.pengaduan.show', $pengaduan)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Tanggapi</a>
                                <form action="<?php echo e(route('admin.pengaduan.destroy', $pengaduan)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus pengaduan ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada pengaduan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php echo e($pengaduans->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views\admin\pengaduan\index.blade.php ENDPATH**/ ?>