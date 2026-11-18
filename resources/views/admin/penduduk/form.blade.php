@extends('layouts.panel')

@php($isEdit = $penduduk->exists)
@section('title', $isEdit ? 'Edit Penduduk' : 'Tambah Penduduk')
@section('subtitle', $isEdit ? 'Perbarui data warga' : 'Tambahkan data warga baru')

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="card">
    <div class="card-body p-4">
        <form action="{{ $isEdit ? route('admin.penduduk.update', $penduduk) : route('admin.penduduk.store') }}" method="POST" class="row g-3">
            @csrf
            @if ($isEdit) @method('PUT') @endif

            <div class="col-md-6">
                <label class="form-label small fw-semibold">NIK <span class="text-danger">*</span></label>
                <input type="text" name="nik" value="{{ old('nik', $penduduk->nik) }}" maxlength="16" class="form-control @error('nik') is-invalid @enderror" required>
                @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">No. KK</label>
                <input type="text" name="no_kk" value="{{ old('no_kk', $penduduk->no_kk) }}" maxlength="16" class="form-control @error('no_kk') is-invalid @enderror">
                @error('no_kk')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $penduduk->nama) }}" class="form-control @error('nama') is-invalid @enderror" required>
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-semibold">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $penduduk->tempat_lahir) }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $penduduk->tanggal_lahir?->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" class="form-control">
                <div class="small fw-semibold mt-1" id="umurOtomatis" style="color: var(--biru)"></div>
                <div class="form-text"><i class="bi bi-key me-1"></i>Dipakai sebagai kata sandi awal warga (format DDMMYYYY) untuk masuk dengan NIK.</div>
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                    <option value="L" @selected(old('jenis_kelamin', $penduduk->jenis_kelamin) === 'L')>Laki-laki</option>
                    <option value="P" @selected(old('jenis_kelamin', $penduduk->jenis_kelamin) === 'P')>Perempuan</option>
                </select>
                @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Agama</label>
                <input type="text" name="agama" value="{{ old('agama', $penduduk->agama) }}" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Pekerjaan</label>
                <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $penduduk->pekerjaan) }}" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-semibold">RT <span class="text-danger">*</span></label>
                <input type="text" name="rt" value="{{ old('rt', $penduduk->rt) }}" class="form-control @error('rt') is-invalid @enderror" required>
                @error('rt')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">RW <span class="text-danger">*</span></label>
                <input type="text" name="rw" value="{{ old('rw', $penduduk->rw) }}" class="form-control @error('rw') is-invalid @enderror" required>
                @error('rw')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Dusun</label>
                <input type="text" name="dusun" value="{{ old('dusun', $penduduk->dusun) }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Status Keluarga <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="Kepala Keluarga" @selected(old('status', $penduduk->status) === 'Kepala Keluarga')>Kepala Keluarga</option>
                    <option value="Anggota" @selected(old('status', $penduduk->status) === 'Anggota')>Anggota</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label class="form-label small fw-semibold">Alamat</label>
                <textarea name="alamat" rows="2" class="form-control">{{ old('alamat', $penduduk->alamat) }}</textarea>
            </div>

            <div class="col-12 d-flex gap-2 mt-2">
                <button class="btn btn-hijau px-4"><i class="bi bi-save me-1"></i> Simpan</button>
                <a href="{{ route('admin.penduduk.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Pratinjau umur otomatis dari tanggal lahir (umur di sistem selalu dihitung ulang sendiri setiap hari)
    (function () {
        var input = document.getElementById('tanggal_lahir'), out = document.getElementById('umurOtomatis');
        function hitung() {
            if (!input.value) { out.textContent = ''; return; }
            var lahir = new Date(input.value + 'T00:00:00'), kini = new Date();
            var th = kini.getFullYear() - lahir.getFullYear();
            if (kini.getMonth() < lahir.getMonth() || (kini.getMonth() === lahir.getMonth() && kini.getDate() < lahir.getDate())) th--;
            if (th < 0) { out.textContent = 'Tanggal lahir tidak boleh di masa depan'; return; }
            var teks = th + ' tahun';
            if (th === 0) { var bl = (kini.getFullYear() - lahir.getFullYear()) * 12 + kini.getMonth() - lahir.getMonth() - (kini.getDate() < lahir.getDate() ? 1 : 0); teks = Math.max(0, bl) + ' bulan'; }
            var kel = th < 5 ? 'Balita' : th < 12 ? 'Anak' : th < 18 ? 'Remaja' : th < 60 ? 'Dewasa' : 'Lansia';
            out.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Umur: ' + teks + ' (' + kel + ')';
        }
        input.addEventListener('input', hitung); input.addEventListener('change', hitung); hitung();
    })();
</script>
@endpush
