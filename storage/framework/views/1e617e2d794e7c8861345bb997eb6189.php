<?php $__env->startSection('title', 'Kata Sandi'); ?>
<?php $__env->startSection('heading', $user->wajib_ganti_sandi ? 'Buat Kata Sandi Baru' : 'Ganti Kata Sandi'); ?>
<?php $__env->startSection('subheading', $user->wajib_ganti_sandi ? 'Langkah terakhir sebelum memakai layanan desa' : 'Perbarui kata sandi akun Anda'); ?>

<?php $__env->startSection('content'); ?>
<div class="akun-info mb-3">
    <span class="akun-avatar"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></span>
    <div class="lh-sm">
        <div class="fw-bold"><?php echo e($user->name); ?></div>
        <div class="small text-muted"><?php echo e($user->nik ? 'NIK ' . $user->nik : $user->email); ?></div>
    </div>
</div>

<?php if($user->wajib_ganti_sandi): ?>
    <div class="login-hint small mb-3">
        <i class="bi bi-shield-lock-fill"></i>
        <div>Anda masih memakai kata sandi awal (tanggal lahir) yang mudah ditebak. Buat kata sandi baru minimal 8 karakter.</div>
    </div>
<?php endif; ?>

<form action="<?php echo e(route('akun.sandi.update')); ?>" method="POST" class="d-grid gap-3">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

    <?php if (! ($user->wajib_ganti_sandi)): ?>
        <div>
            <label class="form-label small fw-semibold" for="password_lama">Kata Sandi Lama</label>
            <input type="password" id="password_lama" name="password_lama" class="form-control <?php $__errorArgs = ['password_lama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" autocomplete="current-password" required>
            <?php $__errorArgs = ['password_lama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    <?php endif; ?>

    <div>
        <label class="form-label small fw-semibold" for="password">Kata Sandi Baru</label>
        <input type="password" id="password" name="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" minlength="8" autocomplete="new-password" placeholder="Minimal 8 karakter" required>
        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        <div class="sandi-meter mt-2"><span id="meterBar"></span></div>
        <div class="small text-muted mt-1" id="meterText">Gunakan gabungan huruf dan angka.</div>
    </div>

    <div>
        <label class="form-label small fw-semibold" for="password_confirmation">Ulangi Kata Sandi Baru</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" minlength="8" autocomplete="new-password" required>
    </div>

    <?php if($user->wajib_ganti_sandi && !$user->phone): ?>
        <div>
            <label class="form-label small fw-semibold" for="phone">No. HP / WhatsApp <span class="text-muted fw-normal">(opsional)</span></label>
            <input type="tel" id="phone" name="phone" value="<?php echo e(old('phone')); ?>" class="form-control" placeholder="08xxxxxxxxxx">
            <div class="form-text">Agar petugas desa bisa menghubungi Anda terkait layanan.</div>
        </div>
    <?php endif; ?>

    <button class="btn btn-kuning py-2 fw-semibold"><i class="bi bi-check2-circle me-1"></i> Simpan Kata Sandi</button>
</form>

<div class="text-center mt-2">
    <?php if($user->wajib_ganti_sandi): ?>
        <form action="<?php echo e(route('logout')); ?>" method="POST"><?php echo csrf_field(); ?><button class="btn btn-link btn-sm text-muted text-decoration-none">Keluar</button></form>
    <?php else: ?>
        <a href="<?php echo e($user->isAdmin() ? route('admin.dashboard') : route('user.dashboard')); ?>" class="small text-decoration-none text-muted"><i class="bi bi-arrow-left me-1"></i>Kembali ke dashboard</a>
    <?php endif; ?>
</div>

<script>
    (function () {
        var i = document.getElementById('password'), bar = document.getElementById('meterBar'), t = document.getElementById('meterText');
        i.addEventListener('input', function () {
            var v = i.value, s = 0;
            if (v.length >= 8) s++; if (v.length >= 12) s++;
            if (/[a-z]/.test(v) && /[A-Z]/.test(v)) s++; if (/\d/.test(v)) s++; if (/[^A-Za-z0-9]/.test(v)) s++;
            if (/^\d+$/.test(v)) s = Math.min(s, 1);
            var lv = [['Terlalu pendek', '#ef4444', 15], ['Lemah', '#f97316', 35], ['Cukup', '#f59e0b', 60], ['Kuat', '#16a34a', 85], ['Sangat kuat', '#15803d', 100]][Math.min(v.length < 8 ? 0 : s, 4)];
            bar.style.width = lv[2] + '%'; bar.style.background = lv[1]; t.textContent = lv[0];
        });
    })();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views/auth/sandi.blade.php ENDPATH**/ ?>