<?php
    // Struktur surat diatur admin di menu "Pengaturan Surat"
    $ps = $ps ?? \App\Models\PengaturanSurat::aktif();
    $nilai = \App\Support\SuratTemplate::nilai($surat, $ps, $profil);
    $logo = $ps->tampilkan_logo ? \App\Support\SuratTemplate::logoDataUri($profil) : null;
    $tanggalSurat = ($surat->selesai_at ?? now())->translatedFormat('d F Y');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?php echo e($surat->jenisSurat->nama ?? 'Surat Keterangan'); ?></title>
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
    <?php if(!empty($layar)): ?>
        
        <style>
            html { background: #e2e8f0; }
            body { background: #fff; width: 210mm; min-height: 297mm; margin: 18px auto; padding: 28px 46px; box-sizing: border-box; box-shadow: 0 10px 30px rgba(15,23,42,.18); }
        </style>
    <?php endif; ?>
</head>
<body>
    
    <table class="kop">
        <tr>
            <td class="logo"><?php if($logo): ?><img src="<?php echo e($logo); ?>" alt="Logo"><?php endif; ?></td>
            <td class="teks">
                <?php if($ps->kop_baris_1): ?><p class="b1"><?php echo e($ps->kop_baris_1); ?></p><?php endif; ?>
                <?php if($ps->kop_baris_2): ?><p class="b2"><?php echo e($ps->kop_baris_2); ?></p><?php endif; ?>
                <?php if($ps->kop_baris_3): ?><p class="b3"><?php echo e($ps->kop_baris_3); ?></p><?php endif; ?>
                <?php if($ps->kop_alamat): ?><p class="alamat"><?php echo e($ps->kop_alamat); ?><?php if($profil?->telepon): ?> &middot; Telp. <?php echo e($profil->telepon); ?><?php endif; ?> <?php if($profil?->email): ?> &middot; <?php echo e($profil->email); ?><?php endif; ?></p><?php endif; ?>
            </td>
            <td class="penyeimbang"></td>
        </tr>
    </table>

    
    <div class="judul">
        <h4><?php echo e($surat->jenisSurat->nama ?? 'Surat Keterangan'); ?></h4>
        <p>Nomor: <?php echo e($surat->no_surat ?: '........................'); ?></p>
    </div>

    <div class="isi">
        <p><?php echo \App\Support\SuratTemplate::render($ps->kalimat_pembuka ?: \App\Models\PengaturanSurat::KALIMAT_PEMBUKA, $nilai); ?></p>

        <table class="data">
            <tr><td class="label">Nama</td><td class="pemisah">:</td><td><strong><?php echo e($nilai['{nama}']); ?></strong></td></tr>
            <tr><td class="label">NIK</td><td class="pemisah">:</td><td><?php echo e($nilai['{nik}']); ?></td></tr>
            <tr><td class="label">Tempat / Tanggal Lahir</td><td class="pemisah">:</td><td><?php echo e($nilai['{ttl}']); ?></td></tr>
            <tr><td class="label">Jenis Kelamin</td><td class="pemisah">:</td><td><?php echo e($nilai['{jenis_kelamin}']); ?></td></tr>
            <tr><td class="label">Agama</td><td class="pemisah">:</td><td><?php echo e($nilai['{agama}']); ?></td></tr>
            <tr><td class="label">Pekerjaan</td><td class="pemisah">:</td><td><?php echo e($nilai['{pekerjaan}']); ?></td></tr>
            <tr><td class="label">Alamat</td><td class="pemisah">:</td><td><?php echo e($nilai['{alamat}']); ?></td></tr>
        </table>

        
        <?php if(!empty($surat->data)): ?>
            <div class="sub">Keterangan tambahan:</div>
            <table class="data">
                <?php $__currentLoopData = $surat->data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr><td class="label"><?php echo e($row['label'] ?? ''); ?></td><td class="pemisah">:</td><td><?php echo e($row['nilai'] ?? '-'); ?></td></tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
        <?php endif; ?>

        <p><?php echo \App\Support\SuratTemplate::render(\App\Support\SuratTemplate::isi($surat->jenisSurat, $ps), $nilai); ?></p>
        <p><?php echo \App\Support\SuratTemplate::render($ps->kalimat_penutup ?: \App\Models\PengaturanSurat::KALIMAT_PENUTUP, $nilai); ?></p>
    </div>

    
    <table class="ttd">
        <tr>
            <td></td>
            <td class="kanan">
                <?php echo e($ps->tempat_surat ?: ($profil?->kabupaten ?? '')); ?>, <?php echo e($tanggalSurat); ?><br>
                <?php echo e($nilai['{jabatan}']); ?>

                <div class="nama"><?php echo e($ps->ttd_nama ?: '..............................'); ?></div>
                <?php if($ps->ttd_nip): ?><div>NIP. <?php echo e($ps->ttd_nip); ?></div><?php endif; ?>
            </td>
        </tr>
    </table>
</body>
</html>
<?php /**PATH E:\laragon\www\sipades\resources\views\pdf\surat.blade.php ENDPATH**/ ?>