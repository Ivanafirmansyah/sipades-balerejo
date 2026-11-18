<?php $__env->startSection('title', 'Akun Warga'); ?>
<?php $__env->startSection('heading', 'Akun Warga Otomatis'); ?>
<?php $__env->startSection('subheading', 'Tidak perlu mendaftar — akun dibuat dari data penduduk desa'); ?>

<?php $__env->startSection('content'); ?>
<?php ($profil = \App\Models\ProfilDesa::first()); ?>
<ol class="langkah-akun">
    <li>
        <span class="nomor">1</span>
        <div><b>Pastikan Anda sudah didata desa.</b><br><span class="text-muted">Akun otomatis tersedia untuk setiap warga yang datanya sudah dimasukkan petugas desa.</span></div>
    </li>
    <li>
        <span class="nomor">2</span>
        <div><b>Masuk dengan NIK.</b><br><span class="text-muted">Kata sandi pertama adalah tanggal lahir Anda, format <code>DDMMYYYY</code> (contoh: <code>17081990</code>).</span></div>
    </li>
    <li>
        <span class="nomor">3</span>
        <div><b>Buat kata sandi baru.</b><br><span class="text-muted">Saat pertama masuk Anda diminta mengganti kata sandi agar akun aman.</span></div>
    </li>
</ol>

<div class="login-hint small mb-3">
    <i class="bi bi-telephone-fill"></i>
    <div>NIK belum terdaftar atau lupa kata sandi? Hubungi kantor desa<?php echo e(!empty($profil->telepon) ? ' di ' . $profil->telepon : ''); ?> dengan membawa KTP/KK. Petugas akan mendata atau mengatur ulang kata sandi Anda.</div>
</div>

<div class="d-grid gap-2">
    <a href="<?php echo e(route('login')); ?>" class="btn btn-biru py-2 fw-semibold"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk dengan NIK</a>
    <a href="<?php echo e(route('home')); ?>" class="small text-decoration-none text-muted text-center"><i class="bi bi-arrow-left me-1"></i>Kembali ke beranda</a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views\auth\register.blade.php ENDPATH**/ ?>