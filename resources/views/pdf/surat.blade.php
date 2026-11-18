@php
    // Struktur surat diatur admin di menu "Pengaturan Surat"
    $ps = $ps ?? \App\Models\PengaturanSurat::aktif();
    $nilai = \App\Support\SuratTemplate::nilai($surat, $ps, $profil);
    $logo = $ps->tampilkan_logo ? \App\Support\SuratTemplate::logoDataUri($profil) : null;
    $tanggalSurat = ($surat->selesai_at ?? now())->translatedFormat('d F Y');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $surat->jenisSurat->nama ?? 'Surat Keterangan' }}</title>
    <style>
        @page { margin: 28px 46px 36px; }
        * { font-family: 'DejaVu Sans', sans-serif; }
        body { font-size: 12px; color: #111; line-height: 1.55; }
        .kop { width: 100%; border-bottom: 3px double #111; padding-bottom: 8px; margin-bottom: 20px; }
        .kop td { vertical-align: middle; }
        .kop .logo { width: 78px; }
        .kop .logo img { width: 70px; height: 70px; }
        .kop .teks { text-align: center; }
        .kop .b1, .kop .b2 { margin: 0; font-size: 14px; font-weight: bold; text-transform: uppercase; letter-spacing: .4px; }
        .kop .b3 { margin: 1px 0 2px; font-size: 19px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .kop .alamat { margin: 0; font-size: 10px; }
        .kop .penyeimbang { width: 78px; }
        .judul { text-align: center; margin-bottom: 20px; }
        .judul h4 { margin: 0; font-size: 15px; text-decoration: underline; text-transform: uppercase; letter-spacing: .5px; }
        .judul p { margin: 3px 0 0; font-size: 11px; }
        .isi p { margin: 0 0 12px; text-align: justify; }
        table.data { margin: 6px 0 14px 24px; border-collapse: collapse; }
        table.data td { padding: 2px 6px; vertical-align: top; font-size: 12px; }
        table.data td.label { width: 160px; }
        table.data td.pemisah { width: 10px; }
        .sub { font-weight: bold; margin: 4px 0 2px 24px; font-size: 11px; }
        .ttd { width: 100%; margin-top: 34px; }
        .ttd td { vertical-align: top; }
        .ttd .kanan { text-align: center; width: 260px; }
        .ttd .nama { margin-top: 72px; font-weight: bold; text-decoration: underline; }
    </style>
    @if (!empty($layar))
        {{-- Pratinjau di layar admin: tampil seperti lembar A4 --}}
        <style>
            html { background: #e2e8f0; }
            body { background: #fff; width: 210mm; min-height: 297mm; margin: 18px auto; padding: 28px 46px; box-sizing: border-box; box-shadow: 0 10px 30px rgba(15,23,42,.18); }
        </style>
    @endif
</head>
<body>
    {{-- KOP SURAT --}}
    <table class="kop">
        <tr>
            <td class="logo">@if ($logo)<img src="{{ $logo }}" alt="Logo">@endif</td>
            <td class="teks">
                @if ($ps->kop_baris_1)<p class="b1">{{ $ps->kop_baris_1 }}</p>@endif
                @if ($ps->kop_baris_2)<p class="b2">{{ $ps->kop_baris_2 }}</p>@endif
                @if ($ps->kop_baris_3)<p class="b3">{{ $ps->kop_baris_3 }}</p>@endif
                @if ($ps->kop_alamat)<p class="alamat">{{ $ps->kop_alamat }}@if ($profil?->telepon) &middot; Telp. {{ $profil->telepon }}@endif @if ($profil?->email) &middot; {{ $profil->email }}@endif</p>@endif
            </td>
            <td class="penyeimbang"></td>
        </tr>
    </table>

    {{-- JUDUL & NOMOR --}}
    <div class="judul">
        <h4>{{ $surat->jenisSurat->nama ?? 'Surat Keterangan' }}</h4>
        <p>Nomor: {{ $surat->no_surat ?: '........................' }}</p>
    </div>

    <div class="isi">
        <p>{!! \App\Support\SuratTemplate::render($ps->kalimat_pembuka ?: \App\Models\PengaturanSurat::KALIMAT_PEMBUKA, $nilai) !!}</p>

        <table class="data">
            <tr><td class="label">Nama</td><td class="pemisah">:</td><td><strong>{{ $nilai['{nama}'] }}</strong></td></tr>
            <tr><td class="label">NIK</td><td class="pemisah">:</td><td>{{ $nilai['{nik}'] }}</td></tr>
            <tr><td class="label">Tempat / Tanggal Lahir</td><td class="pemisah">:</td><td>{{ $nilai['{ttl}'] }}</td></tr>
            <tr><td class="label">Jenis Kelamin</td><td class="pemisah">:</td><td>{{ $nilai['{jenis_kelamin}'] }}</td></tr>
            <tr><td class="label">Agama</td><td class="pemisah">:</td><td>{{ $nilai['{agama}'] }}</td></tr>
            <tr><td class="label">Pekerjaan</td><td class="pemisah">:</td><td>{{ $nilai['{pekerjaan}'] }}</td></tr>
            <tr><td class="label">Alamat</td><td class="pemisah">:</td><td>{{ $nilai['{alamat}'] }}</td></tr>
        </table>

        {{-- Isian tambahan dari formulir online (bila surat diajukan lewat Form Builder) --}}
        @if (!empty($surat->data))
            <div class="sub">Keterangan tambahan:</div>
            <table class="data">
                @foreach ($surat->data as $row)
                    <tr><td class="label">{{ $row['label'] ?? '' }}</td><td class="pemisah">:</td><td>{{ $row['nilai'] ?? '-' }}</td></tr>
                @endforeach
            </table>
        @endif

        <p>{!! \App\Support\SuratTemplate::render(\App\Support\SuratTemplate::isi($surat->jenisSurat, $ps), $nilai) !!}</p>
        <p>{!! \App\Support\SuratTemplate::render($ps->kalimat_penutup ?: \App\Models\PengaturanSurat::KALIMAT_PENUTUP, $nilai) !!}</p>
    </div>

    {{-- TANDA TANGAN --}}
    <table class="ttd">
        <tr>
            <td></td>
            <td class="kanan">
                {{ $ps->tempat_surat ?: ($profil?->kabupaten ?? '') }}, {{ $tanggalSurat }}<br>
                {{ $nilai['{jabatan}'] }}
                <div class="nama">{{ $ps->ttd_nama ?: '..............................' }}</div>
                @if ($ps->ttd_nip)<div>NIP. {{ $ps->ttd_nip }}</div>@endif
            </td>
        </tr>
    </table>
</body>
</html>
