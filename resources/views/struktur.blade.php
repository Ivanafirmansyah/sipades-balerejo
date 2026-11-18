@extends('layouts.app')

@section('title', 'Struktur Organisasi')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Struktur Organisasi</h1>
        <p class="mb-0 opacity-75">Susunan pemerintahan {{ $profil->nama_desa }}</p>
    </div>
</div>

<div class="container py-5">
    @if ($akar->isEmpty() && $mitra->isEmpty())
        <div class="alert alert-light border text-center text-muted">Data perangkat desa belum tersedia.</div>
    @else
        <div class="card">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <span class="small text-muted"><i class="bi bi-people me-1"></i>{{ $jumlah }} anggota</span>
                    <div class="pohon-legenda small">
                        <span><i class="garis-utuh"></i> Garis komando (atasan langsung)</span>
                        @if ($mitra->isNotEmpty())<span><i class="garis-putus"></i> Garis koordinasi (lembaga mitra)</span>@endif
                    </div>
                </div>

                <div class="pohon" id="pohon">
                    <ul>
                        @foreach ($akar as $i => $p)
                            @include('struktur._simpul', ['p' => $p, 'level' => 0, 'mitra' => $i === 0 ? $mitra : collect()])
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <p class="small text-muted text-center mt-3 mb-0">
            Susunan mengacu pada Permendagri No. 84 Tahun 2015 tentang Susunan Organisasi dan Tata Kerja Pemerintah Desa.
        </p>
    @endif
</div>
@endsection
