<?php $__env->startSection('title', 'Masuk'); ?>
<?php $__env->startSection('heading', 'Masuk ke SIPADES'); ?>
<?php $__env->startSection('subheading', 'Warga cukup masuk dengan NIK — tidak perlu mendaftar'); ?>

<?php $__env->startSection('content'); ?>
<form action="<?php echo e(route('login')); ?>" method="POST" class="d-grid gap-3">
    <?php echo csrf_field(); ?>

    <div>
        <label class="form-label small fw-semibold" for="login">NIK <span class="text-muted fw-normal">(atau email untuk admin)</span></label>
        <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-person-vcard"></i></span>
            <input type="text" id="login" name="login" value="<?php echo e(old('login')); ?>" class="form-control <?php $__errorArgs = ['login'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   placeholder="16 digit NIK" autocomplete="username" required autofocus>
        </div>
        <?php $__errorArgs = ['login'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div>
        <label class="form-label small fw-semibold" for="password">Kata Sandi</label>
        <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
            <input type="password" id="password" name="password" class="form-control" placeholder="Kata sandi" autocomplete="current-password" required>
            <button type="button" class="btn btn-outline-secondary" id="lihatSandi" title="Lihat kata sandi"><i class="bi bi-eye"></i></button>
        </div>
    </div>

    <div class="login-hint small">
        <i class="bi bi-info-circle-fill"></i>
        <div><b>Pertama kali masuk?</b> Gunakan <b>tanggal lahir</b> Anda sebagai kata sandi dengan format <b>DDMMYYYY</b> — contoh lahir 17 Agustus 1990 ketik <code>17081990</code>. Setelah itu Anda diminta membuat kata sandi baru.</div>
    </div>

    <div class="form-check">
        <input type="checkbox" name="remember" id="remember" class="form-check-input" value="1">
        <label for="remember" class="form-check-label small">Ingat saya</label>
    </div>

    <button class="btn btn-biru py-2 fw-semibold"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk</button>

    <div class="text-center small text-muted">
        NIK belum terdaftar? <a href="<?php echo e(route('register')); ?>" class="fw-semibold text-decoration-none">Lihat caranya</a>
    </div>
    <div class="text-center">
        <a href="<?php echo e(route('home')); ?>" class="small text-decoration-none text-muted"><i class="bi bi-arrow-left me-1"></i>Kembali ke beranda</a>
    </div>
</form>

<script>
    document.getElementById('lihatSandi').addEventListener('click', function () {
        var i = document.getElementById('password'); var show = i.type === 'password';
        i.type = show ? 'text' : 'password';
        this.innerHTML = '<i class="bi bi-eye' + (show ? '-slash' : '') + '"></i>';
    });
    // NIK: buang spasi yang ikut tersalin
    document.getElementById('login').addEventListener('blur', function () { if (/^[\d\s]+$/.test(this.value)) this.value = this.value.replace(/\s+/g, ''); });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sipades\resources\views/auth/login.blade.php ENDPATH**/ ?>