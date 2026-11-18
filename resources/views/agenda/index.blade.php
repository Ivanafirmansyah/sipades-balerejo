@extends('layouts.app')

@section('title', 'Agenda Kegiatan')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Agenda Kegiatan</h1>
        <p class="mb-0 opacity-75">Jadwal kegiatan dan acara desa</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        @forelse ($agendas as $i => $agenda)
            <div class="col-md-6 col-lg-4 reveal" style="transition-delay: {{ ($i % 3) * 100 }}ms">
                <div class="agenda-foto-card">
                    <div class="agenda-foto">
                        @if ($agenda->foto)
                            <button type="button" class="agenda-foto-btn" data-bs-toggle="modal" data-bs-target="#fotoAgenda"
                                    data-foto="{{ $agenda->foto_url }}" data-judul="{{ $agenda->judul }}" aria-label="Perbesar foto {{ $agenda->judul }}">
                                <img src="{{ $agenda->foto_url }}" alt="{{ $agenda->judul }}" loading="lazy">
                                <span class="agenda-zoom"><i class="bi bi-arrows-fullscreen"></i></span>
                            </button>
                        @else
                            <div class="agenda-foto-kosong"><i class="bi bi-calendar-event"></i></div>
                        @endif
                        <div class="agenda-tanggal">
                            <div class="tgl">{{ $agenda->tanggal->format('d') }}</div>
                            <div class="bln">{{ $agenda->tanggal->translatedFormat('M Y') }}</div>
                        </div>
                        @if ($agenda->tanggal->isToday())
                            <span class="agenda-label hari-ini">Hari ini</span>
                        @elseif ($agenda->tanggal->isPast())
                            <span class="agenda-label selesai">Selesai</span>
                        @endif
                    </div>
                    <div class="agenda-isi">
                        <h6>{{ $agenda->judul }}</h6>
                        <div class="small text-muted mb-1"><i class="bi bi-clock me-1"></i>{{ $agenda->jam_formatted ?? '-' }} WIB</div>
                        <div class="small text-muted mb-2"><i class="bi bi-geo-alt me-1"></i>{{ $agenda->lokasi ?? '-' }}</div>
                        @if ($agenda->deskripsi)
                            <p class="small text-muted mb-0">{{ $agenda->deskripsi }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12"><div class="alert alert-light border text-center text-muted">Belum ada agenda kegiatan.</div></div>
        @endforelse
    </div>

    <div class="mt-4">{{ $agendas->links() }}</div>
</div>

{{-- Foto diperbesar --}}
<div class="modal fade" id="fotoAgenda" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0 shadow-none">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-white fw-semibold" id="fotoAgendaJudul"></span>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <img src="" alt="" id="fotoAgendaImg" class="img-fluid rounded-4 w-100">
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('fotoAgenda').addEventListener('show.bs.modal', function (e) {
        var b = e.relatedTarget;
        document.getElementById('fotoAgendaImg').src = b.dataset.foto;
        document.getElementById('fotoAgendaImg').alt = b.dataset.judul;
        document.getElementById('fotoAgendaJudul').textContent = b.dataset.judul;
    });
</script>
@endpush
