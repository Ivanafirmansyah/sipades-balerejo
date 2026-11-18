@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
@php($foto = 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1600')

{{-- ================= HERO SLIDER ================= --}}
<section id="heroSlider" class="carousel slide carousel-fade hero-slider" data-bs-ride="carousel" data-bs-interval="6000">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="0" class="active" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active" style="background-image: url('{{ $foto }}'); background-position: center 40%;">
            <div class="container hero-content">
                <div class="hero-tagline">Mudah, Cepat, dan Praktis</div>
                <h1 class="hero-title">Layanan Surat Online 24 Jam</h1>
                <p class="hero-text">Ajukan surat keterangan kapan saja dan di mana saja tanpa harus antre di kantor desa. Pantau status pengajuan langsung dari dashboard warga.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('layanan') }}" class="btn btn-kuning rounded-pill px-4">Ajukan Surat <i class="bi bi-arrow-right btn-arrow"></i></a>
                    <a href="{{ route('layanan') }}#pengaduan" class="btn btn-outline-light rounded-pill px-4">Kirim Pengaduan</a>
                </div>
            </div>
        </div>
        <div class="carousel-item" style="background-image: url('{{ $foto }}'); background-position: center 80%;">
            <div class="container hero-content">
                <div class="hero-tagline">Website Resmi</div>
                <h1 class="hero-title">Selamat Datang di {{ $profil->nama_desa }}</h1>
                <p class="hero-text">Pusat informasi dan layanan administrasi desa yang modern, transparan, dan mudah diakses oleh seluruh masyarakat.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('profil') }}" class="btn btn-light rounded-pill px-4">Profil Desa <i class="bi bi-arrow-right btn-arrow"></i></a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light rounded-pill px-4">Portal Warga</a>
                </div>
            </div>
        </div>
        <div class="carousel-item" style="background-image: url('{{ $foto }}'); background-position: center 10%;">
            <div class="container hero-content">
                <div class="hero-tagline">Transparan & Terbuka</div>
                <h1 class="hero-title">Informasi Desa Dalam Genggaman</h1>
                <p class="hero-text">Ikuti berita, pengumuman, agenda kegiatan, dan data kependudukan {{ $profil->nama_desa }} secara langsung.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('berita.index') }}" class="btn btn-kuning rounded-pill px-4">Baca Berita <i class="bi bi-arrow-right btn-arrow"></i></a>
                    <a href="{{ route('penduduk.publik') }}" class="btn btn-outline-light rounded-pill px-4">Data Penduduk</a>
                </div>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev"><i class="bi bi-chevron-left"></i></button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next"><i class="bi bi-chevron-right"></i></button>
    <a href="#unggulan" class="hero-scroll" aria-label="Gulir ke bawah"><i class="bi bi-chevron-double-down"></i></a>
</section>

{{-- ================= LAYANAN UNGGULAN (pita kuning) ================= --}}
<section id="unggulan" class="band-kuning">
    <div class="container">
        <div class="row g-4">
            @foreach ([
                ['bi-file-earmark-text', 'Pembuatan Surat Online', 'Ajukan pembuatan surat secara online tanpa harus datang ke kantor desa.', route('layanan'), 'Ajukan Sekarang'],
                ['bi-graph-up-arrow', 'Data & Statistik Desa', 'Akses data demografi dan statistik desa secara transparan dan real-time.', route('penduduk.publik'), 'Lihat Data'],
                ['bi-newspaper', 'Berita & Informasi', 'Dapatkan informasi terkini tentang kegiatan dan program pembangunan desa.', route('berita.index'), 'Baca Berita'],
            ] as $i => $f)
                <div class="col-md-4 reveal" style="transition-delay: {{ $i * 120 }}ms">
                    <a href="{{ $f[3] }}" class="feature-card">
                        <div class="feature-media"><i class="bi {{ $f[0] }}"></i></div>
                        <div class="feature-body">
                            <h5>{{ $f[1] }}</h5>
                            <p>{{ $f[2] }}</p>
                            <span class="link-kuning">{{ $f[4] }} <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= LAYANAN KAMI ================= --}}
<section class="py-5 my-lg-4">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <h2 class="judul-tengah">Layanan Kami</h2>
            <p class="text-muted mb-0">Berbagai layanan administrasi dan informasi desa yang dapat Anda akses dengan mudah</p>
        </div>
        <div class="row g-4">
            @foreach ([
                ['bi-envelope-paper', 'Surat Menyurat', 'Pembuatan surat keterangan, surat pengantar, dan dokumen resmi desa lainnya.', route('layanan'), 'Lihat Layanan'],
                ['bi-people', 'Data Penduduk', 'Informasi demografi dan statistik penduduk desa secara transparan.', route('penduduk.publik'), 'Lihat Data'],
                ['bi-megaphone', 'Berita Desa', 'Update terkini tentang kegiatan, program, dan pengumuman desa.', route('berita.index'), 'Baca Berita'],
                ['bi-chat-dots', 'Pengaduan', 'Layanan pengaduan dan aspirasi masyarakat untuk desa yang lebih baik.', route('layanan') . '#pengaduan', 'Hubungi Kami'],
            ] as $i => $l)
                <div class="col-sm-6 col-lg-3 reveal" style="transition-delay: {{ $i * 100 }}ms">
                    <a href="{{ $l[3] }}" class="service-card">
                        <div class="service-icon"><i class="bi {{ $l[0] }}"></i></div>
                        <h6>{{ $l[1] }}</h6>
                        <p>{{ $l[2] }}</p>
                        <span class="link-kuning">{{ $l[4] }} <i class="bi bi-arrow-right"></i></span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= SAMBUTAN KEPALA DESA ================= --}}
<section class="quote-section">
    <div class="container text-center reveal">
        <i class="bi bi-quote quote-mark"></i>
        <blockquote>
            {{ $profil->visi ?? 'Pelayanan desa yang modern dan transparan adalah kunci untuk meningkatkan kesejahteraan masyarakat dan membangun desa yang maju.' }}
        </blockquote>
        <div class="d-inline-flex align-items-center gap-3 mt-3 text-start">
            <span class="quote-avatar">
                @if (!empty($kepalaDesa?->foto))
                    <img src="{{ asset('storage/' . $kepalaDesa->foto) }}" alt="{{ $kepalaDesa->nama }}">
                @else
                    <i class="bi bi-person-fill"></i>
                @endif
            </span>
            <div>
                <div class="fw-bold text-white">{{ $kepalaDesa->nama ?? 'Kepala Desa' }}</div>
                <div class="small quote-role">Kepala {{ $profil->nama_desa }}</div>
            </div>
        </div>
    </div>
</section>

{{-- ================= STATISTIK ================= --}}
<section class="stats-band" style="background-image: url('{{ $foto }}');">
    <div class="container">
        <div class="row g-4 text-center">
            @foreach ([
                [$stats['penduduk'], 'Total Penduduk', 'bi-people-fill'],
                [$stats['kk'], 'Kepala Keluarga', 'bi-house-heart-fill'],
                [$stats['rt'], 'Jumlah RT', 'bi-signpost-2-fill'],
                [$stats['rw'], 'Jumlah RW', 'bi-pin-map-fill'],
            ] as $s)
                <div class="col-6 col-lg-3 reveal">
                    <div class="stat-big">
                        <i class="bi {{ $s[2] }}"></i>
                        <div class="angka" data-count="{{ $s[0] }}">{{ number_format($s[0]) }}</div>
                        <div class="label">{{ $s[1] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= BERITA ================= --}}
<section class="py-5 my-lg-4">
    <div class="container">
        <div class="d-flex flex-wrap align-items-end justify-content-between gap-2 mb-4 reveal">
            <div>
                <h2 class="judul-kiri">Berita & Pengumuman</h2>
                <p class="text-muted mb-0">Kabar terbaru dari {{ $profil->nama_desa }}</p>
            </div>
            <a href="{{ route('berita.index') }}" class="btn btn-biru rounded-pill px-4">Lihat Semua <i class="bi bi-arrow-right btn-arrow"></i></a>
        </div>
        <div class="row g-4">
            @forelse ($beritas as $i => $berita)
                <div class="col-md-4 reveal" style="transition-delay: {{ $i * 120 }}ms">
                    <a href="{{ route('berita.show', $berita->slug) }}" class="news-card">
                        <div class="news-img">
                            @if ($berita->gambar)
                                <img src="{{ asset('storage/' . $berita->gambar) }}" alt="{{ $berita->judul }}">
                            @else
                                <div class="news-placeholder"><i class="bi bi-newspaper"></i></div>
                            @endif
                            <span class="news-tag">{{ ucfirst($berita->kategori) }}</span>
                        </div>
                        <div class="news-body">
                            <div class="news-date"><i class="bi bi-calendar3 me-1"></i>{{ ($berita->published_at ?? $berita->created_at)->translatedFormat('d F Y') }}</div>
                            <h6>{{ $berita->judul }}</h6>
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($berita->isi), 95) }}</p>
                            <span class="link-biru">Baca Selengkapnya <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12"><div class="alert alert-light border text-center text-muted">Belum ada berita.</div></div>
            @endforelse
        </div>
    </div>
</section>

{{-- ================= AGENDA ================= --}}
<section class="pb-5 mb-lg-4">
    <div class="container">
        <div class="d-flex flex-wrap align-items-end justify-content-between gap-2 mb-4 reveal">
            <div>
                <h2 class="judul-kiri">Agenda Kegiatan</h2>
                <p class="text-muted mb-0">Jadwal kegiatan desa yang akan datang</p>
            </div>
            <a href="{{ route('agenda.index') }}" class="btn btn-outline-biru rounded-pill px-4">Semua Agenda</a>
        </div>
        <div class="row g-3">
            @forelse ($agendas as $i => $agenda)
                <div class="col-md-4 reveal" style="transition-delay: {{ $i * 120 }}ms">
                    <div class="agenda-card">
                        <div class="agenda-date">
                            <div class="tgl">{{ $agenda->tanggal->format('d') }}</div>
                            <div class="bln">{{ $agenda->tanggal->translatedFormat('M') }}</div>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <h6 class="fw-bold mb-1">{{ $agenda->judul }}</h6>
                            <div class="small text-muted"><i class="bi bi-clock me-1"></i>{{ $agenda->jam_formatted ?? '-' }} WIB</div>
                            <div class="small text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $agenda->lokasi ?? '-' }}</div>
                        </div>
                        @if ($agenda->foto)
                            <img src="{{ $agenda->foto_url }}" alt="{{ $agenda->judul }}" class="agenda-mini-foto" loading="lazy">
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-12"><div class="alert alert-light border text-center text-muted">Belum ada agenda kegiatan.</div></div>
            @endforelse
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Animasi angka statistik berhitung naik
    (function () {
        var nums = document.querySelectorAll('.stat-big .angka');
        var run = function (el) {
            var target = +el.dataset.count, start = null;
            var step = function (t) {
                if (!start) start = t;
                var p = Math.min((t - start) / 1200, 1);
                el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3))).toLocaleString('id-ID');
                if (p < 1) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
        };
        if (!('IntersectionObserver' in window)) return;
        var io = new IntersectionObserver(function (es) {
            es.forEach(function (e) { if (e.isIntersecting) { run(e.target); io.unobserve(e.target); } });
        }, { threshold: .5 });
        nums.forEach(function (n) { io.observe(n); });
    })();
</script>
@endpush
