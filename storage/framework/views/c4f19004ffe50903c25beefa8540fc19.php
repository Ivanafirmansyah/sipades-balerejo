<div class="nav-heading">Utama</div>
<a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
    <i class="bi bi-grid-1x2"></i> Dashboard
</a>

<div class="nav-heading">Pelayanan</div>
<a href="<?php echo e(route('admin.penduduk.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.penduduk.*') ? 'active' : ''); ?>">
    <i class="bi bi-people"></i> Data Penduduk
</a>
<a href="<?php echo e(route('admin.surat.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.surat.index') ? 'active' : ''); ?>">
    <i class="bi bi-file-earmark-text"></i> Surat Menyurat
</a>
<a href="<?php echo e(route('admin.surat.pengaturan')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.surat.pengaturan*') ? 'active' : ''); ?>">
    <i class="bi bi-file-earmark-ruled"></i> Pengaturan Surat
</a>
<a href="<?php echo e(route('admin.jenis.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.jenis.*') ? 'active' : ''); ?>">
    <i class="bi bi-tags"></i> Jenis Surat
</a>
<a href="<?php echo e(route('admin.pengaduan.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.pengaduan.*') ? 'active' : ''); ?>">
    <i class="bi bi-megaphone"></i> Pengaduan Warga
</a>
<a href="<?php echo e(route('admin.formulir.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.formulir.*') ? 'active' : ''); ?>">
    <i class="bi bi-ui-checks-grid"></i> Formulir Baru
</a>

<div class="nav-heading">Konten</div>
<a href="<?php echo e(route('admin.berita.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.berita.*') ? 'active' : ''); ?>">
    <i class="bi bi-newspaper"></i> Berita & Pengumuman
</a>
<a href="<?php echo e(route('admin.agenda.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.agenda.*') ? 'active' : ''); ?>">
    <i class="bi bi-calendar-event"></i> Agenda Kegiatan
</a>

<div class="nav-heading">Manajemen Desa</div>
<a href="<?php echo e(route('admin.perangkat.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.perangkat.*') ? 'active' : ''); ?>">
    <i class="bi bi-person-badge"></i> Perangkat Desa
</a>
<a href="<?php echo e(route('admin.lokasi.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.lokasi.*') ? 'active' : ''); ?>">
    <i class="bi bi-pin-map"></i> Peta Fasilitas
</a>
<a href="<?php echo e(route('admin.profil.edit')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.profil.*') ? 'active' : ''); ?>">
    <i class="bi bi-gear"></i> Profil & Peta
</a>
<a href="<?php echo e(route('admin.users.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.users.*') ? 'active' : ''); ?>">
    <i class="bi bi-shield-lock"></i> Manajemen Admin
</a>
<a href="<?php echo e(route('akun.sandi')); ?>" class="nav-link">
    <i class="bi bi-key"></i> Ganti Kata Sandi
</a>
<a href="<?php echo e(route('user.dashboard')); ?>" class="nav-link">
    <i class="bi bi-person"></i> Panel Warga
</a>
<?php /**PATH C:\laragon\www\sipades\resources\views/partials/sidebar-admin.blade.php ENDPATH**/ ?>