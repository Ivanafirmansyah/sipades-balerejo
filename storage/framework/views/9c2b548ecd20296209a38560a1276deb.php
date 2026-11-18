<?php $__env->startSection('title', 'Pengajuan Surat'); ?>
<?php $__env->startSection('subtitle', 'Isi formulir pengajuan surat keterangan'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('partials.sidebar-user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-1">Formulir Pengajuan Surat</h6>
        <p class="text-muted small mb-4">Pastikan data yang Anda isi sesuai dengan KTP. Pengajuan akan diproses oleh perangkat desa.</p>

        <form action="<?php echo e(route('user.surat.store')); ?>" method="POST" class="row g-3">
            <?php echo csrf_field(); ?>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Jenis Surat <span class="text-danger">*</span></label>
                <select name="jenis_surat_id" class="form-select <?php $__errorArgs = ['jenis_surat_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <option value="">Pilih jenis surat</option>
                    <?php $__currentLoopData = $jenisSurats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jenis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($jenis->id); ?>" <?php if(old('jenis_surat_id') == $jenis->id): echo 'selected'; endif; ?>><?php echo e($jenis->nama); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['jenis_surat_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Keperluan <span class="text-danger">*</span></label>
                <input type="text" name="keperluan" value="<?php echo e(old('keperluan')); ?>" class="form-control <?php $__errorArgs = ['keperluan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Contoh: keperluan administrasi bank" required>
                <?php $__errorArgs = ['keperluan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-12"><hr class="my-1"><div class="fw-semibold small text-muted">DATA PEMOHON</div></div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">NIK <span class="text-danger">*</span></label>
                <input type="text" name="nik" value="<?php echo e(old('nik', $penduduk->nik ?? '')); ?>" maxlength="16" class="form-control <?php $__errorArgs = ['nik'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="16 digit NIK" required>
                <?php $__errorArgs = ['nik'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama" value="<?php echo e(old('nama', $penduduk->nama ?? auth()->user()->name)); ?>" class="form-control <?php $__errorArgs = ['nama'];
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
                <label class="form-label small fw-semibold">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" value="<?php echo e(old('tempat_lahir', $penduduk->tempat_lahir ?? '')); ?>" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" value="<?php echo e(old('tanggal_lahir', isset($penduduk->tanggal_lahir) ? $penduduk->tanggal_lahir->format('Y-m-d') : '')); ?>" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                <select name="jenis_kelamin" class="form-select <?php $__errorArgs = ['jenis_kelamin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <option value="L" <?php if(old('jenis_kelamin', $penduduk->jenis_kelamin ?? '') === 'L'): echo 'selected'; endif; ?>>Laki-laki</option>
                    <option value="P" <?php if(old('jenis_kelamin', $penduduk->jenis_kelamin ?? '') === 'P'): echo 'selected'; endif; ?>>Perempuan</option>
                </select>
                <?php $__errorArgs = ['jenis_kelamin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-semibold">Agama</label>
                <input type="text" name="agama" value="<?php echo e(old('agama', $penduduk->agama ?? '')); ?>" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-semibold">Pekerjaan</label>
                <input type="text" name="pekerjaan" value="<?php echo e(old('pekerjaan', $penduduk->pekerjaan ?? '')); ?>" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-semibold">RT <span class="text-danger">*</span></label>
                <input type="text" name="rt" value="<?php echo e(old('rt', $penduduk->rt ?? '')); ?>" class="form-control <?php $__errorArgs = ['rt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                <?php $__errorArgs = ['rt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-semibold">RW <span class="text-danger">*</span></label>
                <input type="text" name="rw" value="<?php echo e(old('rw', $penduduk->rw ?? '')); ?>" class="form-control <?php $__errorArgs = ['rw'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                <?php $__errorArgs = ['rw'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Dusun</label>
                <input type="text" name="dusun" value="<?php echo e(old('dusun', $penduduk->dusun ?? '')); ?>" class="form-control">
            </div>

            <div class="col-12">
                <label class="form-label small fw-semibold">Alamat</label>
                <textarea name="alamat" rows="2" class="form-control"><?php echo e(old('alamat', $penduduk->alamat ?? '')); ?></textarea>
            </div>

            <div class="col-12 d-flex gap-2 mt-2">
                <button class="btn btn-hijau px-4"><i class="bi bi-send me-1"></i> Kirim Pengajuan</button>
                <a href="<?php echo e(route('user.surat.index')); ?>" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views/user/surat/create.blade.php ENDPATH**/ ?>