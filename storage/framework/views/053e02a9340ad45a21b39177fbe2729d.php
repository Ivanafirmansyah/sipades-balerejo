<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', 'Beranda'); ?> - <?php echo e($profil->nama_desa ?? 'SIPADES'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,700&display=swap" rel="stylesheet">
    <link href="<?php echo e(asset('css/sipades-theme.css')); ?>?v=8" rel="stylesheet">
    <link href="<?php echo e(asset('css/sipades-fitur.css')); ?>?v=11" rel="stylesheet">
    <?php echo $__env->make('partials.pwa-head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="site">
<?php
    $navProfil = request()->routeIs('profil') || request()->routeIs('struktur') || request()->routeIs('peta');
    $navInfo = request()->routeIs('berita.*') || request()->routeIs('agenda.*') || request()->routeIs('penduduk.publik');
?>


<div class="topinfo d-none d-md-block">
    <div class="container d-flex justify-content-between align-items-center">
        <div><i class="bi bi-geo-alt-fill me-1"></i> Selamat Datang di <?php echo e($profil->nama_desa ?? 'Desa'); ?>

            <?php if(!empty($profil->kecamatan)): ?> &bull; Kecamatan <?php echo e($profil->kecamatan); ?>, Kabupaten <?php echo e($profil->kabupaten); ?> <?php endif; ?>
        </div>
        <div class="d-flex gap-4">
            <?php if(!empty($profil->telepon)): ?><span><i class="bi bi-telephone-fill me-1"></i> <?php echo e($profil->telepon); ?></span><?php endif; ?>
            <?php if(!empty($profil->email)): ?><span><i class="bi bi-envelope-fill me-1"></i> <?php echo e($profil->email); ?></span><?php endif; ?>
        </div>
    </div>
</div>


<nav class="navbar navbar-expand-lg navbar-site sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo e(route('home')); ?>">
            <span class="brand-logo">
                <?php if(!empty($profil->logo)): ?>
                    <img src="<?php echo e(asset('storage/' . $profil->logo)); ?>" alt="Logo">
                <?php else: ?>
                    <i class="bi bi-bank2"></i>
                <?php endif; ?>
            </span>
            <span class="lh-sm">
                <span class="d-block brand-name"><?php echo e($profil->nama_desa ?? 'SIPADES'); ?></span>
                <span class="d-block brand-sub">Kec. <?php echo e($profil->kecamatan ?? '-'); ?>, Kab. <?php echo e($profil->kabupaten ?? '-'); ?></span>
            </span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navDesa">
            <i class="bi bi-list fs-2"></i>
        </button>
        <div class="collapse navbar-collapse" id="navDesa">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('home') ? 'active' : ''); ?>" href="<?php echo e(route('home')); ?>">Beranda</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo e($navProfil ? 'active' : ''); ?>" href="#" data-bs-toggle="dropdown">Profil</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo e(route('profil')); ?>"><i class="bi bi-info-circle"></i> Profil Desa</a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('struktur')); ?>"><i class="bi bi-diagram-3"></i> Struktur Organisasi</a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('peta')); ?>"><i class="bi bi-map"></i> Peta Desa</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo e($navInfo ? 'active' : ''); ?>" href="#" data-bs-toggle="dropdown">Informasi</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo e(route('berita.index')); ?>"><i class="bi bi-newspaper"></i> Berita & Pengumuman</a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('agenda.index')); ?>"><i class="bi bi-calendar-event"></i> Agenda Kegiatan</a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('penduduk.publik')); ?>"><i class="bi bi-bar-chart"></i> Data Penduduk</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo e(request()->routeIs('layanan') || request()->routeIs('formulir.*') ? 'active' : ''); ?>" href="#" data-bs-toggle="dropdown">Layanan</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo e(route('layanan')); ?>"><i class="bi bi-envelope-paper"></i> Surat & Pengaduan</a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('formulir.index')); ?>"><i class="bi bi-ui-checks"></i> Formulir Online <span class="badge badge-soft-success ms-1">Offline</span></a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link <?php echo e(request()->routeIs('kontak') ? 'active' : ''); ?>" href="<?php echo e(route('kontak')); ?>">Kontak</a></li>
            </ul>
            <div class="d-flex gap-2 py-2 py-lg-0">
                <button type="button" class="btn btn-outline-biru rounded-pill px-3" data-pasang-app hidden title="Pasang aplikasi di HP"><i class="bi bi-phone-fill"></i><span class="d-lg-none d-xl-inline ms-1">Pasang App</span></button>
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard')); ?>" class="btn btn-kuning rounded-pill px-4">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard <i class="bi bi-arrow-right btn-arrow"></i>
                    </a>
                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button class="btn btn-outline-biru rounded-pill px-3" title="Keluar"><i class="bi bi-box-arrow-right"></i></button>
                    </form>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-kuning rounded-pill px-4">Portal Warga <i class="bi bi-arrow-right btn-arrow"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<main>
    <div class="container flash-wrap"><?php echo $__env->make('partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
    <?php echo $__env->yieldContent('content'); ?>
</main>

<footer class="site-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="brand-logo"><i class="bi bi-bank2"></i></span>
                    <h5 class="text-white fw-bold mb-0"><?php echo e($profil->nama_desa ?? 'SIPADES'); ?></h5>
                </div>
                <p class="small mb-3"><?php echo e(\Illuminate\Support\Str::limit($profil->deskripsi ?? 'Website resmi desa untuk pelayanan masyarakat secara digital.', 140)); ?></p>
                <div class="d-flex gap-2 sosmed">
                    <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="footer-title">Menu Utama</h6>
                <ul class="list-unstyled small footer-links">
                    <li><a href="<?php echo e(route('home')); ?>">Beranda</a></li>
                    <li><a href="<?php echo e(route('profil')); ?>">Profil Desa</a></li>
                    <li><a href="<?php echo e(route('struktur')); ?>">Struktur</a></li>
                    <li><a href="<?php echo e(route('berita.index')); ?>">Berita</a></li>
                    <li><a href="<?php echo e(route('agenda.index')); ?>">Agenda</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="footer-title">Layanan</h6>
                <ul class="list-unstyled small footer-links">
                    <li><a href="<?php echo e(route('layanan')); ?>">Pengajuan Surat</a></li>
                    <li><a href="<?php echo e(route('layanan')); ?>#pengaduan">Pengaduan</a></li>
                    <li><a href="<?php echo e(route('formulir.index')); ?>">Formulir Online</a></li>
                    <li><a href="<?php echo e(route('penduduk.publik')); ?>">Data Penduduk</a></li>
                    <li><a href="<?php echo e(route('kontak')); ?>">Kontak</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h6 class="footer-title">Hubungi Kami</h6>
                <ul class="list-unstyled small footer-contact">
                    <li><i class="bi bi-geo-alt-fill"></i><span><?php echo e($profil->alamat_kantor ?? 'Kantor Desa'); ?></span></li>
                    <li><i class="bi bi-telephone-fill"></i><span><?php echo e($profil->telepon ?? '-'); ?></span></li>
                    <li><i class="bi bi-envelope-fill"></i><span><?php echo e($profil->email ?? '-'); ?></span></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">&copy; <?php echo e(date('Y')); ?> <?php echo e($profil->nama_desa ?? 'SIPADES'); ?>. Sistem Informasi Desa.</div>
    </div>
</footer>

<a href="#" class="back-top" aria-label="Kembali ke atas"><i class="bi bi-arrow-up"></i></a>

<?php echo $__env->make('partials.pwa-ui', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Animasi muncul saat di-scroll
    (function () {
        var els = document.querySelectorAll('.reveal');
        if (!('IntersectionObserver' in window)) { els.forEach(function (e) { e.classList.add('show'); }); return; }
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) { if (en.isIntersecting) { en.target.classList.add('show'); io.unobserve(en.target); } });
        }, { threshold: .12 });
        els.forEach(function (e) { io.observe(e); });
    })();
    // Tombol kembali ke atas + bayangan navbar
    window.addEventListener('scroll', function () {
        document.querySelector('.back-top').classList.toggle('show', window.scrollY > 400);
        document.querySelector('.navbar-site').classList.toggle('scrolled', window.scrollY > 10);
    });
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\sipades\resources\views/layouts/app.blade.php ENDPATH**/ ?>