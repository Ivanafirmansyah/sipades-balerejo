@extends('layouts.app')

@section('title', 'Berita dan Pengumuman')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Berita & Pengumuman</h1>
        <p class="mb-0 opacity-75">Informasi terbaru seputar kegiatan dan pengumuman desa</p>
    </div>
</div>

<div class="container py-5">
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ route('berita.index') }}" class="btn btn-sm {{ !$kategori ? 'btn-hijau' : 'btn-outline-secondary' }}">Semua</a>
        <a href="{{ route('berita.index', ['kategori' => 'berita']) }}" class="btn btn-sm {{ $kategori === 'berita' ? 'btn-hijau' : 'btn-outline-secondary' }}">Berita</a>
        <a href="{{ route('berita.index', ['kategori' => 'pengumuman']) }}" class="btn btn-sm {{ $kategori === 'pengumuman' ? 'btn-hijau' : 'btn-outline-secondary' }}">Pengumuman</a>
    </div>

    <div class="row g-4">
        @forelse ($beritas as $berita)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('berita.show', $berita->slug) }}" class="card h-100 text-decoration-none text-dark">
                    @if ($berita->gambar)
                        <img src="{{ asset('storage/' . $berita->gambar) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $berita->judul }}">
                    @else
                        <div class="bg-success-subtle d-grid" style="height: 200px; place-items: center;">
                            <i class="bi bi-newspaper fs-1 text-success"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <span class="badge badge-soft-success mb-2 text-capitalize">{{ $berita->kategori }}</span>
                        <h6 class="fw-bold">{{ $berita->judul }}</h6>
                        <p class="small text-muted mb-0">{{ \Illuminate\Support\Str::limit(strip_tags($berita->isi), 110) }}</p>
                    </div>
                    <div class="card-footer bg-white border-0 small text-muted">
                        <i class="bi bi-calendar3 me-1"></i>{{ ($berita->published_at ?? $berita->created_at)->translatedFormat('d F Y') }}
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12"><div class="alert alert-light border text-center text-muted">Belum ada berita pada kategori ini.</div></div>
        @endforelse
    </div>

    <div class="mt-4">{{ $beritas->links() }}</div>
</div>
@endsection
