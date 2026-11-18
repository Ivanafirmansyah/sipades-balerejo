<?php $__env->startSection('title', 'Riwayat Surat'); ?>
<?php $__env->startSection('subtitle', 'Pantau status pengajuan surat Anda'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('partials.sidebar-user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h6 class="fw-bold mb-0">Daftar Pengajuan Surat</h6>
            <form action="<?php echo e(route('user.surat.index')); ?>" method="GET" class="d-flex gap-2">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <?php $__currentLoopData = ['menunggu' => 'Menunggu', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php if(request('status') === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <a href="<?php echo e(route('user.surat.create')); ?>" class="btn btn-sm btn-hijau text-nowrap"><i class="bi bi-plus-lg me-1"></i> Ajukan</a>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Jenis Surat</th>
                        <th>Keperluan</th>
                        <th>No. Surat</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $surats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $surat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($surat->id); ?></td>
                            <td class="fw-semibold"><?php echo e($surat->jenisSurat->nama ?? '-'); ?></td>
                            <td class="small text-muted"><?php echo e(\Illuminate\Support\Str::limit($surat->keperluan, 35)); ?></td>
                            <td><?php echo e($surat->no_surat ?? '-'); ?></td>
                            <td class="small"><?php echo e($surat->created_at->format('d/m/Y')); ?></td>
                            <td>
                                <?php ($badge = ['menunggu' => 'warning', 'diproses' => 'info', 'selesai' => 'success', 'ditolak' => 'danger'][$surat->status]); ?>
                                <span class="badge badge-soft-<?php echo e($badge); ?>"><?php echo e($surat->statusLabel()); ?></span>
                                <?php if($surat->catatan_admin): ?>
                                    <div class="small text-muted mt-1"><i class="bi bi-chat-left-text me-1"></i><?php echo e($surat->catatan_admin); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <?php if($surat->status === 'selesai'): ?>
                                    <a href="<?php echo e(route('user.surat.pdf', $surat)); ?>" class="btn btn-sm btn-outline-success" title="Unduh PDF">
                                        <i class="bi bi-download"></i> PDF
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada pengajuan surat. <a href="<?php echo e(route('user.surat.create')); ?>">Ajukan sekarang</a>.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php echo e($surats->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views\user\surat\index.blade.php ENDPATH**/ ?>