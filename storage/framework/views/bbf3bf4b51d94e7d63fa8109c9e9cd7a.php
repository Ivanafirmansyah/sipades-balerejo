<?php ($role = auth()->user()->role); ?>
<div class="nav-heading">Menu Warga</div>
<a href="<?php echo e(route('user.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('user.dashboard') ? 'active' : ''); ?>">
    <i class="bi bi-grid-1x2"></i> Dashboard
</a>
<a href="<?php echo e(route('user.surat.create')); ?>" class="nav-link <?php echo e(request()->routeIs('user.surat.create') ? 'active' : ''); ?>">
    <i class="bi bi-file-earmark-plus"></i> Pengajuan Surat
</a>
<a href="<?php echo e(route('user.surat.index')); ?>" class="nav-link <?php echo e(request()->routeIs('user.surat.index') ? 'active' : ''); ?>">
    <i class="bi bi-clock-history"></i> Riwayat Surat
</a>
<a href="<?php echo e(route('formulir.index')); ?>" class="nav-link">
    <i class="bi bi-ui-checks"></i> Formulir Online
</a>
<a href="<?php echo e(route('user.pengaduan.create')); ?>" class="nav-link <?php echo e(request()->routeIs('user.pengaduan.create') ? 'active' : ''); ?>">
    <i class="bi bi-megaphone"></i> Layanan Pengaduan
</a>
<a href="<?php echo e(route('user.pengaduan.index')); ?>" class="nav-link <?php echo e(request()->routeIs('user.pengaduan.index') ? 'active' : ''); ?>">
    <i class="bi bi-chat-left-text"></i> Riwayat Pengaduan
</a>
<div class="nav-heading">Akun</div>
<a href="<?php echo e(route('akun.sandi')); ?>" class="nav-link <?php echo e(request()->routeIs('akun.sandi') ? 'active' : ''); ?>">
    <i class="bi bi-key"></i> Ganti Kata Sandi
</a>
<?php if($role === 'admin'): ?>
    <div class="nav-heading">Admin</div>
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link">
        <i class="bi bi-shield-lock"></i> Panel Admin
    </a>
<?php endif; ?>
<?php /**PATH C:\laragon\www\sipades\resources\views/partials/sidebar-user.blade.php ENDPATH**/ ?>