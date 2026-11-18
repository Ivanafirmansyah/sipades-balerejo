@extends('layouts.panel')

@section('title', 'Data Penduduk')
@section('subtitle', 'Kelola data warga dan pengelompokan RT/RW')

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card"><div class="card-body">
            <div class="small text-muted">Total Penduduk</div>
            <div class="fs-4 fw-bold">{{ number_format($stats['total']) }}</div>
        </div></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card"><div class="card-body">
            <div class="small text-muted">Laki-laki</div>
            <div class="fs-4 fw-bold">{{ number_format($stats['laki']) }}</div>
        </div></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card"><div class="card-body">
            <div class="small text-muted">Perempuan</div>
            <div class="fs-4 fw-bold">{{ number_format($stats['perempuan']) }}</div>
        </div></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card"><div class="card-body">
            <div class="small text-muted">Kepala Keluarga</div>
            <div class="fs-4 fw-bold">{{ number_format($stats['kk']) }}</div>
        </div></div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body p-4">
        <form action="{{ route('admin.penduduk.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Cari</label>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Nama / NIK / No. KK">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold">RT</label>
                <select name="rt" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach ($rtList as $rt)
                        <option value="{{ $rt }}" @selected(request('rt') == $rt)>RT {{ $rt }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold">RW</label>
                <select name="rw" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    @foreach ($rwList as $rw)
                        <option value="{{ $rw }}" @selected(request('rw') == $rw)>RW {{ $rw }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">JK</label>
                <select name="jenis_kelamin" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="L" @selected(request('jenis_kelamin') === 'L')>Laki-laki</option>
                    <option value="P" @selected(request('jenis_kelamin') === 'P')>Perempuan</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-sm btn-hijau"><i class="bi bi-funnel me-1"></i> Filter</button>
                <a href="{{ route('admin.penduduk.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h6 class="fw-bold mb-0">Daftar Penduduk</h6>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.penduduk.export', request()->only(['rt', 'rw'])) }}" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                </a>
                <a href="{{ route('admin.penduduk.create') }}" class="btn btn-sm btn-hijau">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Penduduk
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>JK</th>
                        <th>Tgl Lahir</th>
                        <th>Umur</th>
                        <th>RT/RW</th>
                        <th>Status</th>
                        <th>Akun</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($penduduks as $penduduk)
                        <tr>
                            <td class="font-monospace small">{{ $penduduk->nik }}</td>
                            <td class="fw-semibold">{{ $penduduk->nama }}</td>
                            <td>{{ $penduduk->jenis_kelamin }}</td>
                            <td class="small">{{ $penduduk->tanggal_lahir?->format('d/m/Y') ?? '-' }}</td>
                            <td class="small text-nowrap">
                                <span class="fw-semibold">{{ $penduduk->umur_teks }}</span>
                                @if ($penduduk->kelompok_umur)<div><span class="badge badge-soft-{{ ['Balita' => 'info', 'Anak' => 'success', 'Remaja' => 'warning', 'Dewasa' => 'secondary', 'Lansia' => 'danger'][$penduduk->kelompok_umur] }}">{{ $penduduk->kelompok_umur }}</span></div>@endif
                            </td>
                            <td>{{ $penduduk->rt }}/{{ $penduduk->rw }}</td>
                            <td><span class="badge badge-soft-secondary">{{ $penduduk->status }}</span></td>
                            <td>
                                @php($akun = \App\Services\AkunWarga::status($penduduk))
                                <span class="badge badge-soft-{{ $akun['warna'] }} akun-badge" title="Login dengan NIK {{ $penduduk->nik }}"><i class="bi {{ $akun['ikon'] }} me-1"></i>{{ $akun['label'] }}</span>
                            </td>
                            <td class="text-end text-nowrap">
                                @if ($penduduk->user?->role !== 'admin')
                                    <form action="{{ route('admin.penduduk.reset-sandi', $penduduk) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Kembalikan kata sandi {{ $penduduk->nama }} ke tanggal lahir?')">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-secondary" title="Reset kata sandi ke tanggal lahir"><i class="bi bi-key"></i></button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.penduduk.edit', $penduduk) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.penduduk.destroy', $penduduk) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data {{ $penduduk->nama }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">Data penduduk tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $penduduks->links() }}
    </div>
</div>

<div class="card mt-4">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-3">Pengelompokan per RT/RW</h6>
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead class="table-light"><tr><th>RT</th><th>RW</th><th class="text-end">Jumlah Jiwa</th></tr></thead>
                <tbody>
                    @forelse ($perRt as $item)
                        <tr>
                            <td>{{ $item->rt }}</td>
                            <td>{{ $item->rw }}</td>
                            <td class="text-end fw-semibold">{{ $item->jumlah }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
