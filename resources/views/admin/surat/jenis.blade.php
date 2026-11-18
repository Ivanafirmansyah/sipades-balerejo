@extends('layouts.panel')

@section('title', 'Jenis Surat')
@section('subtitle', 'Kelola jenis surat yang tersedia')

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">Tambah Jenis Surat</h6>
                <form action="{{ route('admin.jenis.store') }}" method="POST" class="d-grid gap-3">
                    @csrf
                    <div>
                        <label class="form-label small fw-semibold">Nama Surat <span class="text-danger">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" placeholder="Contoh: Surat Keterangan Domisili" required>
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="form-label small fw-semibold">Kode</label>
                        <input type="text" name="kode" value="{{ old('kode') }}" class="form-control" placeholder="Contoh: SKD">
                    </div>
                    <div>
                        <label class="form-label small fw-semibold">Keterangan</label>
                        <textarea name="keterangan" rows="2" class="form-control" placeholder="Penjelasan singkat">{{ old('keterangan') }}</textarea>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_aktif" id="is_aktif" class="form-check-input" value="1" checked>
                        <label class="form-check-label small" for="is_aktif">Aktif (tampil pada pengajuan)</label>
                    </div>
                    <button class="btn btn-hijau"><i class="bi bi-plus-lg me-1"></i> Tambah</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">Daftar Jenis Surat</h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr><th>Nama</th><th>Kode</th><th>Dipakai</th><th>Status</th><th class="text-end">Aksi</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($jenisSurats as $jenis)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $jenis->nama }}</div>
                                        @if ($jenis->keterangan)<div class="small text-muted">{{ $jenis->keterangan }}</div>@endif
                                    </td>
                                    <td class="small">{{ $jenis->kode ?? '-' }}</td>
                                    <td class="small">{{ $jenis->surats_count }}</td>
                                    <td>
                                        <span class="badge badge-soft-{{ $jenis->is_aktif ? 'success' : 'secondary' }}">
                                            {{ $jenis->is_aktif ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editJenis{{ $jenis->id }}"><i class="bi bi-pencil"></i></button>
                                        <form action="{{ route('admin.jenis.destroy', $jenis) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus jenis surat {{ $jenis->nama }}?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada jenis surat.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($jenisSurats as $jenis)
    <div class="modal fade" id="editJenis{{ $jenis->id }}" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('admin.jenis.update', $jenis) }}" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Edit Jenis Surat</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body d-grid gap-3">
                    <div>
                        <label class="form-label small fw-semibold">Nama Surat</label>
                        <input type="text" name="nama" value="{{ $jenis->nama }}" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label small fw-semibold">Kode</label>
                        <input type="text" name="kode" value="{{ $jenis->kode }}" class="form-control">
                    </div>
                    <div>
                        <label class="form-label small fw-semibold">Keterangan</label>
                        <textarea name="keterangan" rows="2" class="form-control">{{ $jenis->keterangan }}</textarea>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_aktif" id="aktif{{ $jenis->id }}" class="form-check-input" value="1" @checked($jenis->is_aktif)>
                        <label class="form-check-label small" for="aktif{{ $jenis->id }}">Aktif</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-hijau btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endforeach
@endsection
