<?php ($isEdit = $berita->exists); ?>
<?php $__env->startSection('title', $isEdit ? 'Edit Berita' : 'Tambah Berita'); ?>
<?php $__env->startSection('subtitle', $isEdit ? 'Perbarui berita / pengumuman' : 'Tulis berita atau pengumuman baru'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('partials.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-body p-4">
        <form action="<?php echo e($isEdit ? route('admin.berita.update', $berita) : route('admin.berita.store')); ?>" method="POST" enctype="multipart/form-data" class="row g-3">
            <?php echo csrf_field(); ?>
            <?php if($isEdit): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

            <div class="col-md-8">
                <label class="form-label small fw-semibold">Judul <span class="text-danger">*</span></label>
                <input type="text" name="judul" value="<?php echo e(old('judul', $berita->judul)); ?>" class="form-control <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-semibold">Kategori <span class="text-danger">*</span></label>
                <select name="kategori" class="form-select <?php $__errorArgs = ['kategori'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <option value="berita" <?php if(old('kategori', $berita->kategori) === 'berita'): echo 'selected'; endif; ?>>Berita</option>
                    <option value="pengumuman" <?php if(old('kategori', $berita->kategori) === 'pengumuman'): echo 'selected'; endif; ?>>Pengumuman</option>
                </select>
                <?php $__errorArgs = ['kategori'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-12">
                <label class="form-label small fw-semibold">Isi Berita <span class="text-danger">*</span></label>
                <textarea name="isi" rows="10" class="form-control <?php $__errorArgs = ['isi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Tulis isi berita..." required><?php echo e(old('isi', $berita->isi)); ?></textarea>
                <?php $__errorArgs = ['isi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Gambar <span class="text-muted fw-normal">(opsional, maks 10MB — otomatis dikompres)</span></label>
                <input type="file" name="gambar" class="form-control <?php $__errorArgs = ['gambar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept="image/*">
                <?php $__errorArgs = ['gambar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <?php if($berita->gambar): ?>
                    <div class="mt-2 d-flex align-items-center gap-2">
                        <img src="<?php echo e(asset('storage/' . $berita->gambar)); ?>" class="rounded" width="120" height="80" style="object-fit: cover;" alt="">
                        <span class="small text-muted">Gambar saat ini</span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-6 d-flex align-items-center">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1" <?php if(old('is_published', $berita->is_published ?? true)): echo 'checked'; endif; ?>>
                    <label class="form-check-label small fw-semibold" for="is_published">Terbitkan sekarang</label>
                </div>
            </div>

            <div class="col-12 d-flex gap-2 mt-2">
                <button class="btn btn-hijau px-4"><i class="bi bi-save me-1"></i> Simpan</button>
                <a href="<?php echo e(route('admin.berita.index')); ?>" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views\admin\berita\form.blade.php ENDPATH**/ ?>