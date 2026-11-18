<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Data Penduduk</title>
    <style>
        * { font-family: 'DejaVu Sans', sans-serif; }
        body { font-size: 9.5px; color: #111; }
        .kop { text-align: center; border-bottom: 2px solid #111; padding-bottom: 8px; margin-bottom: 12px; }
        .kop h3 { margin: 0; font-size: 13px; text-transform: uppercase; }
        .kop h2 { margin: 2px 0; font-size: 16px; text-transform: uppercase; }
        .kop p { margin: 0; font-size: 8.5px; }
        .judul { text-align: center; margin-bottom: 10px; }
        .judul h4 { margin: 0; font-size: 12px; text-decoration: underline; text-transform: uppercase; }
        .judul p { margin: 3px 0 0; font-size: 9px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #555; padding: 4px 5px; vertical-align: top; }
        th { background: #e8efe9; font-size: 9px; text-align: left; }
        .center { text-align: center; }
        .footer { margin-top: 14px; font-size: 8.5px; }
    </style>
</head>
<body>
    <div class="kop">
        <h3>Pemerintah Kabupaten {{ $profil->kabupaten ?? '-' }}</h3>
        <h3>Kecamatan {{ $profil->kecamatan ?? '-' }}</h3>
        <h2>Desa {{ $profil->nama_desa ?? '-' }}</h2>
        <p>{{ $profil->alamat_kantor ?? '' }}</p>
    </div>

    <div class="judul">
        <h4>Laporan Data Penduduk</h4>
        <p>
            @if (!empty($filter['rt'])) RT {{ $filter['rt'] }} @endif
            @if (!empty($filter['rw'])) RW {{ $filter['rw'] }} @endif
            @if (empty($filter['rt']) && empty($filter['rw'])) Seluruh Wilayah Desa @endif
            &middot; Dicetak: {{ now()->translatedFormat('d F Y H:i') }} WIB
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 28px;" class="center">No</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>JK</th>
                <th>Tempat / Tgl Lahir</th>
                <th class="center">Umur</th>
                <th>Agama</th>
                <th>Pekerjaan</th>
                <th>Alamat</th>
                <th class="center">RT</th>
                <th class="center">RW</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($penduduks as $i => $p)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ $p->nik }}</td>
                    <td>{{ $p->nama }}</td>
                    <td class="center">{{ $p->jenis_kelamin }}</td>
                    <td>{{ $p->tempat_lahir ?? '-' }}, {{ $p->tanggal_lahir?->format('d/m/Y') ?? '-' }}</td>
                    <td class="center">{{ $p->umur_teks }}</td>
                    <td>{{ $p->agama ?? '-' }}</td>
                    <td>{{ $p->pekerjaan ?? '-' }}</td>
                    <td>{{ $p->alamat ?? '-' }}</td>
                    <td class="center">{{ $p->rt }}</td>
                    <td class="center">{{ $p->rw }}</td>
                    <td>{{ $p->status }}</td>
                </tr>
            @empty
                <tr><td colspan="12" class="center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Total penduduk: <strong>{{ $penduduks->count() }}</strong> jiwa &middot;
        Laki-laki: {{ $penduduks->where('jenis_kelamin', 'L')->count() }} &middot;
        Perempuan: {{ $penduduks->where('jenis_kelamin', 'P')->count() }} &middot;
        Kepala Keluarga: {{ $penduduks->where('status', 'Kepala Keluarga')->count() }}
    </div>
</body>
</html>
