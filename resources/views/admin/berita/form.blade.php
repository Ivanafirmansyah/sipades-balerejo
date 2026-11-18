@extends('layouts.panel')

@php($isEdit = $berita->exists)
@section('title', $isEdit ? 'Edit Berita' : 'Tambah Berita')
@section('subtitle', $isEdit ? 'Perbarui berita / pengumuman' : 'Tulis berita atau pengumuman baru')

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="card">
    <div class="card-body p-4">
        <form action="{{ $isEdit ? route('admin.berita.update', $berita) : route('admin.berita.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
            @csrf
            @if ($isEdit) @method('PUT') @endif

            <div class="col-md-8">
                <label class="form-label small fw-semibold">Judul <span class="text-danger">*</span></label>
                <input type="text" name="judul" value="{{ old('judul', $berita->judul) }}" class="form-control @error('judul') is-invalid @enderror" required>
                @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-semibold">Kategori <span class="text-danger">*</span></label>
                <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                    <option value="berita" @selected(old('kategori', $berita->kategori) === 'berita')>Berita</option>
                    <option value="pengumuman" @selected(old('kategori', $berita->kategori) === 'pengumuman')>Pengumuman</option>
                </select>
                @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label class="form-label small fw-semibold">Isi Berita <span class="text-danger">*</span></label>
                <textarea name="isi" rows="10" class="form-control @error('isi') is-invalid @enderror" placeholder="Tulis isi berita..." required>{{ old('isi', $berita->isi) }}</textarea>
                @error('isi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Gambar <span class="text-muted fw-normal">(opsional, maks 10MB — otomatis dikompres)</span></label>
                <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror" accept="image/*">
                @error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @if ($berita->gambar)
                    <div class="mt-2 d-flex align-items-center gap-2">
                        <img src="{{ asset('storage/' . $berita->gambar) }}" class="rounded" width="120" height="80" style="object-fit: cover;" alt="">
                        <span class="small text-muted">Gambar saat ini</span>
                    </div>
                @endif
            </div>

            <div class="col-md-6 d-flex align-items-center">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1" @checked(old('is_published', $berita->is_published ?? true))>
                    <label class="form-check-label small fw-semibold" for="is_published">Terbitkan sekarang</label>
                </div>
            </div>

            <div class="col-12 d-flex gap-2 mt-2">
                <button class="btn btn-hijau px-4"><i class="bi bi-save me-1"></i> Simpan</button>
                <a href="{{ route('admin.berita.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
