

<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('subtitle', 'Ringkasan layanan dan aktivitas Anda'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('partials.sidebar-user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon bg-success-subtle text-success"><i class="bi bi-file-earmark-text"></i></div>
                <div>
                    <div class="fs-4 fw-bold"><?php echo e($stats['total']); ?></div>
                    <div class="small text-muted">Total Pengajuan</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon bg-warning-subtle text-warning"><i class="bi bi-hourglass-split"></i></div>
                <div>
                    <div class="fs-4 fw-bold"><?php echo e($stats['menunggu']); ?></div>
                    <div class="small text-muted">Menunggu</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon bg-info-subtle text-info"><i class="bi bi-arrow-repeat"></i></div>
                <div>
                    <div class="fs-4 fw-bold"><?php echo e($stats['diproses']); ?></div>
                    <div class="small text-muted">Diproses</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon bg-primary-subtle text-primary"><i class="bi bi-check2-circle"></i></div>
                <div>
                    <div class="fs-4 fw-bold"><?php echo e($stats['selesai']); ?></div>
                    <div class="small text-muted">Selesai</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Pengajuan Terbaru</h6>
                    <a href="<?php echo e(route('user.surat.index')); ?>" class="btn btn-sm btn-outline-success">Lihat semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Jenis Surat</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $surats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $surat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($surat->id); ?></td>
                                    <td><?php echo e($surat->jenisSurat->nama ?? '-'); ?></td>
                                    <td><?php echo e($surat->created_at->format('d/m/Y')); ?></td>
                                    <td>
                                        <?php ($badge = ['menunggu' => 'warning', 'diproses' => 'info', 'selesai' => 'success', 'ditolak' => 'danger'][$surat->status]); ?>
                                        <span class="badge badge-soft-<?php echo e($badge); ?>"><?php echo e($surat->statusLabel()); ?></span>
                                    </td>
                                    <td class="text-end">
                                        <?php if($surat->status === 'selesai'): ?>
                                            <a href="<?php echo e(route('user.surat.pdf', $surat)); ?>" class="btn btn-sm btn-outline-success"><i class="bi bi-download"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pengajuan surat.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">Aksi Cepat</h6>
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('user.surat.create')); ?>" class="btn btn-hijau"><i class="bi bi-file-earmark-plus me-1"></i> Ajukan Surat</a>
                    <a href="<?php echo e(route('user.pengaduan.create')); ?>" class="btn btn-outline-success"><i class="bi bi-megaphone me-1"></i> Buat Pengaduan</a>
                    <a href="<?php echo e(route('user.pengaduan.index')); ?>" class="btn btn-outline-secondary"><i class="bi bi-chat-left-text me-1"></i> Riwayat Pengaduan</a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-2"><i class="bi bi-person-circle me-1 text-success"></i> Profil Akun</h6>
                <div class="small text-muted">Nama</div>
                <div class="fw-semibold"><?php echo e(auth()->user()->name); ?></div>
                <?php if(auth()->user()->nik): ?>
                    <div class="small text-muted mt-2">NIK (untuk masuk)</div>
                    <div class="fw-semibold font-monospace"><?php echo e(auth()->user()->nik); ?></div>
                <?php endif; ?>
                <?php if(auth()->user()->email): ?>
                    <div class="small text-muted mt-2">Email</div>
                    <div class="fw-semibold"><?php echo e(auth()->user()->email); ?></div>
                <?php endif; ?>
                <div class="small text-muted mt-2">No. HP</div>
                <div class="fw-semibold"><?php echo e(auth()->user()->phone ?? '-'); ?></div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views\user\dashboard.blade.php ENDPATH**/ ?>