@extends('layouts.app')

@section('title', 'Data Penduduk')

@push('styles')
    @vite(['resources/css/statistik.css', 'resources/js/statistik.jsx'])
@endpush

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Data Penduduk</h1>
        <p class="mb-0 opacity-75">Statistik kependudukan {{ $profil->nama_desa }}</p>
    </div>
</div>

<div class="container py-5">
    {{-- Filter wilayah (hasil tetap berupa angka statistik) --}}
    <form action="{{ route('penduduk.publik') }}" method="GET" class="d-flex flex-wrap gap-2 align-items-center mb-4 reveal">
        <span class="small fw-semibold text-muted me-1"><i class="bi bi-funnel me-1"></i>Wilayah:</span>
        <select name="rw" class="form-select form-select-sm w-auto">
            <option value="">Semua RW</option>
            @foreach ($rwList as $rw)
                <option value="{{ $rw }}" @selected(request('rw') == $rw)>RW {{ $rw }}</option>
            @endforeach
        </select>
        <select name="rt" class="form-select form-select-sm w-auto">
            <option value="">Semua RT</option>
            @foreach ($rtList as $rt)
                <option value="{{ $rt }}" @selected(request('rt') == $rt)>RT {{ $rt }}</option>
            @endforeach
        </select>
        <button class="btn btn-sm btn-biru px-3">Tampilkan</button>
        @if (request()->hasAny(['rt', 'rw']))
            <a href="{{ route('penduduk.publik') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        @endif
    </form>

    <div class="row g-3 mb-4">
        @foreach ([
            [$stats['total'], 'Total Penduduk', 'bi-people-fill'],
            [$stats['laki'], 'Laki-laki', 'bi-gender-male'],
            [$stats['perempuan'], 'Perempuan', 'bi-gender-female'],
            [$stats['kk'], 'Kepala Keluarga', 'bi-house-heart-fill'],
        ] as $s)
            <div class="col-6 col-lg-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon"><i class="bi {{ $s[2] }}"></i></div>
                        <div>
                            <div class="fs-4 fw-bold lh-1 mb-1">{{ number_format($s[0]) }}</div>
                            <div class="small text-muted">{{ $s[1] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ===== Grafik statistik (shadcn/ui charts + Recharts) ===== --}}
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card h-100 grafik-card">
                <div class="card-body p-4">
                    <h5 class="section-title mb-1">Jumlah Warga per RT/RW</h5>
                    <p class="small text-muted mb-3">Laki-laki dan perempuan di setiap wilayah</p>
                    <div data-grafik="wilayah" data-props='@json(['data' => $grafik['wilayah'], 'tinggi' => 300])' class="grafik-wadah" style="min-height: 300px"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100 grafik-card">
                <div class="card-body p-4">
                    <h5 class="section-title mb-1">Jenis Kelamin</h5>
                    <p class="small text-muted mb-3">Komposisi warga</p>
                    <div data-grafik="jenis-kelamin" data-props='@json(['laki' => $stats['laki'], 'perempuan' => $stats['perempuan'], 'tinggi' => 300])' class="grafik-wadah" style="min-height: 300px"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100 grafik-card">
                <div class="card-body p-4">
                    <h5 class="section-title mb-1">Kelompok Umur</h5>
                    <p class="small text-muted mb-3">Balita (0–4), Anak (5–11), Remaja (12–17), Dewasa (18–59), Lansia (60+)</p>
                    <div data-grafik="umur" data-props='@json(['data' => $grafik['umur'], 'tinggi' => 280])' class="grafik-wadah" style="min-height: 280px">
                        {{-- cadangan bila JavaScript tidak dapat dimuat --}}
                    @php($maks = max(1, max($kelompok)))
                    <div class="d-grid gap-3">
                        @foreach ($kelompok as $nama => $jumlah)
                            <div class="statbar">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="fw-semibold">{{ $nama }}
                                        <span class="text-muted fw-normal">{{ ['Balita' => '(0–4 th)', 'Anak' => '(5–11 th)', 'Remaja' => '(12–17 th)', 'Dewasa' => '(18–59 th)', 'Lansia' => '(60+ th)'][$nama] ?? '' }}</span>
                                    </span>
                                    <span class="fw-bold">{{ $jumlah }} <span class="text-muted fw-normal">jiwa</span></span>
                                </div>
                                <div class="statbar-track"><span style="width: {{ round($jumlah / $maks * 100) }}%"></span></div>
                            </div>
                        @endforeach
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100 grafik-card">
                <div class="card-body p-4">
                    <h5 class="section-title mb-1">Pekerjaan</h5>
                    <p class="small text-muted mb-3">Pekerjaan warga terbanyak</p>
                    <div data-grafik="peringkat" data-props='@json(['data' => $grafik['pekerjaan'], 'label' => 'Jiwa', 'warna' => 'var(--chart-1)'])' class="grafik-wadah"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100 grafik-card">
                <div class="card-body p-4">
                    <h5 class="section-title mb-1">Agama</h5>
                    <p class="small text-muted mb-3">Jumlah warga menurut agama</p>
                    <div data-grafik="peringkat" data-props='@json(['data' => $grafik['agama'], 'label' => 'Jiwa', 'warna' => 'var(--chart-4)'])' class="grafik-wadah"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body p-4">
                    <h5 class="section-title mb-3">Rincian per RT/RW</h5>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light"><tr><th>RW</th><th>RT</th><th class="text-end">L</th><th class="text-end">P</th><th class="text-end">Jiwa</th></tr></thead>
                            <tbody>
                                @forelse ($perRt as $item)
                                    <tr>
                                        <td>{{ $item->rw }}</td><td>{{ $item->rt }}</td>
                                        <td class="text-end">{{ (int) $item->laki }}</td><td class="text-end">{{ (int) $item->perempuan }}</td>
                                        <td class="text-end fw-semibold">{{ $item->jumlah }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted">Belum ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="offline-ready-note mt-4">
        <i class="bi bi-shield-lock-fill"></i>
        <span>Halaman ini hanya menampilkan statistik. Data pribadi warga (nama, NIK, alamat) dilindungi dan hanya dapat diakses petugas desa yang berwenang, sesuai UU No. 27 Tahun 2022 tentang Pelindungan Data Pribadi.</span>
    </div>
</div>
@endsection
