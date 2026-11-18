<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Beranda') - {{ $profil->nama_desa ?? 'SIPADES' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/sipades-theme.css') }}?v=8" rel="stylesheet">
    <link href="{{ asset('css/sipades-fitur.css') }}?v=11" rel="stylesheet">
    @include('partials.pwa-head')
    @stack('styles')
</head>
<body class="site">
@php
    $navProfil = request()->routeIs('profil') || request()->routeIs('struktur') || request()->routeIs('peta');
    $navInfo = request()->routeIs('berita.*') || request()->routeIs('agenda.*') || request()->routeIs('penduduk.publik');
@endphp

{{-- Bar info atas --}}
<div class="topinfo d-none d-md-block">
    <div class="container d-flex justify-content-between align-items-center">
        <div><i class="bi bi-geo-alt-fill me-1"></i> Selamat Datang di {{ $profil->nama_desa ?? 'Desa' }}
            @if (!empty($profil->kecamatan)) &bull; Kecamatan {{ $profil->kecamatan }}, Kabupaten {{ $profil->kabupaten }} @endif
        </div>
        <div class="d-flex gap-4">
            @if (!empty($profil->telepon))<span><i class="bi bi-telephone-fill me-1"></i> {{ $profil->telepon }}</span>@endif
            @if (!empty($profil->email))<span><i class="bi bi-envelope-fill me-1"></i> {{ $profil->email }}</span>@endif
        </div>
    </div>
</div>

{{-- Navbar putih --}}
<nav class="navbar navbar-expand-lg navbar-site sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <span class="brand-logo">
                @if (!empty($profil->logo))
                    <img src="{{ asset('storage/' . $profil->logo) }}" alt="Logo">
                @else
                    <i class="bi bi-bank2"></i>
                @endif
            </span>
            <span class="lh-sm">
                <span class="d-block brand-name">{{ $profil->nama_desa ?? 'SIPADES' }}</span>
                <span class="d-block brand-sub">Kec. {{ $profil->kecamatan ?? '-' }}, Kab. {{ $profil->kabupaten ?? '-' }}</span>
            </span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navDesa">
            <i class="bi bi-list fs-2"></i>
        </button>
        <div class="collapse navbar-collapse" id="navDesa">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ $navProfil ? 'active' : '' }}" href="#" data-bs-toggle="dropdown">Profil</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('profil') }}"><i class="bi bi-info-circle"></i> Profil Desa</a></li>
                        <li><a class="dropdown-item" href="{{ route('struktur') }}"><i class="bi bi-diagram-3"></i> Struktur Organisasi</a></li>
                        <li><a class="dropdown-item" href="{{ route('peta') }}"><i class="bi bi-map"></i> Peta Desa</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ $navInfo ? 'active' : '' }}" href="#" data-bs-toggle="dropdown">Informasi</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('berita.index') }}"><i class="bi bi-newspaper"></i> Berita & Pengumuman</a></li>
                        <li><a class="dropdown-item" href="{{ route('agenda.index') }}"><i class="bi bi-calendar-event"></i> Agenda Kegiatan</a></li>
                        <li><a class="dropdown-item" href="{{ route('penduduk.publik') }}"><i class="bi bi-bar-chart"></i> Data Penduduk</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('layanan') || request()->routeIs('formulir.*') ? 'active' : '' }}" href="#" data-bs-toggle="dropdown">Layanan</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('layanan') }}"><i class="bi bi-envelope-paper"></i> Surat & Pengaduan</a></li>
                        <li><a class="dropdown-item" href="{{ route('formulir.index') }}"><i class="bi bi-ui-checks"></i> Formulir Online <span class="badge badge-soft-success ms-1">Offline</span></a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('kontak') ? 'active' : '' }}" href="{{ route('kontak') }}">Kontak</a></li>
            </ul>
            <div class="d-flex gap-2 py-2 py-lg-0">
                <button type="button" class="btn btn-outline-biru rounded-pill px-3" data-pasang-app hidden title="Pasang aplikasi di HP"><i class="bi bi-phone-fill"></i><span class="d-lg-none d-xl-inline ms-1">Pasang App</span></button>
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" class="btn btn-kuning rounded-pill px-4">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard <i class="bi bi-arrow-right btn-arrow"></i>
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-outline-biru rounded-pill px-3" title="Keluar"><i class="bi bi-box-arrow-right"></i></button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-kuning rounded-pill px-4">Portal Warga <i class="bi bi-arrow-right btn-arrow"></i></a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<main>
    <div class="container flash-wrap">@include('partials.flash')</div>
    @yield('content')
</main>

<footer class="site-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="brand-logo"><i class="bi bi-bank2"></i></span>
                    <h5 class="text-white fw-bold mb-0">{{ $profil->nama_desa ?? 'SIPADES' }}</h5>
                </div>
                <p class="small mb-3">{{ \Illuminate\Support\Str::limit($profil->deskripsi ?? 'Website resmi desa untuk pelayanan masyarakat secara digital.', 140) }}</p>
                <div class="d-flex gap-2 sosmed">
                    <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="footer-title">Menu Utama</h6>
                <ul class="list-unstyled small footer-links">
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><a href="{{ route('profil') }}">Profil Desa</a></li>
                    <li><a href="{{ route('struktur') }}">Struktur</a></li>
                    <li><a href="{{ route('berita.index') }}">Berita</a></li>
                    <li><a href="{{ route('agenda.index') }}">Agenda</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="footer-title">Layanan</h6>
                <ul class="list-unstyled small footer-links">
                    <li><a href="{{ route('layanan') }}">Pengajuan Surat</a></li>
                    <li><a href="{{ route('layanan') }}#pengaduan">Pengaduan</a></li>
                    <li><a href="{{ route('formulir.index') }}">Formulir Online</a></li>
                    <li><a href="{{ route('penduduk.publik') }}">Data Penduduk</a></li>
                    <li><a href="{{ route('kontak') }}">Kontak</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h6 class="footer-title">Hubungi Kami</h6>
                <ul class="list-unstyled small footer-contact">
                    <li><i class="bi bi-geo-alt-fill"></i><span>{{ $profil->alamat_kantor ?? 'Kantor Desa' }}</span></li>
                    <li><i class="bi bi-telephone-fill"></i><span>{{ $profil->telepon ?? '-' }}</span></li>
                    <li><i class="bi bi-envelope-fill"></i><span>{{ $profil->email ?? '-' }}</span></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">&copy; {{ date('Y') }} {{ $profil->nama_desa ?? 'SIPADES' }}. Sistem Informasi Desa.</div>
    </div>
</footer>

<a href="#" class="back-top" aria-label="Kembali ke atas"><i class="bi bi-arrow-up"></i></a>

@include('partials.pwa-ui')
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
@stack('scripts')
</body>
</html>
