<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - SIPADES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --hijau: #146c43; --hijau-tua: #0f5132; --sidebar: 260px; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: #f4f6f4; }
        .sidebar {
            position: fixed; top: 0; left: 0; bottom: 0; width: var(--sidebar);
            background: linear-gradient(180deg, var(--hijau-tua), #0a3a24);
            color: #fff; z-index: 1040; overflow-y: auto; transition: transform .25s ease;
        }
        .sidebar .brand { padding: 1.25rem 1.25rem; border-bottom: 1px solid rgba(255,255,255,.12); font-weight: 700; font-size: 1.05rem; }
        .sidebar .nav-link {
            color: rgba(255,255,255,.78); border-radius: .5rem; padding: .6rem .9rem;
            margin: .15rem .6rem; font-size: .93rem; display: flex; align-items: center; gap: .6rem;
        }
        .sidebar .nav-link:hover { background: rgba(255,255,255,.08); color: #fff; }
        .sidebar .nav-link.active { background: rgba(255,255,255,.16); color: #fff; font-weight: 600; }
        .sidebar .nav-heading { font-size: .72rem; text-transform: uppercase; letter-spacing: .08em; color: rgba(255,255,255,.45); padding: 1rem 1.4rem .35rem; }
        .main { margin-left: var(--sidebar); min-height: 100vh; }
        .topbar { background: #fff; border-bottom: 1px solid #e8ece8; padding: .75rem 1.5rem; position: sticky; top: 0; z-index: 1030; }
        .content { padding: 1.5rem; }
        .card { border: 0; border-radius: .9rem; box-shadow: 0 2px 14px rgba(0,0,0,.05); }
        .stat-card { border-radius: 1rem; border: 0; box-shadow: 0 2px 14px rgba(0,0,0,.06); }
        .stat-card .icon { width: 50px; height: 50px; display: grid; place-items: center; border-radius: 12px; font-size: 1.35rem; }
        .btn-hijau { background: var(--hijau); border-color: var(--hijau); color: #fff; }
        .btn-hijau:hover { background: var(--hijau-tua); border-color: var(--hijau-tua); color: #fff; }
        .text-hijau { color: var(--hijau); }
        .badge-soft-success { background: #d1e7dd; color: #0f5132; }
        .badge-soft-warning { background: #fff3cd; color: #664d03; }
        .badge-soft-danger { background: #f8d7da; color: #842029; }
        .badge-soft-info { background: #cff4fc; color: #055160; }
        .badge-soft-secondary { background: #e2e3e5; color: #41464b; }
        .table > :not(caption) > * > * { padding: .7rem .75rem; }
        .avatar-inisial { width: 38px; height: 38px; border-radius: 50%; display: inline-grid; place-items: center; background: #d1e7dd; color: #0f5132; font-weight: 700; }
        .auth-card { max-width: 440px; margin: 0 auto; }
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main { margin-left: 0; }
            .sidebar-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 1035; display: none; }
            .sidebar-backdrop.show { display: block; }
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/sipades-theme.css') }}?v=8" rel="stylesheet">
    <link href="{{ asset('css/sipades-fitur.css') }}?v=11" rel="stylesheet">
    @include('partials.pwa-head')
    @stack('styles')
</head>
<body>
<div class="sidebar" id="sidebar">
    <div class="brand d-flex align-items-center gap-2">
        <i class="bi bi-bank2"></i> SIPADES
    </div>
    @yield('sidebar')
    <div class="mt-3 mb-4 px-3">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn btn-outline-light btn-sm w-100"><i class="bi bi-box-arrow-right me-1"></i> Keluar</button>
        </form>
    </div>
</div>
<div class="sidebar-backdrop" id="backdrop" onclick="toggleSidebar()"></div>

<div class="main">
    <div class="topbar d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-light d-lg-none border" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
            <div>
                <div class="fw-bold">@yield('title', 'Dashboard')</div>
                <div class="small text-muted">@yield('subtitle', 'Sistem Informasi Desa')</div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary d-none d-sm-inline"><i class="bi bi-house me-1"></i> Lihat Situs</a>
            <span class="avatar-inisial">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</span>
            <div class="d-none d-md-block">
                <div class="small fw-semibold">{{ auth()->user()->name ?? '' }}</div>
                <div class="small text-muted text-capitalize">{{ auth()->user()->role ?? '' }}</div>
            </div>
        </div>
    </div>
    <div class="content">
        @include('partials.flash')
        @yield('content')
    </div>
</div>

@include('partials.pwa-ui')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('show');
        document.getElementById('backdrop').classList.toggle('show');
    }
</script>
@stack('scripts')
</body>
</html>
