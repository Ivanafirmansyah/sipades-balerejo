<?php $__env->startSection('title', 'Profil & Peta Desa'); ?>
<?php $__env->startSection('subtitle', 'Kelola identitas desa, kontak, dan peta lokasi'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('partials.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-body p-4">
        <form action="<?php echo e(route('admin.profil.update')); ?>" method="POST" enctype="multipart/form-data" class="row g-3">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="col-12"><h6 class="fw-bold text-success">Identitas Desa</h6><hr class="mt-1"></div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Nama Desa <span class="text-danger">*</span></label>
                <input type="text" name="nama_desa" value="<?php echo e(old('nama_desa', $profil->nama_desa)); ?>" class="form-control <?php $__errorArgs = ['nama_desa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                <?php $__errorArgs = ['nama_desa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Logo Desa</label>
                <div class="form-text mt-0 mb-1">Maks 10MB — otomatis dikompres</div>
                <input type="file" name="logo" class="form-control <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept="image/*">
                <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <?php if($profil->logo): ?>
                    <img src="<?php echo e(asset('storage/' . $profil->logo)); ?>" class="mt-2 bg-light rounded p-1" height="64" alt="Logo">
                <?php endif; ?>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Kecamatan</label>
                <input type="text" name="kecamatan" value="<?php echo e(old('kecamatan', $profil->kecamatan)); ?>" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Kabupaten</label>
                <input type="text" name="kabupaten" value="<?php echo e(old('kabupaten', $profil->kabupaten)); ?>" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Provinsi</label>
                <input type="text" name="provinsi" value="<?php echo e(old('provinsi', $profil->provinsi)); ?>" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Kode Pos</label>
                <input type="text" name="kode_pos" value="<?php echo e(old('kode_pos', $profil->kode_pos)); ?>" class="form-control">
            </div>

            <div class="col-12">
                <label class="form-label small fw-semibold">Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3" class="form-control"><?php echo e(old('deskripsi', $profil->deskripsi)); ?></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Sejarah Desa</label>
                <textarea name="sejarah" rows="5" class="form-control"><?php echo e(old('sejarah', $profil->sejarah)); ?></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Misi</label>
                <textarea name="misi" rows="5" class="form-control"><?php echo e(old('misi', $profil->misi)); ?></textarea>
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Visi</label>
                <textarea name="visi" rows="2" class="form-control"><?php echo e(old('visi', $profil->visi)); ?></textarea>
            </div>

            <div class="col-12 mt-4"><h6 class="fw-bold text-success">Kontak & Peta</h6><hr class="mt-1"></div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Alamat Kantor</label>
                <input type="text" name="alamat_kantor" value="<?php echo e(old('alamat_kantor', $profil->alamat_kantor)); ?>" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Telepon</label>
                <input type="text" name="telepon" value="<?php echo e(old('telepon', $profil->telepon)); ?>" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Email</label>
                <input type="email" name="email" value="<?php echo e(old('email', $profil->email)); ?>" class="form-control">
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Embed Peta (Google Maps iframe)</label>
                <textarea name="peta_embed" rows="4" class="form-control font-monospace" style="font-size: .8rem;" placeholder='<iframe src="https://www.google.com/maps/embed?..." ...></iframe>'><?php echo e(old('peta_embed', $profil->peta_embed)); ?></textarea>
                <div class="form-text">Buka Google Maps &rarr; Bagikan &rarr; Sematkan peta &rarr; salin kode iframe dan tempel di sini.</div>
            </div>

            <div class="col-12 d-flex gap-2 mt-3">
                <button class="btn btn-hijau px-4"><i class="bi bi-save me-1"></i> Simpan Profil</button>
                <a href="<?php echo e(route('profil')); ?>" target="_blank" class="btn btn-outline-secondary">Lihat Halaman Publik</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sipades\resources\views/admin/profil/edit.blade.php ENDPATH**/ ?>