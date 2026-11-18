@extends('layouts.app')

@section('title', $berita->judul)

@section('content')
<div class="page-header">
    <div class="container">
        <span class="badge badge-soft-success text-capitalize mb-2">{{ $berita->kategori }}</span>
        <h1>{{ $berita->judul }}</h1>
        <p class="mb-0 opacity-75">
            <i class="bi bi-person me-1"></i>{{ $berita->penulis ?? 'Admin Desa' }}
            &middot; <i class="bi bi-calendar3 me-1"></i>{{ ($berita->published_at ?? $berita->created_at)->translatedFormat('d F Y') }}
        </p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    @if ($berita->gambar)
                        <img src="{{ asset('storage/' . $berita->gambar) }}" class="img-fluid rounded mb-4 w-100" style="max-height: 420px; object-fit: cover;" alt="{{ $berita->judul }}">
                    @endif
                    <div style="white-space: pre-line; text-align: justify;" class="text-muted">{{ $berita->isi }}</div>
                    <a href="{{ route('berita.index') }}" class="btn btn-outline-success btn-sm mt-4"><i class="bi bi-arrow-left me-1"></i> Kembali ke daftar</a>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Berita Lainnya</h6>
                    <div class="d-grid gap-3">
                        @forelse ($lainnya as $item)
                            <a href="{{ route('berita.show', $item->slug) }}" class="text-decoration-none text-dark d-flex gap-2">
                                @if ($item->gambar)
                                    <img src="{{ asset('storage/' . $item->gambar) }}" width="70" height="60" class="rounded object-fit-cover" style="object-fit: cover;" alt="">
                                @else
                                    <div class="bg-success-subtle rounded d-grid" style="width: 70px; height: 60px; place-items: center;"><i class="bi bi-newspaper text-success"></i></div>
                                @endif
                                <div>
                                    <div class="small fw-semibold">{{ \Illuminate\Support\Str::limit($item->judul, 60) }}</div>
                                    <div class="small text-muted">{{ $item->created_at->format('d/m/Y') }}</div>
                                </div>
                            </a>
                        @empty
                            <div class="small text-muted">Belum ada berita lainnya.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
