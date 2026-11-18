<?php $__env->startSection('title', 'Surat Menyurat'); ?>
<?php $__env->startSection('subtitle', 'Kelola pengajuan surat warga'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('partials.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3 mb-4">
    <?php $__currentLoopData = ['menunggu' => ['Menunggu', 'warning'], 'diproses' => ['Diproses', 'info'], 'selesai' => ['Selesai', 'success'], 'ditolak' => ['Ditolak', 'danger']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-6 col-lg-3">
            <a href="<?php echo e(route('admin.surat.index', ['status' => $key])); ?>" class="text-decoration-none">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="small text-muted"><?php echo e($item[0]); ?></div>
                        <div class="fs-4 fw-bold text-dark"><?php echo e($stats[$key]); ?></div>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="card">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h6 class="fw-bold mb-0">Daftar Pengajuan</h6>
            <div class="d-flex gap-2">
                <a href="<?php echo e(route('admin.surat.export')); ?>" class="btn btn-sm btn-outline-danger"><i class="bi bi-file-earmark-pdf me-1"></i> Export PDF</a>
                <a href="<?php echo e(route('admin.jenis.index')); ?>" class="btn btn-sm btn-outline-success"><i class="bi bi-tags me-1"></i> Jenis Surat</a>
            </div>
        </div>

        <form action="<?php echo e(route('admin.surat.index')); ?>" method="GET" class="row g-2 mb-3">
            <div class="col-md-5">
                <input type="text" name="q" value="<?php echo e(request('q')); ?>" class="form-control form-control-sm" placeholder="Cari no. surat / nama / NIK / jenis surat">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <?php $__currentLoopData = ['menunggu' => 'Menunggu', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php if(request('status') === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-sm btn-hijau"><i class="bi bi-search me-1"></i> Cari</button>
                <a href="<?php echo e(route('admin.surat.index')); ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Pemohon</th>
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
                            <td>
                                <div class="fw-semibold"><?php echo e($surat->penduduk->nama ?? '-'); ?></div>
                                <div class="small text-muted font-monospace"><?php echo e($surat->penduduk->nik ?? ''); ?></div>
                            </td>
                            <td class="small"><?php echo e($surat->jenisSurat->nama ?? '-'); ?></td>
                            <td class="small text-muted"><?php echo e(\Illuminate\Support\Str::limit($surat->keperluan, 30)); ?></td>
                            <td class="small"><?php echo e($surat->no_surat ?? '-'); ?></td>
                            <td class="small"><?php echo e($surat->created_at->format('d/m/Y')); ?></td>
                            <td>
                                <?php ($badge = ['menunggu' => 'warning', 'diproses' => 'info', 'selesai' => 'success', 'ditolak' => 'danger'][$surat->status]); ?>
                                <span class="badge badge-soft-<?php echo e($badge); ?>"><?php echo e($surat->statusLabel()); ?></span>
                            </td>
                            <td class="text-end text-nowrap">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalSurat<?php echo e($surat->id); ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <?php if($surat->status === 'selesai'): ?>
                                    <a href="<?php echo e(route('admin.surat.pdf', $surat)); ?>" class="btn btn-sm btn-outline-success" title="Unduh PDF"><i class="bi bi-file-earmark-pdf"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada pengajuan surat.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php echo e($surats->links()); ?>

    </div>
</div>

<?php $__currentLoopData = $surats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $surat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="modalSurat<?php echo e($surat->id); ?>" tabindex="-1">
        <div class="modal-dialog">
            <form action="<?php echo e(route('admin.surat.update', $surat)); ?>" method="POST" class="modal-content">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Proses Surat #<?php echo e($surat->id); ?></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 small">
                        <div><span class="text-muted">Pemohon:</span> <strong><?php echo e($surat->penduduk->nama ?? '-'); ?></strong></div>
                        <div><span class="text-muted">Jenis:</span> <?php echo e($surat->jenisSurat->nama ?? '-'); ?></div>
                        <div><span class="text-muted">Keperluan:</span> <?php echo e($surat->keperluan); ?></div>
                    </div>
                    <?php if(!empty($surat->data)): ?>
                        <div class="mb-3 p-3 rounded-3 small" style="background: var(--biru-pucat-2)">
                            <div class="fw-bold mb-2"><i class="bi bi-ui-checks me-1"></i> Isian Formulir Online</div>
                            <?php $__currentLoopData = $surat->data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="d-flex gap-2"><span class="text-muted" style="min-width: 40%"><?php echo e($row['label'] ?? ''); ?></span><span><?php echo e($row['nilai'] ?? '-'); ?></span></div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <?php $__currentLoopData = ['menunggu' => 'Menunggu', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>" <?php if($surat->status === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nomor Surat</label>
                        <input type="text" name="no_surat" value="<?php echo e($surat->no_surat); ?>" class="form-control" placeholder="Contoh: 470/001/DS/2026">
                    </div>
                    <div>
                        <label class="form-label small fw-semibold">Catatan Admin</label>
                        <textarea name="catatan_admin" rows="2" class="form-control" placeholder="Catatan untuk pemohon (opsional)"><?php echo e($surat->catatan_admin); ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-hijau btn-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sipades\resources\views/admin/surat/index.blade.php ENDPATH**/ ?>