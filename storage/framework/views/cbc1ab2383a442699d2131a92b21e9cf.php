<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Pengaduan</title>
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
    </style>
</head>
<body>
    <div class="kop">
        <h3>Pemerintah Kabupaten <?php echo e($profil->kabupaten ?? '-'); ?></h3>
        <h3>Kecamatan <?php echo e($profil->kecamatan ?? '-'); ?></h3>
        <h2>Desa <?php echo e($profil->nama_desa ?? '-'); ?></h2>
        <p><?php echo e($profil->alamat_kantor ?? ''); ?></p>
    </div>

    <div class="judul">
        <h4>Laporan Pengaduan Warga</h4>
        <p>Dicetak: <?php echo e(now()->translatedFormat('d F Y H:i')); ?> WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 28px;" class="center">No</th>
                <th class="center">Tanggal</th>
                <th>Pelapor</th>
                <th>Kategori</th>
                <th>Lokasi</th>
                <th>Isi Pengaduan</th>
                <th>Tanggapan</th>
                <th class="center">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $pengaduans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="center"><?php echo e($i + 1); ?></td>
                    <td class="center"><?php echo e($p->created_at->format('d/m/Y')); ?></td>
                    <td><?php echo e($p->nama_pelapor ?? $p->user->name ?? '-'); ?></td>
                    <td><?php echo e($p->kategori); ?></td>
                    <td><?php echo e($p->lokasi ?? '-'); ?></td>
                    <td><?php echo e($p->isi); ?></td>
                    <td><?php echo e($p->tanggapan ?? '-'); ?></td>
                    <td class="center status"><?php echo e($p->status); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8" class="center">Tidak ada data.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH E:\laragon\www\sipades\resources\views\pdf\pengaduan.blade.php ENDPATH**/ ?>