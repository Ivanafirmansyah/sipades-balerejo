@extends('layouts.app')

@section('title', $formulir->judul)

@section('content')
<div class="page-header">
    <div class="container">
        <h1>{{ $formulir->judul }}</h1>
        <p class="mb-0 opacity-75">{{ $formulir->isSurat() ? 'Formulir pengajuan ' . ($formulir->jenisSurat->nama ?? 'surat') : 'Formulir online ' . ($profil->nama_desa ?? 'desa') }}</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4 justify-content-center">
        <div class="col-lg-8">
            <div class="offline-ready-note mb-3" id="offlineReady" hidden>
                <i class="bi bi-cloud-check-fill"></i>
                <span><b>Bisa diisi tanpa sinyal.</b> Jika koneksi terputus, isian disimpan di perangkat ini lalu terkirim otomatis saat sinyal kembali.</span>
            </div>

            {{-- Konflik koreksi (diisi oleh pwa.js) --}}
            <div class="konflik-card mb-3" id="konflikBox" hidden></div>

            {{-- Isian yang pernah dikirim (dari perangkat ini / akun ini) — bisa dikoreksi --}}
            <div class="card isian-saya mb-3" id="isianSaya" hidden>
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-1"></i> Isian Anda</h6>
                        <span class="small text-muted">Salah isi? Koreksi selama belum diproses petugas.</span>
                    </div>
                    <ul class="isian-list" id="isianList"></ul>
                </div>
            </div>
            <script type="application/json" id="isianSayaData">@json($isianSaya)</script>

            <div class="card">
                <div class="card-body p-4 p-md-5">
                    @if ($formulir->deskripsi)
                        <p class="text-muted">{{ $formulir->deskripsi }}</p>
                    @endif

                    @if ($formulir->butuh_login && !auth()->check())
                        <div class="alert alert-warning d-flex gap-2 align-items-start">
                            <i class="bi bi-lock-fill"></i>
                            <div>Formulir ini memerlukan akun warga. <a href="{{ route('login') }}" class="fw-bold">Masuk</a> atau <a href="{{ route('register') }}" class="fw-bold">daftar</a> terlebih dahulu.</div>
                        </div>
                    @endif

                    <div class="form-success" id="formSuccess" hidden>
                        <div class="success-icon"><i class="bi bi-check-lg"></i></div>
                        <h4 class="fw-bold" id="successTitle">Terkirim!</h4>
                        <p class="text-muted mb-4" id="successText"></p>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <button type="button" class="btn btn-kuning rounded-pill px-4" id="koreksiBaru" hidden><i class="bi bi-pencil-square me-1"></i> Koreksi Isian Ini</button>
                            <button type="button" class="btn btn-outline-biru rounded-pill px-4" id="isiLagi"><i class="bi bi-arrow-repeat me-1"></i> Isi Lagi</button>
                        </div>
                    </div>

                    <form action="{{ route('formulir.kirim', $formulir->slug) }}" method="POST" class="row g-3 dyn-form" id="dynForm" novalidate
                          data-offline-form
                          data-slug="{{ $formulir->slug }}"
                          data-judul="{{ $formulir->judul }}"
                          data-pesan="{{ $formulir->pesan_sukses ?: 'Terima kasih, isian Anda sudah kami terima.' }}"
                          data-url-ubah="{{ route('formulir.ubah', $formulir->slug) }}"
                          data-url-isian="{{ url('/formulir/' . $formulir->slug . '/isian') }}"
                          @if ($formulir->butuh_login && !auth()->check()) data-locked @endif>
                        @csrf
                        <div class="col-12" id="modeKoreksi" hidden>
                            <div class="mode-koreksi">
                                <i class="bi bi-pencil-square"></i>
                                <div class="flex-grow-1"><b>Mode koreksi</b> — <span id="modeKoreksiInfo"></span><div class="small">Ubah isian yang salah lalu simpan. Data lama tetap tersimpan sebagai riwayat.</div></div>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="batalKoreksi">Batal</button>
                            </div>
                        </div>
                        <div class="col-12"><div class="alert alert-danger small mb-0" id="formAlert" hidden></div></div>

                        @foreach ($formulir->schema['fields'] ?? [] as $f)
                            @include('formulir._field', ['f' => $f])
                        @endforeach

                        <div class="col-12 d-flex flex-wrap align-items-center gap-3 mt-4">
                            <button class="btn btn-kuning rounded-pill px-4 py-2" id="btnKirim" @if ($formulir->butuh_login && !auth()->check()) disabled @endif>
                                <span class="label-kirim"><i class="bi bi-send-fill me-1"></i> Kirim Formulir</span>
                                <span class="label-proses" hidden><span class="spinner-border spinner-border-sm me-1"></span> Mengirim...</span>
                            </button>
                            <span class="small text-muted" id="draftInfo"><i class="bi bi-floppy me-1"></i>Isian otomatis disimpan sementara di perangkat ini</span>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('formulir.index') }}" class="small text-decoration-none"><i class="bi bi-arrow-left me-1"></i> Semua formulir</a>
            </div>
        </div>
    </div>
</div>
@endsection
