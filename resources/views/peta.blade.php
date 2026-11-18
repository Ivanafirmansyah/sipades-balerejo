@extends('layouts.app')

@section('title', 'Peta Desa')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css">
@endpush

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Peta Desa</h1>
        <p class="mb-0 opacity-75">Lokasi fasilitas umum di {{ $profil->nama_desa }}</p>
    </div>
</div>

<div class="container py-5">
    @if ($lokasis->isNotEmpty())
        {{-- Filter kategori --}}
        <div class="d-flex flex-wrap gap-2 mb-3 reveal" id="filterPeta">
            <button type="button" class="filter-chip aktif" data-kat="">Semua <span>{{ $lokasis->count() }}</span></button>
            @foreach (\App\Models\LokasiDesa::KATEGORI as $k => [$label, $ikon])
                @if ($jumlah[$k] ?? 0)
                    <button type="button" class="filter-chip" data-kat="{{ $k }}"><i class="bi {{ $ikon }}"></i> {{ $label }} <span>{{ $jumlah[$k] }}</span></button>
                @endif
            @endforeach
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card peta-publik"><div id="petaDesa" class="peta-kanvas"></div></div>
                <div class="small text-muted mt-2"><i class="bi bi-wifi-off me-1"></i> Gambar peta memerlukan koneksi internet. Daftar fasilitas di samping tetap bisa dilihat saat offline.</div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-body p-3">
                        <h6 class="fw-bold px-1 mb-2"><i class="bi bi-list-ul me-1"></i> Daftar Fasilitas</h6>
                        <ul class="daftar-lokasi" id="daftarLokasi">
                            @foreach ($lokasis as $l)
                                <li data-kat="{{ $l->kategori }}">
                                    <button type="button" class="lokasi-item" data-id="{{ $l->id }}">
                                        <span class="li-ikon @if ($l->is_utama) utama @endif"><i class="bi {{ $l->ikon }}"></i></span>
                                        <span class="flex-grow-1 min-w-0 text-start">
                                            <b>{{ $l->nama }}</b>
                                            <small>{{ $l->kategori_label }}@if ($l->alamat) · {{ $l->alamat }}@endif</small>
                                        </span>
                                        <i class="bi bi-chevron-right li-panah"></i>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($profil->peta_embed)
        <div class="card"><div class="card-body p-3"><div class="ratio" style="--bs-aspect-ratio: 55%;">{!! $profil->peta_embed !!}</div></div></div>
    @else
        <div class="card"><div class="card-body p-5 text-center text-muted"><i class="bi bi-map fs-1 d-block mb-2"></i>Peta desa belum diatur oleh admin.</div></div>
    @endif

    <div class="card mt-4">
        <div class="card-body p-4">
            <h5 class="section-title mb-3">Alamat Kantor Desa</h5>
            <p class="mb-1"><i class="bi bi-geo-alt text-hijau me-2"></i>{{ $profil->alamat_kantor ?? '-' }}</p>
            <p class="mb-1"><i class="bi bi-telephone text-hijau me-2"></i>{{ $profil->telepon ?? '-' }}</p>
            <p class="mb-0"><i class="bi bi-envelope text-hijau me-2"></i>{{ $profil->email ?? '-' }}</p>
        </div>
    </div>
</div>
@endsection

@if ($lokasis->isNotEmpty())
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/peta-desa.js') }}?v=1"></script>
<script>
    (function () {
        var data = @json($data);
        if (!window.L) return; // offline & Leaflet belum tersimpan: daftar tetap tampil
        var peta = PetaDesa.buat('petaDesa', @json($pusat), 16);
        var penanda = {};
        data.forEach(function (l) {
            penanda[l.id] = { l: l, m: L.marker([l.lat, l.lng], { icon: PetaDesa.ikon(l.ikon, l.utama), title: l.nama }).bindPopup(PetaDesa.popup(l), { maxWidth: 260 }) };
        });

        function tampilkan(kat) {
            var terlihat = [];
            Object.keys(penanda).forEach(function (id) {
                var p = penanda[id], ok = !kat || p.l.kategori === kat;
                if (ok) { p.m.addTo(peta); terlihat.push(p.m); } else peta.removeLayer(p.m);
            });
            document.querySelectorAll('#daftarLokasi li').forEach(function (li) { li.hidden = !!kat && li.dataset.kat !== kat; });
            if (terlihat.length > 1) peta.fitBounds(L.featureGroup(terlihat).getBounds().pad(0.25), { maxZoom: 17 });
            else if (terlihat.length === 1) peta.setView(terlihat[0].getLatLng(), 17);
        }

        document.querySelectorAll('#filterPeta .filter-chip').forEach(function (b) {
            b.addEventListener('click', function () {
                document.querySelectorAll('#filterPeta .filter-chip').forEach(function (x) { x.classList.toggle('aktif', x === b); });
                tampilkan(b.dataset.kat);
            });
        });

        document.querySelectorAll('.lokasi-item').forEach(function (b) {
            b.addEventListener('click', function () {
                var p = penanda[b.dataset.id];
                peta.setView(p.m.getLatLng(), 18);
                p.m.openPopup();
                if (window.innerWidth < 992) document.getElementById('petaDesa').scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        });

        tampilkan('');
    })();
</script>
@endpush
@endif
