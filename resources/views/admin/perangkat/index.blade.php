@extends('layouts.panel')

@section('title', 'Perangkat Desa')
@section('subtitle', 'Kelola struktur dan perangkat desa')

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Daftar Perangkat</h6>
            <a href="{{ route('admin.perangkat.create') }}" class="btn btn-sm btn-hijau"><i class="bi bi-plus-lg me-1"></i> Tambah Perangkat</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr><th>Nama</th><th>Jabatan</th><th>Atasan Langsung</th><th>Keterangan</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($perangkats as $perangkat)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if ($perangkat->foto)
                                        <img src="{{ asset('storage/' . $perangkat->foto) }}" width="46" height="46" class="rounded-circle" style="object-fit: cover;" alt="">
                                    @else
                                        <span class="avatar-inisial">{{ strtoupper(substr($perangkat->nama, 0, 1)) }}</span>
                                    @endif
                                    <span class="fw-semibold">{{ $perangkat->nama }}</span>
                                </div>
                            </td>
                            <td class="small">{{ $perangkat->jabatan }}</td>
                            <td class="small">
                                @if ($perangkat->is_mitra)<span class="badge badge-soft-info">Lembaga mitra</span>
                                @elseif ($perangkat->atasan){{ $perangkat->atasan->jabatan }}
                                @else<span class="badge badge-soft-warning"><i class="bi bi-star-fill"></i> Puncak struktur</span>@endif
                            </td>
                            <td class="small text-muted">{{ $perangkat->keterangan ?? '-' }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.perangkat.edit', $perangkat) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.perangkat.destroy', $perangkat) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus perangkat ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data perangkat desa.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
