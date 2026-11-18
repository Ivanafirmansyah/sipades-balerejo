@extends('layouts.panel')

@section('title', 'Pengaduan Warga')
@section('subtitle', 'Kelola laporan dan pengaduan masyarakat')

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="row g-3 mb-4">
    @foreach (['baru' => ['Pengaduan Baru', 'bi-inbox-fill'], 'diproses' => ['Sedang Diproses', 'bi-arrow-repeat'], 'selesai' => ['Selesai', 'bi-check2-circle']] as $key => $item)
        <div class="col-6 col-lg-3">
            <a href="{{ route('admin.pengaduan.index', ['status' => $key]) }}" class="text-decoration-none">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon"><i class="bi {{ $item[1] }}"></i></div>
                        <div>
                            <div class="fs-4 fw-bold lh-1 mb-1">{{ $stats[$key] }}</div>
                            <div class="small text-muted">{{ $item[0] }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
    <div class="col-6 col-lg-3">
        <a href="{{ route('admin.pengaduan.export', request()->only('status')) }}" class="export-card">
            <span class="export-icon"><i class="bi bi-file-earmark-pdf-fill"></i></span>
            <span class="flex-grow-1 lh-sm">
                <span class="d-block fw-bold">Export PDF</span>
                <span class="d-block small export-sub">
                    {{ request('status') ? 'Pengaduan ' . ucfirst(request('status')) : 'Semua pengaduan' }}
                </span>
            </span>
            <i class="bi bi-download export-dl"></i>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('admin.pengaduan.index') }}" method="GET" class="row g-2 mb-3">
            <div class="col-md-5">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Cari isi / pelapor...">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    @foreach (['baru' => 'Baru', 'diproses' => 'Diproses', 'selesai' => 'Selesai'] as $key => $label)
                        <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-sm btn-hijau"><i class="bi bi-search me-1"></i> Cari</button>
                <a href="{{ route('admin.pengaduan.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr><th>#</th><th>Pelapor</th><th>Kategori</th><th>Isi</th><th>Tanggal</th><th>Status</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($pengaduans as $pengaduan)
                        <tr>
                            <td>{{ $pengaduan->id }}</td>
                            <td class="small fw-semibold">{{ $pengaduan->nama_pelapor ?? $pengaduan->user->name ?? '-' }}</td>
                            <td class="small">{{ $pengaduan->kategori }}</td>
                            <td class="small text-muted">{{ \Illuminate\Support\Str::limit($pengaduan->isi, 60) }}</td>
                            <td class="small">{{ $pengaduan->created_at->format('d/m/Y') }}</td>
                            <td>
                                @php($badge = ['baru' => 'warning', 'diproses' => 'info', 'selesai' => 'success'][$pengaduan->status])
                                <span class="badge badge-soft-{{ $badge }}">{{ $pengaduan->statusLabel() }}</span>
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.pengaduan.show', $pengaduan) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Tanggapi</a>
                                <form action="{{ route('admin.pengaduan.destroy', $pengaduan) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pengaduan ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada pengaduan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $pengaduans->links() }}
    </div>
</div>
@endsection
