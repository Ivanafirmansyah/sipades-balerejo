@extends('layouts.panel')

@section('title', 'Surat Menyurat')
@section('subtitle', 'Kelola pengajuan surat warga')

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="row g-3 mb-4">
    @foreach (['menunggu' => ['Menunggu', 'warning'], 'diproses' => ['Diproses', 'info'], 'selesai' => ['Selesai', 'success'], 'ditolak' => ['Ditolak', 'danger']] as $key => $item)
        <div class="col-6 col-lg-3">
            <a href="{{ route('admin.surat.index', ['status' => $key]) }}" class="text-decoration-none">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="small text-muted">{{ $item[0] }}</div>
                        <div class="fs-4 fw-bold text-dark">{{ $stats[$key] }}</div>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>

<div class="card">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h6 class="fw-bold mb-0">Daftar Pengajuan</h6>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.surat.export') }}" class="btn btn-sm btn-outline-danger"><i class="bi bi-file-earmark-pdf me-1"></i> Export PDF</a>
                <a href="{{ route('admin.jenis.index') }}" class="btn btn-sm btn-outline-success"><i class="bi bi-tags me-1"></i> Jenis Surat</a>
            </div>
        </div>

        <form action="{{ route('admin.surat.index') }}" method="GET" class="row g-2 mb-3">
            <div class="col-md-5">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Cari no. surat / nama / NIK / jenis surat">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    @foreach (['menunggu' => 'Menunggu', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'] as $key => $label)
                        <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-sm btn-hijau"><i class="bi bi-search me-1"></i> Cari</button>
                <a href="{{ route('admin.surat.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Pemohon</th>
                        <th>Jenis Surat</th>
                        <th>Keperluan</th>
                        <th>No. Surat</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($surats as $surat)
                        <tr>
                            <td>{{ $surat->id }}</td>
                            <td>
                                <div class="fw-semibold">{{ $surat->penduduk->nama ?? '-' }}</div>
                                <div class="small text-muted font-monospace">{{ $surat->penduduk->nik ?? '' }}</div>
                            </td>
                            <td class="small">{{ $surat->jenisSurat->nama ?? '-' }}</td>
                            <td class="small text-muted">{{ \Illuminate\Support\Str::limit($surat->keperluan, 30) }}</td>
                            <td class="small">{{ $surat->no_surat ?? '-' }}</td>
                            <td class="small">{{ $surat->created_at->format('d/m/Y') }}</td>
                            <td>
                                @php($badge = ['menunggu' => 'warning', 'diproses' => 'info', 'selesai' => 'success', 'ditolak' => 'danger'][$surat->status])
                                <span class="badge badge-soft-{{ $badge }}">{{ $surat->statusLabel() }}</span>
                            </td>
                            <td class="text-end text-nowrap">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalSurat{{ $surat->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                @if ($surat->status === 'selesai')
                                    <a href="{{ route('admin.surat.pdf', $surat) }}" class="btn btn-sm btn-outline-success" title="Unduh PDF"><i class="bi bi-file-earmark-pdf"></i></a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada pengajuan surat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $surats->links() }}
    </div>
</div>

@foreach ($surats as $surat)
    <div class="modal fade" id="modalSurat{{ $surat->id }}" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('admin.surat.update', $surat) }}" method="POST" class="modal-content">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Proses Surat #{{ $surat->id }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 small">
                        <div><span class="text-muted">Pemohon:</span> <strong>{{ $surat->penduduk->nama ?? '-' }}</strong></div>
                        <div><span class="text-muted">Jenis:</span> {{ $surat->jenisSurat->nama ?? '-' }}</div>
                        <div><span class="text-muted">Keperluan:</span> {{ $surat->keperluan }}</div>
                    </div>
                    @if (!empty($surat->data))
                        <div class="mb-3 p-3 rounded-3 small" style="background: var(--biru-pucat-2)">
                            <div class="fw-bold mb-2"><i class="bi bi-ui-checks me-1"></i> Isian Formulir Online</div>
                            @foreach ($surat->data as $row)
                                <div class="d-flex gap-2"><span class="text-muted" style="min-width: 40%">{{ $row['label'] ?? '' }}</span><span>{{ $row['nilai'] ?? '-' }}</span></div>
                            @endforeach
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            @foreach (['menunggu' => 'Menunggu', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'] as $key => $label)
                                <option value="{{ $key }}" @selected($surat->status === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nomor Surat</label>
                        <input type="text" name="no_surat" value="{{ $surat->no_surat }}" class="form-control" placeholder="Contoh: 470/001/DS/2026">
                    </div>
                    <div>
                        <label class="form-label small fw-semibold">Catatan Admin</label>
                        <textarea name="catatan_admin" rows="2" class="form-control" placeholder="Catatan untuk pemohon (opsional)">{{ $surat->catatan_admin }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-hijau btn-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endforeach
@endsection
