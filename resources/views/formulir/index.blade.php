@extends('layouts.app')

@section('title', 'Formulir Online')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Formulir Online</h1>
        <p class="mb-0 opacity-75">Isi formulir surat dan pendataan desa — tetap bisa diisi saat tidak ada sinyal</p>
    </div>
</div>

<div class="container py-5">
    <div class="offline-ready-note mb-4" id="offlineReady" hidden>
        <i class="bi bi-cloud-check-fill"></i>
        <span><b>Semua formulir di bawah sudah tersimpan untuk offline.</b> Anda bisa membuka dan mengisinya meski tanpa sinyal.</span>
    </div>

    <div class="row g-4">
        @forelse ($formulirs as $i => $f)
            <div class="col-md-6 col-lg-4 reveal" style="transition-delay: {{ ($i % 3) * 100 }}ms">
                <a href="{{ $f->isDinamis() ? route('formulir.show', $f->slug) : $f->link }}" class="form-tile" @unless ($f->isDinamis()) target="_blank" rel="noopener" @endunless
                   @if ($f->isDinamis()) data-offline-url="{{ route('formulir.show', $f->slug) }}" @endif>
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="form-tile-icon"><i class="bi {{ $f->isSurat() ? 'bi-envelope-paper' : ($f->isDinamis() ? 'bi-ui-checks' : 'bi-link-45deg') }}"></i></span>
                        <span class="d-flex gap-1 flex-wrap justify-content-end">
                            @if ($f->isSurat())<span class="badge badge-soft-info">Surat</span>@elseif ($f->isDinamis())<span class="badge badge-soft-success">Pendataan</span>@else<span class="badge badge-soft-secondary">Tautan luar</span>@endif
                            @if ($f->butuh_login)<span class="badge badge-soft-warning"><i class="bi bi-lock"></i> Login</span>@endif
                        </span>
                    </div>
                    <h6>{{ $f->judul }}</h6>
                    <p>{{ \Illuminate\Support\Str::limit($f->deskripsi, 110) ?: 'Isi formulir ini secara online.' }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="link-biru">{{ $f->isDinamis() ? 'Isi Formulir' : 'Buka Tautan' }} <i class="bi bi-arrow-right"></i></span>
                        @if ($f->isDinamis())<span class="offline-chip" hidden><i class="bi bi-cloud-check"></i> Offline</span>@endif
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12"><div class="alert alert-light border text-center text-muted">Belum ada formulir yang tersedia.</div></div>
        @endforelse
    </div>
</div>
@endsection
