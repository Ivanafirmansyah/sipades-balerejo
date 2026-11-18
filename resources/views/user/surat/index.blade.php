@extends('layouts.panel')

@section('title', 'Riwayat Surat')
@section('subtitle', 'Pantau status pengajuan surat Anda')

@section('sidebar')
    @include('partials.sidebar-user')
@endsection

@section('content')
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h6 class="fw-bold mb-0">Daftar Pengajuan Surat</h6>
            <form action="{{ route('user.surat.index') }}" method="GET" class="d-flex gap-2">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    @foreach (['menunggu' => 'Menunggu', 'diproses' => 'Diproses', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak'] as $key => $label)
                        <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <a href="{{ route('user.surat.create') }}" class="btn btn-sm btn-hijau text-nowrap"><i class="bi bi-plus-lg me-1"></i> Ajukan</a>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
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
                            <td class="fw-semibold">{{ $surat->jenisSurat->nama ?? '-' }}</td>
                            <td class="small text-muted">{{ \Illuminate\Support\Str::limit($surat->keperluan, 35) }}</td>
                            <td>{{ $surat->no_surat ?? '-' }}</td>
                            <td class="small">{{ $surat->created_at->format('d/m/Y') }}</td>
                            <td>
                                @php($badge = ['menunggu' => 'warning', 'diproses' => 'info', 'selesai' => 'success', 'ditolak' => 'danger'][$surat->status])
                                <span class="badge badge-soft-{{ $badge }}">{{ $surat->statusLabel() }}</span>
                                @if ($surat->catatan_admin)
                                    <div class="small text-muted mt-1"><i class="bi bi-chat-left-text me-1"></i>{{ $surat->catatan_admin }}</div>
                                @endif
                            </td>
                            <td class="text-end">
                                @if ($surat->status === 'selesai')
                                    <a href="{{ route('user.surat.pdf', $surat) }}" class="btn btn-sm btn-outline-success" title="Unduh PDF">
                                        <i class="bi bi-download"></i> PDF
                                    </a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada pengajuan surat. <a href="{{ route('user.surat.create') }}">Ajukan sekarang</a>.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $surats->links() }}
    </div>
</div>
@endsection
