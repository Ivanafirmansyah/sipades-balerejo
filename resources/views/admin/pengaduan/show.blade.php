@extends('layouts.panel')

@section('title', 'Detail Pengaduan')
@section('subtitle', 'Pengaduan #' . $pengaduan->id)

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge badge-soft-secondary">{{ $pengaduan->kategori }}</span>
                        @php($badge = ['baru' => 'warning', 'diproses' => 'info', 'selesai' => 'success'][$pengaduan->status])
                        <span class="badge badge-soft-{{ $badge }}">{{ $pengaduan->statusLabel() }}</span>
                    </div>
                    <div class="small text-muted">{{ $pengaduan->created_at->translatedFormat('d F Y, H:i') }}</div>
                </div>

                <h6 class="fw-bold">Isi Pengaduan</h6>
                <p style="white-space: pre-line;">{{ $pengaduan->isi }}</p>

                <table class="table table-sm mt-3">
                    <tr><th class="text-muted fw-normal" width="150">Pelapor</th><td>{{ $pengaduan->nama_pelapor ?? $pengaduan->user->name ?? '-' }}</td></tr>
                    <tr><th class="text-muted fw-normal">Email</th><td>{{ $pengaduan->user->email ?? '-' }}</td></tr>
                    <tr><th class="text-muted fw-normal">Lokasi</th><td>{{ $pengaduan->lokasi ?? '-' }}</td></tr>
                </table>

                @if ($pengaduan->tanggapan)
                    <div class="alert alert-success mt-3">
                        <div class="fw-semibold mb-1"><i class="bi bi-reply-fill me-1"></i>Tanggapan Tersimpan</div>
                        <div style="white-space: pre-line;">{{ $pengaduan->tanggapan }}</div>
                        <div class="small text-muted mt-2">{{ $pengaduan->tanggapan_at?->translatedFormat('d F Y, H:i') }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">Beri Tanggapan</h6>
                <form action="{{ route('admin.pengaduan.update', $pengaduan) }}" method="POST" class="d-grid gap-3">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="form-label small fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            @foreach (['baru' => 'Baru', 'diproses' => 'Diproses', 'selesai' => 'Selesai'] as $key => $label)
                                <option value="{{ $key }}" @selected($pengaduan->status === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label small fw-semibold">Tanggapan</label>
                        <textarea name="tanggapan" rows="5" class="form-control" placeholder="Tulis tanggapan untuk pelapor...">{{ old('tanggapan', $pengaduan->tanggapan) }}</textarea>
                    </div>
                    <button class="btn btn-hijau"><i class="bi bi-send me-1"></i> Simpan Tanggapan</button>
                    <a href="{{ route('admin.pengaduan.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
