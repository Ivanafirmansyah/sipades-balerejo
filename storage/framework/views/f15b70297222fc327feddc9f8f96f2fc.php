<?php ($isEdit = $agenda->exists); ?>
<?php $__env->startSection('title', $isEdit ? 'Edit Agenda' : 'Tambah Agenda'); ?>
<?php $__env->startSection('subtitle', $isEdit ? 'Perbarui agenda kegiatan' : 'Buat agenda kegiatan baru'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('partials.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-body p-4">
        <form action="<?php echo e($isEdit ? route('admin.agenda.update', $agenda) : route('admin.agenda.store')); ?>" method="POST" enctype="multipart/form-data" class="row g-3">
            <?php echo csrf_field(); ?>
            <?php if($isEdit): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Judul Kegiatan <span class="text-danger">*</span></label>
                <input type="text" name="judul" value="<?php echo e(old('judul', $agenda->judul)); ?>" class="form-control <?php $__errorArgs = ['judul'];
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
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Tanggal <span class="text-danger">*</span></label>
                <input type="date" name="tanggal" value="<?php echo e(old('tanggal', $agenda->tanggal?->format('Y-m-d'))); ?>" class="form-control <?php $__errorArgs = ['tanggal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                <?php $__errorArgs = ['tanggal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Jam</label>
                <input type="time" name="jam" value="<?php echo e(old('jam', $agenda->jam_formatted)); ?>" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Lokasi</label>
                <input type="text" name="lokasi" value="<?php echo e(old('lokasi', $agenda->lokasi)); ?>" class="form-control" placeholder="Contoh: Balai Desa">
            </div>

            <div class="col-12">
                <label class="form-label small fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" rows="4" class="form-control"><?php echo e(old('deskripsi', $agenda->deskripsi)); ?></textarea>
            </div>

            
            <div class="col-12">
                <label class="form-label small fw-semibold">Foto Kegiatan <span class="text-muted fw-normal">(opsional, maks 10MB — otomatis dikompres)</span></label>
                <label class="foto-drop <?php $__errorArgs = ['foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" for="fotoInput" id="fotoDrop">
                    <img id="fotoPreview" src="<?php echo e($agenda->foto_url); ?>" alt="Pratinjau foto" <?php if (! ($agenda->foto)): ?> hidden <?php endif; ?>>
                    <span class="foto-drop-kosong" id="fotoKosong" <?php if($agenda->foto): ?> hidden <?php endif; ?>>
                        <i class="bi bi-image"></i>
                        <b>Klik untuk memilih foto</b>
                        <small>atau seret foto ke sini · JPG, PNG, WebP</small>
                    </span>
                </label>
                <input type="file" name="foto" id="fotoInput" accept="image/*" class="d-none">
                <?php $__errorArgs = ['foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <div class="d-flex flex-wrap gap-3 align-items-center mt-2">
                    <span class="small text-muted" id="fotoInfo"><?php echo e($agenda->foto ? 'Foto saat ini. Pilih foto lain untuk mengganti.' : ''); ?></span>
                    <?php if($agenda->foto): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="hapus_foto" value="1" id="hapusFoto">
                            <label class="form-check-label small text-danger" for="hapusFoto">Hapus foto</label>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-12 d-flex gap-2 mt-2">
                <button class="btn btn-hijau px-4"><i class="bi bi-save me-1"></i> Simpan</button>
                <a href="<?php echo e(route('admin.agenda.index')); ?>" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Pratinjau foto sebelum diunggah + seret-lepas
    (function () {
        var input = document.getElementById('fotoInput'), drop = document.getElementById('fotoDrop');
        var img = document.getElementById('fotoPreview'), kosong = document.getElementById('fotoKosong'), info = document.getElementById('fotoInfo');
        var hapus = document.getElementById('hapusFoto');
        function tampil(file) {
            if (!file || !file.type.startsWith('image/')) return;
            img.src = URL.createObjectURL(file); img.hidden = false; kosong.hidden = true;
            info.textContent = file.name + ' · ' + (file.size / 1048576).toFixed(1) + ' MB (akan dikompres saat disimpan)';
            if (hapus) hapus.checked = false;
        }
        input.addEventListener('change', function () { tampil(input.files[0]); });
        ['dragover', 'dragenter'].forEach(function (ev) { drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.add('aktif'); }); });
        ['dragleave', 'drop'].forEach(function (ev) { drop.addEventListener(ev, function () { drop.classList.remove('aktif'); }); });
        drop.addEventListener('drop', function (e) {
            e.preventDefault();
            if (e.dataTransfer.files.length) { input.files = e.dataTransfer.files; tampil(input.files[0]); }
        });
        if (hapus) hapus.addEventListener('change', function () { img.style.opacity = hapus.checked ? .3 : 1; });
    })();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sipades\resources\views/admin/agenda/form.blade.php ENDPATH**/ ?>