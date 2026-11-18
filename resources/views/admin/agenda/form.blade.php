@extends('layouts.panel')

@php($isEdit = $agenda->exists)
@section('title', $isEdit ? 'Edit Agenda' : 'Tambah Agenda')
@section('subtitle', $isEdit ? 'Perbarui agenda kegiatan' : 'Buat agenda kegiatan baru')

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="card">
    <div class="card-body p-4">
        <form action="{{ $isEdit ? route('admin.agenda.update', $agenda) : route('admin.agenda.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
            @csrf
            @if ($isEdit) @method('PUT') @endif

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Judul Kegiatan <span class="text-danger">*</span></label>
                <input type="text" name="judul" value="{{ old('judul', $agenda->judul) }}" class="form-control @error('judul') is-invalid @enderror" required>
                @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Tanggal <span class="text-danger">*</span></label>
                <input type="date" name="tanggal" value="{{ old('tanggal', $agenda->tanggal?->format('Y-m-d')) }}" class="form-control @error('tanggal') is-invalid @enderror" required>
                @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Jam</label>
                <input type="time" name="jam" value="{{ old('jam', $agenda->jam_formatted) }}" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Lokasi</label>
                <input type="text" name="lokasi" value="{{ old('lokasi', $agenda->lokasi) }}" class="form-control" placeholder="Contoh: Balai Desa">
            </div>

            <div class="col-12">
                <label class="form-label small fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" rows="4" class="form-control">{{ old('deskripsi', $agenda->deskripsi) }}</textarea>
            </div>

            {{-- Foto kegiatan --}}
            <div class="col-12">
                <label class="form-label small fw-semibold">Foto Kegiatan <span class="text-muted fw-normal">(opsional, maks 10MB — otomatis dikompres)</span></label>
                <label class="foto-drop @error('foto') is-invalid @enderror" for="fotoInput" id="fotoDrop">
                    <img id="fotoPreview" src="{{ $agenda->foto_url }}" alt="Pratinjau foto" @unless ($agenda->foto) hidden @endunless>
                    <span class="foto-drop-kosong" id="fotoKosong" @if ($agenda->foto) hidden @endif>
                        <i class="bi bi-image"></i>
                        <b>Klik untuk memilih foto</b>
                        <small>atau seret foto ke sini · JPG, PNG, WebP</small>
                    </span>
                </label>
                <input type="file" name="foto" id="fotoInput" accept="image/*" class="d-none">
                @error('foto')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                <div class="d-flex flex-wrap gap-3 align-items-center mt-2">
                    <span class="small text-muted" id="fotoInfo">{{ $agenda->foto ? 'Foto saat ini. Pilih foto lain untuk mengganti.' : '' }}</span>
                    @if ($agenda->foto)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="hapus_foto" value="1" id="hapusFoto">
                            <label class="form-check-label small text-danger" for="hapusFoto">Hapus foto</label>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-12 d-flex gap-2 mt-2">
                <button class="btn btn-hijau px-4"><i class="bi bi-save me-1"></i> Simpan</button>
                <a href="{{ route('admin.agenda.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Pratinjau foto sebelum diunggah + seret-lepas
    (function () {
        var input = document.getElementById('fotoInput'), drop = document.getElementById('fotoDrop');
        var img = document.getElementById('fotoPreview'), kosong = document.getElementById('fotoKosong'), info = document.getElementById('fotoInfo');
        var hapus = document.getElementById('hapusFoto');
        function tampil(file) {
            if (!file || !file.type.startsWith('image/')) return;
            img.src = URL.createObjectURL(file); img.hidden = false; kosong.hidden = true;
            info.textContent = file.name + ' · ' + (file.size / 1048576).toFixed(1) + ' MB (akan dikompres saat disimpan)';
            if (hapus) hapus.checked = false;
        }
        input.addEventListener('change', function () { tampil(input.files[0]); });
        ['dragover', 'dragenter'].forEach(function (ev) { drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.add('aktif'); }); });
        ['dragleave', 'drop'].forEach(function (ev) { drop.addEventListener(ev, function () { drop.classList.remove('aktif'); }); });
        drop.addEventListener('drop', function (e) {
            e.preventDefault();
            if (e.dataTransfer.files.length) { input.files = e.dataTransfer.files; tampil(input.files[0]); }
        });
        if (hapus) hapus.addEventListener('change', function () { img.style.opacity = hapus.checked ? .3 : 1; });
    })();
</script>
@endpush
