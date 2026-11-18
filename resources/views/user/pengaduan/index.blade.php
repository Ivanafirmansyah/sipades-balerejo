@extends('layouts.panel')

@section('title', 'Riwayat Pengaduan')
@section('subtitle', 'Daftar pengaduan dan tanggapan')

@section('sidebar')
    @include('partials.sidebar-user')
@endsection

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('user.pengaduan.create') }}" class="btn btn-hijau btn-sm"><i class="bi bi-plus-lg me-1"></i> Buat Pengaduan</a>
</div>

<div class="row g-3">
    @forelse ($pengaduans as $pengaduan)
        <div class="col-12">
            <div class="card">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between gap-2">
                        <div>
                            <span class="badge badge-soft-secondary">{{ $pengaduan->kategori }}</span>
                            @php($badge = ['baru' => 'warning', 'diproses' => 'info', 'selesai' => 'success'][$pengaduan->status])
                            <span class="badge badge-soft-{{ $badge }}">{{ $pengaduan->statusLabel() }}</span>
                        </div>
                        <div class="small text-muted">{{ $pengaduan->created_at->translatedFormat('d F Y, H:i') }}</div>
                    </div>
                    <p class="mt-2 mb-1">{{ $pengaduan->isi }}</p>
                    @if ($pengaduan->lokasi)
                        <div class="small text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $pengaduan->lokasi }}</div>
                    @endif
                    @if ($pengaduan->tanggapan)
                        <div class="alert alert-success mt-3 mb-0 small">
                            <div class="fw-semibold mb-1"><i class="bi bi-reply-fill me-1"></i>Tanggapan Admin</div>
                            {{ $pengaduan->tanggapan }}
                            <div class="text-muted mt-1">{{ $pengaduan->tanggapan_at?->translatedFormat('d F Y, H:i') }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-light border text-center text-muted">Belum ada pengaduan. <a href="{{ route('user.pengaduan.create') }}">Buat pengaduan</a>.</div>
        </div>
    @endforelse
</div>

<div class="mt-3">{{ $pengaduans->links() }}</div>
@endsection
