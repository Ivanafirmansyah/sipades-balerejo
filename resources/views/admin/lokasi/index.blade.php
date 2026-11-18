@extends('layouts.panel')

@section('title', 'Peta Fasilitas')
@section('subtitle', 'Kelola titik fasilitas umum desa yang tampil di halaman Peta')

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css">
@endpush

@section('content')
<div class="card mb-4 peta-admin">
    <div id="petaRingkas" class="peta-kanvas" style="height: 340px;"></div>
</div>

<div class="card">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.lokasi.index') }}" class="btn btn-sm rounded-pill {{ request('kategori') ? 'btn-outline-secondary' : 'btn-biru' }}">Semua ({{ $jumlah->sum() }})</a>
                @foreach (\App\Models\LokasiDesa::KATEGORI as $k => [$label, $ikon])
                    @if ($jumlah[$k] ?? 0)
                        <a href="{{ route('admin.lokasi.index', ['kategori' => $k]) }}" class="btn btn-sm rounded-pill {{ request('kategori') === $k ? 'btn-biru' : 'btn-outline-secondary' }}"><i class="bi {{ $ikon }} me-1"></i>{{ $label }} ({{ $jumlah[$k] }})</a>
                    @endif
                @endforeach
            </div>
            <a href="{{ route('admin.lokasi.create') }}" class="btn btn-sm btn-biru"><i class="bi bi-plus-lg me-1"></i> Tambah Lokasi</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Foto</th><th>Nama</th><th>Kategori</th><th>Koordinat</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($lokasis as $l)
                        <tr>
                            <td>
                                @if ($l->foto)<img src="{{ $l->foto_url }}" alt="" class="agenda-thumb">
                                @else<span class="agenda-thumb agenda-thumb-kosong"><i class="bi {{ $l->ikon }}"></i></span>@endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $l->nama }} @if ($l->is_utama)<span class="badge badge-soft-warning"><i class="bi bi-star-fill"></i> Utama</span>@endif</div>
                                <div class="small text-muted">{{ $l->alamat ?: \Illuminate\Support\Str::limit($l->keterangan, 50) }}</div>
                            </td>
                            <td class="small"><i class="bi {{ $l->ikon }} me-1"></i>{{ $l->kategori_label }}</td>
                            <td class="small font-monospace"><a href="#" class="lihat-titik text-decoration-none" data-lat="{{ $l->lat }}" data-lng="{{ $l->lng }}">{{ number_format($l->lat, 5) }}, {{ number_format($l->lng, 5) }}</a></td>
                            <td><span class="badge badge-soft-{{ $l->is_aktif ? 'success' : 'secondary' }}">{{ $l->is_aktif ? 'Tampil' : 'Disembunyikan' }}</span></td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.lokasi.edit', $l) }}" class="btn btn-sm btn-outline-primary" title="Ubah"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.lokasi.destroy', $l) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus lokasi {{ $l->nama }} dari peta?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-5"><i class="bi bi-geo-alt fs-2 d-block mb-2"></i>Belum ada lokasi. Mulai dengan menambahkan kantor/balai desa sebagai titik utama.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/peta-desa.js') }}?v=1"></script>
<script>
    (function () {
        var data = @json($semua);
        var peta = PetaDesa.buat('petaRingkas', @json($pusat), 16);
        var grup = [];
        data.forEach(function (l) {
            grup.push(L.marker([l.lat, l.lng], { icon: PetaDesa.ikon(l.ikon, l.utama) }).bindPopup(PetaDesa.popup(l)).addTo(peta));
        });
        if (grup.length > 1) peta.fitBounds(L.featureGroup(grup).getBounds().pad(0.2));
        document.querySelectorAll('.lihat-titik').forEach(function (a) {
            a.addEventListener('click', function (e) { e.preventDefault(); peta.setView([+a.dataset.lat, +a.dataset.lng], 18); window.scrollTo({ top: 0, behavior: 'smooth' }); });
        });
    })();
</script>
@endpush
