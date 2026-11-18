<?php $__env->startSection('title', 'Isian: ' . $formulir->judul); ?>
<?php $__env->startSection('subtitle', 'Data yang dikirim warga melalui formulir dinamis'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('partials.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php ($fields = $formulir->fields()); ?>
<?php ($kolom = array_slice($fields, 0, 3)); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100"><div class="card-body d-flex align-items-center gap-3">
            <div class="icon"><i class="bi bi-inbox-fill"></i></div>
            <div><div class="fs-4 fw-bold lh-1 mb-1"><?php echo e($stats['total']); ?></div><div class="small text-muted">Total Isian</div></div>
        </div></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100"><div class="card-body d-flex align-items-center gap-3">
            <div class="icon"><i class="bi bi-bell-fill"></i></div>
            <div><div class="fs-4 fw-bold lh-1 mb-1"><?php echo e($stats['baru']); ?></div><div class="small text-muted">Belum Ditinjau</div></div>
        </div></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100"><div class="card-body d-flex align-items-center gap-3">
            <div class="icon"><i class="bi bi-wifi-off"></i></div>
            <div><div class="fs-4 fw-bold lh-1 mb-1"><?php echo e($stats['offline']); ?></div><div class="small text-muted">Diisi Saat Offline</div></div>
        </div></div>
    </div>
    <div class="col-6 col-lg-3">
        <a href="<?php echo e(route('admin.formulir.jawaban.csv', $formulir)); ?>" class="export-card">
            <span class="export-icon" style="background:#dcfce7;color:#15803d"><i class="bi bi-filetype-csv"></i></span>
            <span class="flex-grow-1 lh-sm"><span class="d-block fw-bold">Unduh Excel/CSV</span><span class="d-block small export-sub">Semua isian</span></span>
            <i class="bi bi-download export-dl"></i>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div class="d-flex gap-2 flex-wrap">
                <?php $__currentLoopData = ['' => 'Semua', 'baru' => 'Baru', 'diproses' => 'Diproses', 'selesai' => 'Selesai']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('admin.formulir.jawaban', [$formulir, 'status' => $k ?: null])); ?>" class="btn btn-sm <?php echo e(request('status', '') === $k ? 'btn-biru' : 'btn-outline-secondary'); ?> rounded-pill"><?php echo e($v); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="d-flex gap-2">
                <a href="<?php echo e(route('formulir.show', $formulir->slug)); ?>" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-box-arrow-up-right me-1"></i> Buka Formulir</a>
                <a href="<?php echo e(route('admin.formulir.edit', $formulir)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-tools me-1"></i> Ubah Isian</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th><th>Masuk</th><th>Pengirim</th>
                        <?php $__currentLoopData = $kolom; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><th><?php echo e(\Illuminate\Support\Str::limit($f['label'], 22)); ?></th><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <th>Status</th><th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $jawabans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($j->id); ?></td>
                            <td class="small text-nowrap">
                                <?php echo e($j->created_at->format('d/m/Y H:i')); ?>

                                <?php if($j->dikirim_offline): ?>
                                    <div><span class="badge badge-soft-warning" title="Diisi <?php echo e($j->diisi_pada?->format('d/m/Y H:i')); ?>"><i class="bi bi-wifi-off"></i> Offline</span></div>
                                <?php endif; ?>
                            </td>
                            <td class="small"><?php echo e($j->user->name ?? 'Tamu'); ?></td>
                            <?php $__currentLoopData = $kolom; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td class="small"><?php echo e(\Illuminate\Support\Str::limit(\App\Support\FormSchema::display($f, $j->data[$f['name']] ?? null), 30)); ?></td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <td>
                                <?php ($b = ['baru' => 'warning', 'diproses' => 'info', 'selesai' => 'success'][$j->status] ?? 'secondary'); ?>
                                <span class="badge badge-soft-<?php echo e($b); ?>"><?php echo e($j->statusLabel()); ?></span>
                                <?php if($j->surat): ?><div class="small mt-1"><a href="<?php echo e(route('admin.surat.index')); ?>">Surat #<?php echo e($j->surat_id); ?></a></div><?php endif; ?>
                            </td>
                            <td class="text-end text-nowrap">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#isian<?php echo e($j->id); ?>" title="Lihat"><i class="bi bi-eye"></i></button>
                                <form action="<?php echo e(route('admin.formulir.jawaban.destroy', [$formulir, $j])); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus isian ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="<?php echo e(5 + count($kolom)); ?>" class="text-center text-muted py-5"><i class="bi bi-inbox fs-2 d-block mb-2"></i>Belum ada isian masuk.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo e($jawabans->links()); ?>

    </div>
</div>

<?php $__currentLoopData = $jawabans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="isian<?php echo e($j->id); ?>" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <form action="<?php echo e(route('admin.formulir.jawaban.update', [$formulir, $j])); ?>" method="POST" class="modal-content">
                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Isian #<?php echo e($j->id); ?> — <?php echo e($j->user->name ?? 'Tamu'); ?></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="small text-muted mb-3">
                        Masuk <?php echo e($j->created_at->translatedFormat('d F Y H:i')); ?>

                        <?php if($j->dikirim_offline): ?> · <i class="bi bi-wifi-off"></i> diisi offline pada <?php echo e($j->diisi_pada?->translatedFormat('d F Y H:i')); ?><?php endif; ?>
                    </div>
                    <dl class="row isian-detail mb-0">
                        <?php $__currentLoopData = $formulir->schema['fields'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($f['type'] === 'section'): ?>
                                <dt class="col-12 form-section-title mt-2"><?php echo e($f['label']); ?></dt>
                            <?php else: ?>
                                <dt class="col-sm-5"><?php echo e($f['label']); ?></dt>
                                <dd class="col-sm-7"><?php echo e(\App\Support\FormSchema::display($f, $j->data[$f['name']] ?? null)); ?></dd>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </dl>
                </div>
                <div class="modal-footer justify-content-between">
                    <select name="status" class="form-select form-select-sm w-auto">
                        <?php $__currentLoopData = ['baru' => 'Baru', 'diproses' => 'Diproses', 'selesai' => 'Selesai']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($k); ?>" <?php if($j->status === $k): echo 'selected'; endif; ?>><?php echo e($v); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button class="btn btn-biru btn-sm"><i class="bi bi-check2 me-1"></i> Simpan Status</button>
                </div>
            </form>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views/admin/formulir/jawaban.blade.php ENDPATH**/ ?>