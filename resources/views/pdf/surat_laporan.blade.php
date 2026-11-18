<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Surat Menyurat</title>
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
        .status { text-transform: capitalize; }
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
        <h4>Laporan Surat Menyurat</h4>
        <p>Dicetak: {{ now()->translatedFormat('d F Y H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 28px;" class="center">No</th>
                <th>No. Surat</th>
                <th>Pemohon</th>
                <th>NIK</th>
                <th>Jenis Surat</th>
                <th>Keperluan</th>
                <th class="center">Tanggal</th>
                <th class="center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($surats as $i => $s)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ $s->no_surat ?? '-' }}</td>
                    <td>{{ $s->penduduk->nama ?? '-' }}</td>
                    <td>{{ $s->penduduk->nik ?? '-' }}</td>
                    <td>{{ $s->jenisSurat->nama ?? '-' }}</td>
                    <td>{{ $s->keperluan ?? '-' }}</td>
                    <td class="center">{{ $s->created_at->format('d/m/Y') }}</td>
                    <td class="center status">{{ $s->status }}</td>
                </tr>
            @empty
                <tr><td colspan="8" class="center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Total pengajuan: <strong>{{ $surats->count() }}</strong> &middot;
        Menunggu: {{ $surats->where('status', 'menunggu')->count() }} &middot;
        Diproses: {{ $surats->where('status', 'diproses')->count() }} &middot;
        Selesai: {{ $surats->where('status', 'selesai')->count() }} &middot;
        Ditolak: {{ $surats->where('status', 'ditolak')->count() }}
    </div>
</body>
</html>
