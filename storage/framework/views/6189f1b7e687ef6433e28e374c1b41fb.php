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
        <h3>Pemerintah Kabupaten <?php echo e($profil->kabupaten ?? '-'); ?></h3>
        <h3>Kecamatan <?php echo e($profil->kecamatan ?? '-'); ?></h3>
        <h2>Desa <?php echo e($profil->nama_desa ?? '-'); ?></h2>
        <p><?php echo e($profil->alamat_kantor ?? ''); ?></p>
    </div>

    <div class="judul">
        <h4>Laporan Data Penduduk</h4>
        <p>
            <?php if(!empty($filter['rt'])): ?> RT <?php echo e($filter['rt']); ?> <?php endif; ?>
            <?php if(!empty($filter['rw'])): ?> RW <?php echo e($filter['rw']); ?> <?php endif; ?>
            <?php if(empty($filter['rt']) && empty($filter['rw'])): ?> Seluruh Wilayah Desa <?php endif; ?>
            &middot; Dicetak: <?php echo e(now()->translatedFormat('d F Y H:i')); ?> WIB
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
            <?php $__empty_1 = true; $__currentLoopData = $penduduks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="center"><?php echo e($i + 1); ?></td>
                    <td><?php echo e($p->nik); ?></td>
                    <td><?php echo e($p->nama); ?></td>
                    <td class="center"><?php echo e($p->jenis_kelamin); ?></td>
                    <td><?php echo e($p->tempat_lahir ?? '-'); ?>, <?php echo e($p->tanggal_lahir?->format('d/m/Y') ?? '-'); ?></td>
                    <td class="center"><?php echo e($p->umur_teks); ?></td>
                    <td><?php echo e($p->agama ?? '-'); ?></td>
                    <td><?php echo e($p->pekerjaan ?? '-'); ?></td>
                    <td><?php echo e($p->alamat ?? '-'); ?></td>
                    <td class="center"><?php echo e($p->rt); ?></td>
                    <td class="center"><?php echo e($p->rw); ?></td>
                    <td><?php echo e($p->status); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="12" class="center">Tidak ada data.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        Total penduduk: <strong><?php echo e($penduduks->count()); ?></strong> jiwa &middot;
        Laki-laki: <?php echo e($penduduks->where('jenis_kelamin', 'L')->count()); ?> &middot;
        Perempuan: <?php echo e($penduduks->where('jenis_kelamin', 'P')->count()); ?> &middot;
        Kepala Keluarga: <?php echo e($penduduks->where('status', 'Kepala Keluarga')->count()); ?>

    </div>
</body>
</html>
<?php /**PATH E:\laragon\www\sipades\resources\views\pdf\penduduk.blade.php ENDPATH**/ ?>