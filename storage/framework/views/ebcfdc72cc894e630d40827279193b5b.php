

<?php ($isEdit = $penduduk->exists); ?>
<?php $__env->startSection('title', $isEdit ? 'Edit Penduduk' : 'Tambah Penduduk'); ?>
<?php $__env->startSection('subtitle', $isEdit ? 'Perbarui data warga' : 'Tambahkan data warga baru'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('partials.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-body p-4">
        <form action="<?php echo e($isEdit ? route('admin.penduduk.update', $penduduk) : route('admin.penduduk.store')); ?>" method="POST" class="row g-3">
            <?php echo csrf_field(); ?>
            <?php if($isEdit): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">NIK <span class="text-danger">*</span></label>
                <input type="text" name="nik" value="<?php echo e(old('nik', $penduduk->nik)); ?>" maxlength="16" class="form-control <?php $__errorArgs = ['nik'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
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
                <label class="form-label small fw-semibold">No. KK</label>
                <input type="text" name="no_kk" value="<?php echo e(old('no_kk', $penduduk->no_kk)); ?>" maxlength="16" class="form-control <?php $__errorArgs = ['no_kk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php $__errorArgs = ['no_kk'];
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
                <input type="text" name="nama" value="<?php echo e(old('nama', $penduduk->nama)); ?>" class="form-control <?php $__errorArgs = ['nama'];
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

            <div class="col-md-3">
                <label class="form-label small fw-semibold">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" value="<?php echo e(old('tempat_lahir', $penduduk->tempat_lahir)); ?>" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="<?php echo e(old('tanggal_lahir', $penduduk->tanggal_lahir?->format('Y-m-d'))); ?>" max="<?php echo e(now()->format('Y-m-d')); ?>" class="form-control">
                <div class="small fw-semibold mt-1" id="umurOtomatis" style="color: var(--biru)"></div>
                <div class="form-text"><i class="bi bi-key me-1"></i>Dipakai sebagai kata sandi awal warga (format DDMMYYYY) untuk masuk dengan NIK.</div>
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
                    <option value="L" <?php if(old('jenis_kelamin', $penduduk->jenis_kelamin) === 'L'): echo 'selected'; endif; ?>>Laki-laki</option>
                    <option value="P" <?php if(old('jenis_kelamin', $penduduk->jenis_kelamin) === 'P'): echo 'selected'; endif; ?>>Perempuan</option>
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
                <input type="text" name="agama" value="<?php echo e(old('agama', $penduduk->agama)); ?>" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Pekerjaan</label>
                <input type="text" name="pekerjaan" value="<?php echo e(old('pekerjaan', $penduduk->pekerjaan)); ?>" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-semibold">RT <span class="text-danger">*</span></label>
                <input type="text" name="rt" value="<?php echo e(old('rt', $penduduk->rt)); ?>" class="form-control <?php $__errorArgs = ['rt'];
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
                <input type="text" name="rw" value="<?php echo e(old('rw', $penduduk->rw)); ?>" class="form-control <?php $__errorArgs = ['rw'];
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
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Dusun</label>
                <input type="text" name="dusun" value="<?php echo e(old('dusun', $penduduk->dusun)); ?>" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Status Keluarga <span class="text-danger">*</span></label>
                <select name="status" class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <option value="Kepala Keluarga" <?php if(old('status', $penduduk->status) === 'Kepala Keluarga'): echo 'selected'; endif; ?>>Kepala Keluarga</option>
                    <option value="Anggota" <?php if(old('status', $penduduk->status) === 'Anggota'): echo 'selected'; endif; ?>>Anggota</option>
                </select>
                <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-12">
                <label class="form-label small fw-semibold">Alamat</label>
                <textarea name="alamat" rows="2" class="form-control"><?php echo e(old('alamat', $penduduk->alamat)); ?></textarea>
            </div>

            <div class="col-12 d-flex gap-2 mt-2">
                <button class="btn btn-hijau px-4"><i class="bi bi-save me-1"></i> Simpan</button>
                <a href="<?php echo e(route('admin.penduduk.index')); ?>" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Pratinjau umur otomatis dari tanggal lahir (umur di sistem selalu dihitung ulang sendiri setiap hari)
    (function () {
        var input = document.getElementById('tanggal_lahir'), out = document.getElementById('umurOtomatis');
        function hitung() {
            if (!input.value) { out.textContent = ''; return; }
            var lahir = new Date(input.value + 'T00:00:00'), kini = new Date();
            var th = kini.getFullYear() - lahir.getFullYear();
            if (kini.getMonth() < lahir.getMonth() || (kini.getMonth() === lahir.getMonth() && kini.getDate() < lahir.getDate())) th--;
            if (th < 0) { out.textContent = 'Tanggal lahir tidak boleh di masa depan'; return; }
            var teks = th + ' tahun';
            if (th === 0) { var bl = (kini.getFullYear() - lahir.getFullYear()) * 12 + kini.getMonth() - lahir.getMonth() - (kini.getDate() < lahir.getDate() ? 1 : 0); teks = Math.max(0, bl) + ' bulan'; }
            var kel = th < 5 ? 'Balita' : th < 12 ? 'Anak' : th < 18 ? 'Remaja' : th < 60 ? 'Dewasa' : 'Lansia';
            out.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Umur: ' + teks + ' (' + kel + ')';
        }
        input.addEventListener('input', hitung); input.addEventListener('change', hitung); hitung();
    })();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views/admin/penduduk/form.blade.php ENDPATH**/ ?>