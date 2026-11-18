@extends('layouts.panel')

@php($isEdit = $perangkat->exists)
@section('title', $isEdit ? 'Edit Perangkat' : 'Tambah Perangkat')
@section('subtitle', $isEdit ? 'Perbarui data perangkat desa' : 'Tambahkan perangkat desa')

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="card">
    <div class="card-body p-4">
        <form action="{{ $isEdit ? route('admin.perangkat.update', $perangkat) : route('admin.perangkat.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
            @csrf
            @if ($isEdit) @method('PUT') @endif

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Nama <span class="text-danger">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $perangkat->nama) }}" class="form-control @error('nama') is-invalid @enderror" required>
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Jabatan <span class="text-danger">*</span></label>
                <input type="text" name="jabatan" value="{{ old('jabatan', $perangkat->jabatan) }}" class="form-control @error('jabatan') is-invalid @enderror" placeholder="Contoh: Kepala Desa" required>
                @error('jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Posisi dalam struktur organisasi (pohon) --}}
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Atasan Langsung</label>
                @php($pilih = old('atasan', $perangkat->exists ? ($perangkat->atasan_id ?: 'akar') : 'otomatis'))
                <select name="atasan" class="form-select @error('atasan') is-invalid @enderror" id="pilihAtasan">
                    <option value="otomatis" @selected($pilih === 'otomatis')>Otomatis sesuai jabatan</option>
                    <option value="akar" @selected($pilih === 'akar')>— Tidak ada (puncak struktur / Kepala Desa)</option>
                    @foreach ($pilihanAtasan as $a)
                        <option value="{{ $a->id }}" @selected((string) $pilih === (string) $a->id)>{{ $a->jabatan }} — {{ $a->nama }}</option>
                    @endforeach
                </select>
                @error('atasan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="form-text">Otomatis: Sekretaris & Kasi/Kadus di bawah Kepala Desa, Kaur di bawah Sekretaris Desa.</div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Urutan</label>
                <input type="number" name="urutan" min="0" max="999" value="{{ old('urutan', $perangkat->exists ? $perangkat->urutan : '') }}" class="form-control" placeholder="Otomatis">
                <div class="form-text">Kecil = lebih kiri.</div>
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <div class="form-check form-switch mt-3">
                    <input class="form-check-input" type="checkbox" name="is_mitra" value="1" id="isMitra" @checked(old('is_mitra', $perangkat->is_mitra))>
                    <label class="form-check-label small fw-semibold" for="isMitra">Lembaga mitra <span class="text-muted fw-normal d-block">(mis. BPD, LPMD)</span></label>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Foto <span class="text-muted fw-normal">(maks 10MB — otomatis dikompres)</span></label>
                <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @if ($perangkat->foto)
                    <img src="{{ asset('storage/' . $perangkat->foto) }}" class="rounded-circle mt-2" width="80" height="80" style="object-fit: cover;" alt="">
                @endif
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Keterangan</label>
                <input type="text" name="keterangan" value="{{ old('keterangan', $perangkat->keterangan) }}" class="form-control" placeholder="Opsional">
            </div>

            <div class="col-12 d-flex gap-2 mt-2">
                <button class="btn btn-hijau px-4"><i class="bi bi-save me-1"></i> Simpan</button>
                <a href="{{ route('admin.perangkat.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
