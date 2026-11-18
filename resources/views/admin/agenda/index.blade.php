@extends('layouts.panel')

@section('title', 'Agenda Kegiatan')
@section('subtitle', 'Kelola jadwal kegiatan desa')

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Daftar Agenda</h6>
            <a href="{{ route('admin.agenda.create') }}" class="btn btn-sm btn-hijau"><i class="bi bi-plus-lg me-1"></i> Tambah Agenda</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr><th>Foto</th><th>Tanggal</th><th>Jam</th><th>Judul</th><th>Lokasi</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($agendas as $agenda)
                        <tr>
                            <td>
                                @if ($agenda->foto)
                                    <img src="{{ $agenda->foto_url }}" alt="" class="agenda-thumb">
                                @else
                                    <span class="agenda-thumb agenda-thumb-kosong"><i class="bi bi-image"></i></span>
                                @endif
                            </td>
                            <td class="small">{{ $agenda->tanggal->translatedFormat('d M Y') }}</td>
                            <td class="small">{{ $agenda->jam_formatted ?? '-' }}</td>
                            <td>
                                <div class="fw-semibold">{{ $agenda->judul }}</div>
                                @if ($agenda->deskripsi)<div class="small text-muted">{{ \Illuminate\Support\Str::limit($agenda->deskripsi, 60) }}</div>@endif
                            </td>
                            <td class="small">{{ $agenda->lokasi ?? '-' }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.agenda.edit', $agenda) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.agenda.destroy', $agenda) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus agenda ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada agenda.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $agendas->links() }}
    </div>
</div>
@endsection
