@extends('layouts.app')

@section('title', 'Kontak')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Kontak</h1>
        <p class="mb-0 opacity-75">Hubungi pemerintah {{ $profil->nama_desa }}</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-body p-4">
                    <h5 class="section-title mb-3">Informasi Kontak</h5>
                    <div class="d-flex gap-3 mb-3">
                        <div class="icon bg-success-subtle text-success rounded p-2 px-3"><i class="bi bi-geo-alt fs-5"></i></div>
                        <div>
                            <div class="fw-semibold">Alamat Kantor</div>
                            <div class="text-muted small">{{ $profil->alamat_kantor ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="d-flex gap-3 mb-3">
                        <div class="icon bg-success-subtle text-success rounded p-2 px-3"><i class="bi bi-telephone fs-5"></i></div>
                        <div>
                            <div class="fw-semibold">Telepon</div>
                            <div class="text-muted small">{{ $profil->telepon ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="d-flex gap-3 mb-3">
                        <div class="icon bg-success-subtle text-success rounded p-2 px-3"><i class="bi bi-envelope fs-5"></i></div>
                        <div>
                            <div class="fw-semibold">Email</div>
                            <div class="text-muted small">{{ $profil->email ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <div class="icon bg-success-subtle text-success rounded p-2 px-3"><i class="bi bi-clock fs-5"></i></div>
                        <div>
                            <div class="fw-semibold">Jam Pelayanan</div>
                            <div class="text-muted small">Senin - Jumat, 08.00 - 15.00 WIB</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-body p-4">
                    <h5 class="section-title mb-3">Lokasi Kantor Desa</h5>
                    @if ($profil->peta_embed)
                        <div class="ratio" style="--bs-aspect-ratio: 60%;">{!! $profil->peta_embed !!}</div>
                    @else
                        <div class="p-5 text-center text-muted bg-light rounded">
                            <i class="bi bi-map fs-1 d-block mb-2"></i>
                            Peta lokasi belum diatur.
                        </div>
                    @endif
                    <div class="alert alert-success mt-3 mb-0 small">
                        <i class="bi bi-info-circle me-1"></i>
                        Untuk pengajuan surat dan pengaduan, silakan masuk atau daftar akun terlebih dahulu melalui menu
                        <a href="{{ route('layanan') }}" class="fw-semibold">Layanan</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
