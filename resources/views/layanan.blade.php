@extends('layouts.app')

@section('title', 'Layanan')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Layanan Desa</h1>
        <p class="mb-0 opacity-75">Surat menyurat dan pengaduan masyarakat</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card mb-4">
                <div class="card-body p-4">
                    <h5 class="section-title mb-3"><i class="bi bi-file-earmark-text me-2"></i>Surat Menyurat</h5>
                    <p class="text-muted">Ajukan surat keterangan secara online. Beberapa jenis surat yang tersedia:</p>
                    <div class="row g-2">
                        @forelse ($jenisSurats as $jenis)
                            <div class="col-md-6">
                                <div class="border rounded p-2 small">
                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                    <span class="fw-semibold">{{ $jenis->nama }}</span>
                                    @if ($jenis->keterangan)
                                        <div class="text-muted ms-4">{{ $jenis->keterangan }}</div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-12 small text-muted">Jenis surat belum tersedia.</div>
                        @endforelse
                    </div>
                    <div class="mt-4">
                        @auth
                            <a href="{{ route('user.surat.create') }}" class="btn btn-hijau"><i class="bi bi-send me-1"></i> Ajukan Surat Sekarang</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-hijau"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk untuk Mengajukan Surat</a>
                            <a href="{{ route('register') }}" class="btn btn-outline-success">Daftar Akun</a>
                        @endauth
                    </div>
                </div>
            </div>

            @if ($formulirs->count())
                <div class="card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="section-title mb-0"><i class="bi bi-journal-text me-2"></i>Formulir Online</h5>
                            <a href="{{ route('formulir.index') }}" class="small fw-semibold">Lihat semua</a>
                        </div>
                        <div class="d-grid gap-2">
                            @foreach ($formulirs as $formulir)
                                <div class="border rounded p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div>
                                        <div class="fw-semibold small">{{ $formulir->judul }}</div>
                                        @if ($formulir->deskripsi)
                                            <div class="small text-muted">{{ $formulir->deskripsi }}</div>
                                        @endif
                                    </div>
                                    @if ($formulir->isDinamis())
                                        <a href="{{ route('formulir.show', $formulir->slug) }}" class="btn btn-sm btn-biru">Isi <i class="bi bi-arrow-right ms-1"></i></a>
                                    @elseif ($formulir->link)
                                        <a href="{{ $formulir->link }}" target="_blank" class="btn btn-sm btn-outline-success">Buka <i class="bi bi-box-arrow-up-right ms-1"></i></a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-5" id="pengaduan">
            <div class="card mb-4">
                <div class="card-body p-4">
                    <h5 class="section-title mb-3"><i class="bi bi-megaphone me-2"></i>Layanan Pengaduan</h5>
                    <p class="text-muted small">Sampaikan keluhan, saran, atau laporan terkait pelayanan dan fasilitas desa. Pengaduan akan ditindaklanjuti oleh perangkat desa.</p>
                    <ul class="small text-muted ps-3">
                        <li>Laporan dapat dipantau statusnya.</li>
                        <li>Identitas pelapor hanya dilihat admin.</li>
                        <li>Mohon sampaikan informasi yang benar.</li>
                    </ul>
                    @auth
                        <a href="{{ route('user.pengaduan.create') }}" class="btn btn-hijau"><i class="bi bi-pencil-square me-1"></i> Buat Pengaduan</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-hijau"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk untuk Melapor</a>
                    @endauth
                </div>
            </div>

            <div class="card">
                <div class="card-body p-4">
                    <h5 class="section-title mb-3"><i class="bi bi-info-circle me-2"></i>Alur Pengajuan Surat</h5>
                    <ol class="small text-muted ps-3 mb-0 d-grid gap-2">
                        <li>Daftar / masuk ke akun Anda.</li>
                        <li>Pilih jenis surat dan isi data pemohon.</li>
                        <li>Pantau status pada menu Riwayat Surat.</li>
                        <li>Unduh surat (PDF) setelah status selesai.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
