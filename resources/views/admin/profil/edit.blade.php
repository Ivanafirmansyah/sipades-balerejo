@extends('layouts.panel')

@section('title', 'Profil & Peta Desa')
@section('subtitle', 'Kelola identitas desa, kontak, dan peta lokasi')

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data" class="row g-3">
            @csrf
            @method('PUT')

            <div class="col-12"><h6 class="fw-bold text-success">Identitas Desa</h6><hr class="mt-1"></div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Nama Desa <span class="text-danger">*</span></label>
                <input type="text" name="nama_desa" value="{{ old('nama_desa', $profil->nama_desa) }}" class="form-control @error('nama_desa') is-invalid @enderror" required>
                @error('nama_desa')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Logo Desa</label>
                <div class="form-text mt-0 mb-1">Maks 10MB — otomatis dikompres</div>
                <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @if ($profil->logo)
                    <img src="{{ asset('storage/' . $profil->logo) }}" class="mt-2 bg-light rounded p-1" height="64" alt="Logo">
                @endif
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Kecamatan</label>
                <input type="text" name="kecamatan" value="{{ old('kecamatan', $profil->kecamatan) }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Kabupaten</label>
                <input type="text" name="kabupaten" value="{{ old('kabupaten', $profil->kabupaten) }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Provinsi</label>
                <input type="text" name="provinsi" value="{{ old('provinsi', $profil->provinsi) }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Kode Pos</label>
                <input type="text" name="kode_pos" value="{{ old('kode_pos', $profil->kode_pos) }}" class="form-control">
            </div>

            <div class="col-12">
                <label class="form-label small fw-semibold">Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3" class="form-control">{{ old('deskripsi', $profil->deskripsi) }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Sejarah Desa</label>
                <textarea name="sejarah" rows="5" class="form-control">{{ old('sejarah', $profil->sejarah) }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Misi</label>
                <textarea name="misi" rows="5" class="form-control">{{ old('misi', $profil->misi) }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Visi</label>
                <textarea name="visi" rows="2" class="form-control">{{ old('visi', $profil->visi) }}</textarea>
            </div>

            <div class="col-12 mt-4"><h6 class="fw-bold text-success">Kontak & Peta</h6><hr class="mt-1"></div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Alamat Kantor</label>
                <input type="text" name="alamat_kantor" value="{{ old('alamat_kantor', $profil->alamat_kantor) }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Telepon</label>
                <input type="text" name="telepon" value="{{ old('telepon', $profil->telepon) }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Email</label>
                <input type="email" name="email" value="{{ old('email', $profil->email) }}" class="form-control">
            </div>
            <div class="col-12">
                <label class="form-label small fw-semibold">Embed Peta (Google Maps iframe)</label>
                <textarea name="peta_embed" rows="4" class="form-control font-monospace" style="font-size: .8rem;" placeholder='<iframe src="https://www.google.com/maps/embed?..." ...></iframe>'>{{ old('peta_embed', $profil->peta_embed) }}</textarea>
                <div class="form-text">Buka Google Maps &rarr; Bagikan &rarr; Sematkan peta &rarr; salin kode iframe dan tempel di sini.</div>
            </div>

            <div class="col-12 d-flex gap-2 mt-3">
                <button class="btn btn-hijau px-4"><i class="bi bi-save me-1"></i> Simpan Profil</button>
                <a href="{{ route('profil') }}" target="_blank" class="btn btn-outline-secondary">Lihat Halaman Publik</a>
            </div>
        </form>
    </div>
</div>
@endsection
