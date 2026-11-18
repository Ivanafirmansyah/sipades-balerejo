@extends('layouts.panel')

@section('title', 'Berita & Pengumuman')
@section('subtitle', 'Kelola berita dan pengumuman desa')

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h6 class="fw-bold mb-0">Daftar Berita</h6>
            <a href="{{ route('admin.berita.create') }}" class="btn btn-sm btn-hijau"><i class="bi bi-plus-lg me-1"></i> Tambah Berita</a>
        </div>

        <form action="{{ route('admin.berita.index') }}" method="GET" class="row g-2 mb-3">
            <div class="col-md-5">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Cari judul...">
            </div>
            <div class="col-md-3">
                <select name="kategori" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    <option value="berita" @selected(request('kategori') === 'berita')>Berita</option>
                    <option value="pengumuman" @selected(request('kategori') === 'pengumuman')>Pengumuman</option>
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-sm btn-hijau w-100">Filter</button></div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr><th>Judul</th><th>Kategori</th><th>Penulis</th><th>Tanggal</th><th>Status</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($beritas as $berita)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if ($berita->gambar)
                                        <img src="{{ asset('storage/' . $berita->gambar) }}" width="56" height="42" class="rounded" style="object-fit: cover;" alt="">
                                    @else
                                        <div class="bg-success-subtle rounded d-grid" style="width: 56px; height: 42px; place-items: center;"><i class="bi bi-newspaper text-success"></i></div>
                                    @endif
                                    <div class="fw-semibold">{{ \Illuminate\Support\Str::limit($berita->judul, 50) }}</div>
                                </div>
                            </td>
                            <td><span class="badge badge-soft-success text-capitalize">{{ $berita->kategori }}</span></td>
                            <td class="small">{{ $berita->penulis ?? '-' }}</td>
                            <td class="small">{{ $berita->published_at?->format('d/m/Y') ?? '-' }}</td>
                            <td>
                                <span class="badge badge-soft-{{ $berita->is_published ? 'success' : 'secondary' }}">
                                    {{ $berita->is_published ? 'Terbit' : 'Draft' }}
                                </span>
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('berita.show', $berita->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.berita.edit', $berita) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.berita.destroy', $berita) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus berita ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada berita.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $beritas->links() }}
    </div>
</div>
@endsection
