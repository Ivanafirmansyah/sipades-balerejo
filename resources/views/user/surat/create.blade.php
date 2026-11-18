@extends('layouts.panel')

@section('title', 'Pengajuan Surat')
@section('subtitle', 'Isi formulir pengajuan surat keterangan')

@section('sidebar')
    @include('partials.sidebar-user')
@endsection

@section('content')
<div class="card">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-1">Formulir Pengajuan Surat</h6>
        <p class="text-muted small mb-4">Pastikan data yang Anda isi sesuai dengan KTP. Pengajuan akan diproses oleh perangkat desa.</p>

        <form action="{{ route('user.surat.store') }}" method="POST" class="row g-3">
            @csrf

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Jenis Surat <span class="text-danger">*</span></label>
                <select name="jenis_surat_id" class="form-select @error('jenis_surat_id') is-invalid @enderror" required>
                    <option value="">Pilih jenis surat</option>
                    @foreach ($jenisSurats as $jenis)
                        <option value="{{ $jenis->id }}" @selected(old('jenis_surat_id') == $jenis->id)>{{ $jenis->nama }}</option>
                    @endforeach
                </select>
                @error('jenis_surat_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Keperluan <span class="text-danger">*</span></label>
                <input type="text" name="keperluan" value="{{ old('keperluan') }}" class="form-control @error('keperluan') is-invalid @enderror" placeholder="Contoh: keperluan administrasi bank" required>
                @error('keperluan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12"><hr class="my-1"><div class="fw-semibold small text-muted">DATA PEMOHON</div></div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">NIK <span class="text-danger">*</span></label>
                <input type="text" name="nik" value="{{ old('nik', $penduduk->nik ?? '') }}" maxlength="16" class="form-control @error('nik') is-invalid @enderror" placeholder="16 digit NIK" required>
                @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $penduduk->nama ?? auth()->user()->name) }}" class="form-control @error('nama') is-invalid @enderror" required>
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $penduduk->tempat_lahir ?? '') }}" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', isset($penduduk->tanggal_lahir) ? $penduduk->tanggal_lahir->format('Y-m-d') : '') }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                    <option value="L" @selected(old('jenis_kelamin', $penduduk->jenis_kelamin ?? '') === 'L')>Laki-laki</option>
                    <option value="P" @selected(old('jenis_kelamin', $penduduk->jenis_kelamin ?? '') === 'P')>Perempuan</option>
                </select>
                @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-semibold">Agama</label>
                <input type="text" name="agama" value="{{ old('agama', $penduduk->agama ?? '') }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-semibold">Pekerjaan</label>
                <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $penduduk->pekerjaan ?? '') }}" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-semibold">RT <span class="text-danger">*</span></label>
                <input type="text" name="rt" value="{{ old('rt', $penduduk->rt ?? '') }}" class="form-control @error('rt') is-invalid @enderror" required>
                @error('rt')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-semibold">RW <span class="text-danger">*</span></label>
                <input type="text" name="rw" value="{{ old('rw', $penduduk->rw ?? '') }}" class="form-control @error('rw') is-invalid @enderror" required>
                @error('rw')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Dusun</label>
                <input type="text" name="dusun" value="{{ old('dusun', $penduduk->dusun ?? '') }}" class="form-control">
            </div>

            <div class="col-12">
                <label class="form-label small fw-semibold">Alamat</label>
                <textarea name="alamat" rows="2" class="form-control">{{ old('alamat', $penduduk->alamat ?? '') }}</textarea>
            </div>

            <div class="col-12 d-flex gap-2 mt-2">
                <button class="btn btn-hijau px-4"><i class="bi bi-send me-1"></i> Kirim Pengajuan</button>
                <a href="{{ route('user.surat.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
