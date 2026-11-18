@extends('layouts.panel')

@section('title', 'Formulir')
@section('subtitle', 'Buat formulir surat & pendataan sendiri lewat menu Formulir Baru')

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h6 class="fw-bold mb-0">Daftar Formulir</h6>
            <a href="{{ route('admin.formulir.create') }}" class="btn btn-sm btn-biru"><i class="bi bi-plus-lg me-1"></i> Buat Formulir</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr><th>Judul</th><th>Jenis</th><th>Isian</th><th>Masuk</th><th>Status</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($formulirs as $formulir)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $formulir->judul }}</div>
                                <div class="small text-muted">{{ \Illuminate\Support\Str::limit($formulir->deskripsi, 70) }}</div>
                            </td>
                            <td class="small text-nowrap">
                                @if ($formulir->isDinamis())
                                    <span class="badge badge-soft-info"><i class="bi bi-ui-checks me-1"></i>Dinamis</span>
                                    <div class="text-muted mt-1">{{ $formulir->isSurat() ? 'Surat: ' . ($formulir->jenisSurat->nama ?? '-') : 'Pendataan' }}</div>
                                @else
                                    <span class="badge badge-soft-secondary"><i class="bi bi-link-45deg me-1"></i>Tautan</span>
                                @endif
                            </td>
                            <td class="small">{{ $formulir->isDinamis() ? count($formulir->fields()) . ' isian' : '-' }}</td>
                            <td>
                                @if ($formulir->isDinamis())
                                    <a href="{{ route('admin.formulir.jawaban', $formulir) }}" class="fw-bold text-decoration-none">{{ $formulir->jawabans_count }} <span class="small fw-normal">data</span></a>
                                @else - @endif
                            </td>
                            <td>
                                <span class="badge badge-soft-{{ $formulir->is_aktif ? 'success' : 'secondary' }}">{{ $formulir->is_aktif ? 'Aktif' : 'Nonaktif' }}</span>
                                @if ($formulir->butuh_login)<span class="badge badge-soft-warning"><i class="bi bi-lock"></i> Login</span>@endif
                            </td>
                            <td class="text-end text-nowrap">
                                @if ($formulir->isDinamis())
                                    <a href="{{ route('formulir.show', $formulir->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Buka formulir"><i class="bi bi-box-arrow-up-right"></i></a>
                                    <a href="{{ route('admin.formulir.jawaban', $formulir) }}" class="btn btn-sm btn-outline-secondary" title="Lihat isian"><i class="bi bi-inbox"></i></a>
                                @elseif ($formulir->link)
                                    <a href="{{ $formulir->link }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Buka tautan"><i class="bi bi-box-arrow-up-right"></i></a>
                                @endif
                                <a href="{{ route('admin.formulir.edit', $formulir) }}" class="btn btn-sm btn-outline-primary" title="Ubah"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.formulir.destroy', $formulir) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus formulir ini beserta semua isiannya?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada formulir.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $formulirs->links() }}
    </div>
</div>
@endsection
