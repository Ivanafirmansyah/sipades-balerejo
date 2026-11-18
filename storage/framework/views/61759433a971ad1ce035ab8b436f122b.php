<?php ($isEdit = $perangkat->exists); ?>
<?php $__env->startSection('title', $isEdit ? 'Edit Perangkat' : 'Tambah Perangkat'); ?>
<?php $__env->startSection('subtitle', $isEdit ? 'Perbarui data perangkat desa' : 'Tambahkan perangkat desa'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('partials.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-body p-4">
        <form action="<?php echo e($isEdit ? route('admin.perangkat.update', $perangkat) : route('admin.perangkat.store')); ?>" method="POST" enctype="multipart/form-data" class="row g-3">
            <?php echo csrf_field(); ?>
            <?php if($isEdit): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Nama <span class="text-danger">*</span></label>
                <input type="text" name="nama" value="<?php echo e(old('nama', $perangkat->nama)); ?>" class="form-control <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Jabatan <span class="text-danger">*</span></label>
                <input type="text" name="jabatan" value="<?php echo e(old('jabatan', $perangkat->jabatan)); ?>" class="form-control <?php $__errorArgs = ['jabatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Contoh: Kepala Desa" required>
                <?php $__errorArgs = ['jabatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Atasan Langsung</label>
                <?php ($pilih = old('atasan', $perangkat->exists ? ($perangkat->atasan_id ?: 'akar') : 'otomatis')); ?>
                <select name="atasan" class="form-select <?php $__errorArgs = ['atasan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="pilihAtasan">
                    <option value="otomatis" <?php if($pilih === 'otomatis'): echo 'selected'; endif; ?>>Otomatis sesuai jabatan</option>
                    <option value="akar" <?php if($pilih === 'akar'): echo 'selected'; endif; ?>>— Tidak ada (puncak struktur / Kepala Desa)</option>
                    <?php $__currentLoopData = $pilihanAtasan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($a->id); ?>" <?php if((string) $pilih === (string) $a->id): echo 'selected'; endif; ?>><?php echo e($a->jabatan); ?> — <?php echo e($a->nama); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['atasan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <div class="form-text">Otomatis: Sekretaris & Kasi/Kadus di bawah Kepala Desa, Kaur di bawah Sekretaris Desa.</div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Urutan</label>
                <input type="number" name="urutan" min="0" max="999" value="<?php echo e(old('urutan', $perangkat->exists ? $perangkat->urutan : '')); ?>" class="form-control" placeholder="Otomatis">
                <div class="form-text">Kecil = lebih kiri.</div>
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <div class="form-check form-switch mt-3">
                    <input class="form-check-input" type="checkbox" name="is_mitra" value="1" id="isMitra" <?php if(old('is_mitra', $perangkat->is_mitra)): echo 'checked'; endif; ?>>
                    <label class="form-check-label small fw-semibold" for="isMitra">Lembaga mitra <span class="text-muted fw-normal d-block">(mis. BPD, LPMD)</span></label>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Foto <span class="text-muted fw-normal">(maks 10MB — otomatis dikompres)</span></label>
                <input type="file" name="foto" class="form-control <?php $__errorArgs = ['foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept="image/*">
                <?php $__errorArgs = ['foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <?php if($perangkat->foto): ?>
                    <img src="<?php echo e(asset('storage/' . $perangkat->foto)); ?>" class="rounded-circle mt-2" width="80" height="80" style="object-fit: cover;" alt="">
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Keterangan</label>
                <input type="text" name="keterangan" value="<?php echo e(old('keterangan', $perangkat->keterangan)); ?>" class="form-control" placeholder="Opsional">
            </div>

            <div class="col-12 d-flex gap-2 mt-2">
                <button class="btn btn-hijau px-4"><i class="bi bi-save me-1"></i> Simpan</button>
                <a href="<?php echo e(route('admin.perangkat.index')); ?>" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views/admin/perangkat/form.blade.php ENDPATH**/ ?>