@extends('layouts.panel')

@section('title', 'Layanan Pengaduan')
@section('subtitle', 'Sampaikan keluhan, saran, atau laporan Anda')

@section('sidebar')
    @include('partials.sidebar-user')
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-1">Formulir Pengaduan</h6>
                <p class="text-muted small mb-4">Laporan Anda akan ditindaklanjuti oleh perangkat desa. Status dapat Anda pantau pada menu riwayat pengaduan.</p>

                <form action="{{ route('user.pengaduan.store') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                            <option value="">Pilih kategori</option>
                            @foreach (['Infrastruktur', 'Pelayanan Desa', 'Kebersihan & Lingkungan', 'Keamanan', 'Sosial & Bantuan', 'Lainnya'] as $kategori)
                                <option value="{{ $kategori }}" @selected(old('kategori') === $kategori)>{{ $kategori }}</option>
                            @endforeach
                        </select>
                        @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Lokasi Kejadian</label>
                        <input type="text" name="lokasi" value="{{ old('lokasi') }}" class="form-control" placeholder="Contoh: RT 01 / RW 02">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Isi Pengaduan <span class="text-danger">*</span></label>
                        <textarea name="isi" rows="6" class="form-control @error('isi') is-invalid @enderror" placeholder="Jelaskan kronologi dan detail permasalahan..." required>{{ old('isi') }}</textarea>
                        @error('isi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 d-flex gap-2">
                        <button class="btn btn-hijau px-4"><i class="bi bi-send me-1"></i> Kirim Pengaduan</button>
                        <a href="{{ route('user.pengaduan.index') }}" class="btn btn-outline-secondary">Riwayat</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-info-circle text-success me-1"></i> Ketentuan</h6>
                <ul class="small text-muted ps-3 d-grid gap-2 mb-0">
                    <li>Isi laporan dengan jelas dan sesuai fakta.</li>
                    <li>Sertakan lokasi agar mudah ditindaklanjuti.</li>
                    <li>Pelapor akan mendapat tanggapan dari admin.</li>
                    <li>Data pelapor hanya digunakan untuk keperluan tindak lanjut.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
