@extends('layouts.panel')

@section('title', 'Isian: ' . $formulir->judul)
@section('subtitle', 'Data yang dikirim warga melalui formulir dinamis')

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@php($fields = $formulir->fields())
@php($kolom = array_slice($fields, 0, 3))

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100"><div class="card-body d-flex align-items-center gap-3">
            <div class="icon"><i class="bi bi-inbox-fill"></i></div>
            <div><div class="fs-4 fw-bold lh-1 mb-1">{{ $stats['total'] }}</div><div class="small text-muted">Total Isian</div></div>
        </div></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100"><div class="card-body d-flex align-items-center gap-3">
            <div class="icon"><i class="bi bi-bell-fill"></i></div>
            <div><div class="fs-4 fw-bold lh-1 mb-1">{{ $stats['baru'] }}</div><div class="small text-muted">Belum Ditinjau</div></div>
        </div></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100"><div class="card-body d-flex align-items-center gap-3">
            <div class="icon"><i class="bi bi-wifi-off"></i></div>
            <div><div class="fs-4 fw-bold lh-1 mb-1">{{ $stats['offline'] }}</div><div class="small text-muted">Diisi Saat Offline</div></div>
        </div></div>
    </div>
    <div class="col-6 col-lg-3">
        <a href="{{ route('admin.formulir.jawaban.csv', $formulir) }}" class="export-card">
            <span class="export-icon" style="background:#dcfce7;color:#15803d"><i class="bi bi-filetype-csv"></i></span>
            <span class="flex-grow-1 lh-sm"><span class="d-block fw-bold">Unduh Excel/CSV</span><span class="d-block small export-sub">Semua isian</span></span>
            <i class="bi bi-download export-dl"></i>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div class="d-flex gap-2 flex-wrap">
                @foreach (['' => 'Semua', 'baru' => 'Baru', 'diproses' => 'Diproses', 'selesai' => 'Selesai'] as $k => $v)
                    <a href="{{ route('admin.formulir.jawaban', [$formulir, 'status' => $k ?: null]) }}" class="btn btn-sm {{ request('status', '') === $k ? 'btn-biru' : 'btn-outline-secondary' }} rounded-pill">{{ $v }}</a>
                @endforeach
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('formulir.show', $formulir->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-box-arrow-up-right me-1"></i> Buka Formulir</a>
                <a href="{{ route('admin.formulir.edit', $formulir) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-tools me-1"></i> Ubah Isian</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th><th>Masuk</th><th>Pengirim</th>
                        @foreach ($kolom as $f)<th>{{ \Illuminate\Support\Str::limit($f['label'], 22) }}</th>@endforeach
                        <th>Status</th><th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jawabans as $j)
                        <tr>
                            <td>{{ $j->id }}</td>
                            <td class="small text-nowrap">
                                {{ $j->created_at->format('d/m/Y H:i') }}
                                @if ($j->dikirim_offline)
                                    <div><span class="badge badge-soft-warning" title="Diisi {{ $j->diisi_pada?->format('d/m/Y H:i') }}"><i class="bi bi-wifi-off"></i> Offline</span></div>
                                @endif
                                @if ($j->diedit_kali)
                                    <div><span class="badge badge-soft-info" title="Terakhir dikoreksi {{ $j->terakhir_diedit_at?->format('d/m/Y H:i') }}"><i class="bi bi-pencil-square"></i> Dikoreksi {{ $j->diedit_kali }}×</span></div>
                                @endif
                            </td>
                            <td class="small">{{ $j->user->name ?? 'Tamu' }}</td>
                            @foreach ($kolom as $f)
                                <td class="small">{{ \Illuminate\Support\Str::limit(\App\Support\FormSchema::display($f, $j->data[$f['name']] ?? null), 30) }}</td>
                            @endforeach
                            <td>
                                @php($b = ['baru' => 'warning', 'diproses' => 'info', 'selesai' => 'success'][$j->status] ?? 'secondary')
                                <span class="badge badge-soft-{{ $b }}">{{ $j->statusLabel() }}</span>
                                @if ($j->surat)<div class="small mt-1"><a href="{{ route('admin.surat.index') }}">Surat #{{ $j->surat_id }}</a></div>@endif
                            </td>
                            <td class="text-end text-nowrap">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#isian{{ $j->id }}" title="Lihat"><i class="bi bi-eye"></i></button>
                                <form action="{{ route('admin.formulir.jawaban.destroy', [$formulir, $j]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus isian ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ 5 + count($kolom) }}" class="text-center text-muted py-5"><i class="bi bi-inbox fs-2 d-block mb-2"></i>Belum ada isian masuk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $jawabans->links() }}
    </div>
</div>

@foreach ($jawabans as $j)
    <div class="modal fade" id="isian{{ $j->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <form action="{{ route('admin.formulir.jawaban.update', [$formulir, $j]) }}" method="POST" class="modal-content">
                @csrf @method('PATCH')
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Isian #{{ $j->id }} — {{ $j->user->name ?? 'Tamu' }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="small text-muted mb-3">
                        <span class="badge badge-soft-secondary me-1">Versi {{ $j->versi }}</span>
                        Masuk {{ $j->created_at->translatedFormat('d F Y H:i') }}
                        @if ($j->dikirim_offline) · <i class="bi bi-wifi-off"></i> diisi offline pada {{ $j->diisi_pada?->translatedFormat('d F Y H:i') }}@endif
                    </div>
                    <dl class="row isian-detail mb-0">
                        @foreach ($formulir->schema['fields'] ?? [] as $f)
                            @if ($f['type'] === 'section')
                                <dt class="col-12 form-section-title mt-2">{{ $f['label'] }}</dt>
                            @else
                                <dt class="col-sm-5">{{ $f['label'] }}</dt>
                                <dd class="col-sm-7">{{ \App\Support\FormSchema::display($f, $j->data[$f['name']] ?? null) }}</dd>
                            @endif
                        @endforeach
                    </dl>

                    @if ($j->riwayats->isNotEmpty())
                        <hr>
                        <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-1"></i> Riwayat Koreksi ({{ $j->riwayats->count() }}×)</h6>
                        @php($sesudah = $j->data)
                        @foreach ($j->riwayats as $r)
                            @php($berubah = collect($fields)->filter(fn ($f) => ($r->data[$f['name']] ?? null) != ($sesudah[$f['name']] ?? null)))
                            <div class="riwayat-versi small">
                                <div class="fw-semibold">
                                    Versi {{ $r->versi }} → {{ $r->versi + 1 }}
                                    <span class="text-muted fw-normal">· {{ ($r->diubah_pada ?? $r->created_at)->translatedFormat('d M Y H:i') }} · {{ $r->pengubah->name ?? 'Pengirim (perangkat)' }}</span>
                                    @if ($r->dikirim_offline)<span class="badge badge-soft-warning"><i class="bi bi-wifi-off"></i> dikoreksi offline</span>@endif
                                </div>
                                @forelse ($berubah as $f)
                                    <div>{{ $f['label'] }}: <span class="ubah text-decoration-line-through text-muted">{{ \App\Support\FormSchema::display($f, $r->data[$f['name']] ?? null) }}</span>
                                        → <span class="ubah fw-semibold">{{ \App\Support\FormSchema::display($f, $sesudah[$f['name']] ?? null) }}</span></div>
                                @empty
                                    <div class="text-muted">Tidak ada isian yang berubah.</div>
                                @endforelse
                            </div>
                            @php($sesudah = $r->data)
                        @endforeach
                    @endif
                </div>
                <div class="modal-footer justify-content-between">
                    <select name="status" class="form-select form-select-sm w-auto">
                        @foreach (['baru' => 'Baru', 'diproses' => 'Diproses', 'selesai' => 'Selesai'] as $k => $v)
                            <option value="{{ $k }}" @selected($j->status === $k)>{{ $v }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-biru btn-sm"><i class="bi bi-check2 me-1"></i> Simpan Status</button>
                </div>
            </form>
        </div>
    </div>
@endforeach
@endsection
