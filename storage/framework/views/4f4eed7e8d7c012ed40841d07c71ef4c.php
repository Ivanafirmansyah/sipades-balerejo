<?php $__env->startSection('title', 'Jenis Surat'); ?>
<?php $__env->startSection('subtitle', 'Kelola jenis surat yang tersedia'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('partials.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">Tambah Jenis Surat</h6>
                <form action="<?php echo e(route('admin.jenis.store')); ?>" method="POST" class="d-grid gap-3">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="form-label small fw-semibold">Nama Surat <span class="text-danger">*</span></label>
                        <input type="text" name="nama" value="<?php echo e(old('nama')); ?>" class="form-control <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Contoh: Surat Keterangan Domisili" required>
                        <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label class="form-label small fw-semibold">Kode</label>
                        <input type="text" name="kode" value="<?php echo e(old('kode')); ?>" class="form-control" placeholder="Contoh: SKD">
                    </div>
                    <div>
                        <label class="form-label small fw-semibold">Keterangan</label>
                        <textarea name="keterangan" rows="2" class="form-control" placeholder="Penjelasan singkat"><?php echo e(old('keterangan')); ?></textarea>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_aktif" id="is_aktif" class="form-check-input" value="1" checked>
                        <label class="form-check-label small" for="is_aktif">Aktif (tampil pada pengajuan)</label>
                    </div>
                    <button class="btn btn-hijau"><i class="bi bi-plus-lg me-1"></i> Tambah</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">Daftar Jenis Surat</h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr><th>Nama</th><th>Kode</th><th>Dipakai</th><th>Status</th><th class="text-end">Aksi</th></tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $jenisSurats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jenis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?php echo e($jenis->nama); ?></div>
                                        <?php if($jenis->keterangan): ?><div class="small text-muted"><?php echo e($jenis->keterangan); ?></div><?php endif; ?>
                                    </td>
                                    <td class="small"><?php echo e($jenis->kode ?? '-'); ?></td>
                                    <td class="small"><?php echo e($jenis->surats_count); ?></td>
                                    <td>
                                        <span class="badge badge-soft-<?php echo e($jenis->is_aktif ? 'success' : 'secondary'); ?>">
                                            <?php echo e($jenis->is_aktif ? 'Aktif' : 'Nonaktif'); ?>

                                        </span>
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editJenis<?php echo e($jenis->id); ?>"><i class="bi bi-pencil"></i></button>
                                        <form action="<?php echo e(route('admin.jenis.destroy', $jenis)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus jenis surat <?php echo e($jenis->nama); ?>?')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada jenis surat.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__currentLoopData = $jenisSurats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jenis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="editJenis<?php echo e($jenis->id); ?>" tabindex="-1">
        <div class="modal-dialog">
            <form action="<?php echo e(route('admin.jenis.update', $jenis)); ?>" method="POST" class="modal-content">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Edit Jenis Surat</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body d-grid gap-3">
                    <div>
                        <label class="form-label small fw-semibold">Nama Surat</label>
                        <input type="text" name="nama" value="<?php echo e($jenis->nama); ?>" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label small fw-semibold">Kode</label>
                        <input type="text" name="kode" value="<?php echo e($jenis->kode); ?>" class="form-control">
                    </div>
                    <div>
                        <label class="form-label small fw-semibold">Keterangan</label>
                        <textarea name="keterangan" rows="2" class="form-control"><?php echo e($jenis->keterangan); ?></textarea>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_aktif" id="aktif<?php echo e($jenis->id); ?>" class="form-check-input" value="1" <?php if($jenis->is_aktif): echo 'checked'; endif; ?>>
                        <label class="form-check-label small" for="aktif<?php echo e($jenis->id); ?>">Aktif</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-hijau btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views\admin\surat\jenis.blade.php ENDPATH**/ ?>