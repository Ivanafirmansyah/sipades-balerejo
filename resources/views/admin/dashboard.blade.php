@extends('layouts.panel')

@section('title', 'Dashboard Admin')
@section('subtitle', 'Ringkasan data dan aktivitas desa')

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@push('styles')
    @vite(['resources/css/statistik.css', 'resources/js/statistik.jsx'])
@endpush

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon bg-success-subtle text-success"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['penduduk']) }}</div>
                    <div class="small text-muted">Penduduk</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon bg-primary-subtle text-primary"><i class="bi bi-house-heart"></i></div>
                <div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['kk']) }}</div>
                    <div class="small text-muted">Kepala Keluarga</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon bg-warning-subtle text-warning"><i class="bi bi-hourglass-split"></i></div>
                <div>
                    <div class="fs-4 fw-bold">{{ $stats['surat_menunggu'] }}</div>
                    <div class="small text-muted">Surat Menunggu</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon bg-danger-subtle text-danger"><i class="bi bi-megaphone"></i></div>
                <div>
                    <div class="fs-4 fw-bold">{{ $stats['pengaduan_baru'] }}</div>
                    <div class="small text-muted">Pengaduan Baru</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== Grafik statistik (shadcn/ui charts) ===== --}}
<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <h6 class="fw-bold mb-0"><i class="bi bi-bar-chart-fill me-1"></i> Jumlah Warga per RT/RW</h6>
                        <div class="small text-muted">Laki-laki &amp; perempuan per wilayah</div>
                    </div>
                    <a href="{{ route('penduduk.publik') }}" target="_blank" class="btn btn-sm btn-outline-secondary">Statistik lengkap</a>
                </div>
                <div data-grafik="wilayah" data-props='@json(['data' => $grafik['wilayah'], 'tinggi' => 280])' style="min-height: 280px"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="card h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-pie-chart-fill me-1"></i> Jenis Kelamin</h6>
                <div class="small text-muted mb-2">Komposisi seluruh warga</div>
                <div data-grafik="jenis-kelamin" data-props='@json(['laki' => $grafik['laki'], 'perempuan' => $grafik['perempuan'], 'tinggi' => 280])' style="min-height: 280px"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-6">
        <div class="card h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-people-fill me-1"></i> Kelompok Umur</h6>
                <div class="small text-muted mb-2">Dihitung otomatis dari tanggal lahir</div>
                <div data-grafik="umur" data-props='@json(['data' => $grafik['umur'], 'tinggi' => 240])' style="min-height: 240px"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-envelope-paper-fill me-1"></i> Status Pengajuan Surat</h6>
                <div class="small text-muted mb-2">Semua pengajuan surat</div>
                <div data-grafik="peringkat" data-props='@json(['data' => $grafik['surat'], 'label' => 'Surat', 'warna' => 'var(--chart-3)'])'></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Pengajuan Surat Terbaru</h6>
                    <a href="{{ route('admin.surat.index') }}" class="btn btn-sm btn-outline-success">Kelola</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>#</th><th>Pemohon</th><th>Jenis</th><th>Tanggal</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($surats as $surat)
                                <tr>
                                    <td>{{ $surat->id }}</td>
                                    <td class="fw-semibold">{{ $surat->penduduk->nama ?? '-' }}</td>
                                    <td class="small">{{ $surat->jenisSurat->nama ?? '-' }}</td>
                                    <td class="small">{{ $surat->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @php($badge = ['menunggu' => 'warning', 'diproses' => 'info', 'selesai' => 'success', 'ditolak' => 'danger'][$surat->status])
                                        <span class="badge badge-soft-{{ $badge }}">{{ $surat->statusLabel() }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-3">Belum ada pengajuan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Pengaduan Terbaru</h6>
                    <a href="{{ route('admin.pengaduan.index') }}" class="btn btn-sm btn-outline-success">Kelola</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>Pelapor</th><th>Kategori</th><th>Tanggal</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($pengaduans as $pengaduan)
                                <tr>
                                    <td class="fw-semibold">{{ $pengaduan->nama_pelapor ?? $pengaduan->user->name ?? '-' }}</td>
                                    <td class="small">{{ $pengaduan->kategori }}</td>
                                    <td class="small">{{ $pengaduan->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @php($badge = ['baru' => 'warning', 'diproses' => 'info', 'selesai' => 'success'][$pengaduan->status])
                                        <span class="badge badge-soft-{{ $badge }}">{{ $pengaduan->statusLabel() }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">Belum ada pengaduan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-file-earmark-pdf text-danger me-1"></i> Export PDF</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.penduduk.export') }}" class="btn btn-outline-danger btn-sm"><i class="bi bi-download me-1"></i> Laporan Data Penduduk</a>
                    <a href="{{ route('admin.surat.export') }}" class="btn btn-outline-danger btn-sm"><i class="bi bi-download me-1"></i> Laporan Surat Menyurat</a>
                    <a href="{{ route('admin.pengaduan.export') }}" class="btn btn-outline-danger btn-sm"><i class="bi bi-download me-1"></i> Laporan Pengaduan</a>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-calendar-event text-success me-1"></i> Agenda Mendatang</h6>
                <div class="d-grid gap-3">
                    @forelse ($agendas as $agenda)
                        <div class="d-flex gap-2">
                            <div class="text-center bg-success text-white rounded px-2 py-1" style="min-width: 52px;">
                                <div class="fw-bold lh-1">{{ $agenda->tanggal->format('d') }}</div>
                                <div class="small">{{ $agenda->tanggal->translatedFormat('M') }}</div>
                            </div>
                            <div>
                                <div class="small fw-semibold">{{ $agenda->judul }}</div>
                                <div class="small text-muted">{{ $agenda->lokasi ?? '-' }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="small text-muted">Tidak ada agenda mendatang.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-diagram-3 text-success me-1"></i> Sebaran per RT/RW</h6>
                <table class="table table-sm mb-0">
                    <thead><tr><th>RT</th><th>RW</th><th class="text-end">Jiwa</th></tr></thead>
                    <tbody>
                        @forelse ($perRt as $item)
                            <tr><td>{{ $item->rt }}</td><td>{{ $item->rw }}</td><td class="text-end fw-semibold">{{ $item->jumlah }}</td></tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
