<?php $__env->startSection('title', 'Layanan Pengaduan'); ?>
<?php $__env->startSection('subtitle', 'Sampaikan keluhan, saran, atau laporan Anda'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('partials.sidebar-user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-1">Formulir Pengaduan</h6>
                <p class="text-muted small mb-4">Laporan Anda akan ditindaklanjuti oleh perangkat desa. Status dapat Anda pantau pada menu riwayat pengaduan.</p>

                <form action="<?php echo e(route('user.pengaduan.store')); ?>" method="POST" class="row g-3">
                    <?php echo csrf_field(); ?>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori" class="form-select <?php $__errorArgs = ['kategori'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">Pilih kategori</option>
                            <?php $__currentLoopData = ['Infrastruktur', 'Pelayanan Desa', 'Kebersihan & Lingkungan', 'Keamanan', 'Sosial & Bantuan', 'Lainnya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kategori): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($kategori); ?>" <?php if(old('kategori') === $kategori): echo 'selected'; endif; ?>><?php echo e($kategori); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Lokasi Kejadian</label>
                        <input type="text" name="lokasi" value="<?php echo e(old('lokasi')); ?>" class="form-control" placeholder="Contoh: RT 01 / RW 02">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Isi Pengaduan <span class="text-danger">*</span></label>
                        <textarea name="isi" rows="6" class="form-control <?php $__errorArgs = ['isi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Jelaskan kronologi dan detail permasalahan..." required><?php echo e(old('isi')); ?></textarea>
                        <?php $__errorArgs = ['isi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="col-12 d-flex gap-2">
                        <button class="btn btn-hijau px-4"><i class="bi bi-send me-1"></i> Kirim Pengaduan</button>
                        <a href="<?php echo e(route('user.pengaduan.index')); ?>" class="btn btn-outline-secondary">Riwayat</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-info-circle text-success me-1"></i> Ketentuan</h6>
                <ul class="small text-muted ps-3 d-grid gap-2 mb-0">
                    <li>Isi laporan dengan jelas dan sesuai fakta.</li>
                    <li>Sertakan lokasi agar mudah ditindaklanjuti.</li>
                    <li>Pelapor akan mendapat tanggapan dari admin.</li>
                    <li>Data pelapor hanya digunakan untuk keperluan tindak lanjut.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views\user\pengaduan\create.blade.php ENDPATH**/ ?>