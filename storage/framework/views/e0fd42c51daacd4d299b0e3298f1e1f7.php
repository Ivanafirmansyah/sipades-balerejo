

<?php $__env->startSection('title', 'Data Penduduk'); ?>

<?php $__env->startPush('styles'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/statistik.css', 'resources/js/statistik.jsx']); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="container">
        <h1>Data Penduduk</h1>
        <p class="mb-0 opacity-75">Statistik kependudukan <?php echo e($profil->nama_desa); ?></p>
    </div>
</div>

<div class="container py-5">
    
    <form action="<?php echo e(route('penduduk.publik')); ?>" method="GET" class="d-flex flex-wrap gap-2 align-items-center mb-4 reveal">
        <span class="small fw-semibold text-muted me-1"><i class="bi bi-funnel me-1"></i>Wilayah:</span>
        <select name="rw" class="form-select form-select-sm w-auto">
            <option value="">Semua RW</option>
            <?php $__currentLoopData = $rwList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($rw); ?>" <?php if(request('rw') == $rw): echo 'selected'; endif; ?>>RW <?php echo e($rw); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select name="rt" class="form-select form-select-sm w-auto">
            <option value="">Semua RT</option>
            <?php $__currentLoopData = $rtList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($rt); ?>" <?php if(request('rt') == $rt): echo 'selected'; endif; ?>>RT <?php echo e($rt); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <button class="btn btn-sm btn-biru px-3">Tampilkan</button>
        <?php if(request()->hasAny(['rt', 'rw'])): ?>
            <a href="<?php echo e(route('penduduk.publik')); ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
        <?php endif; ?>
    </form>

    <div class="row g-3 mb-4">
        <?php $__currentLoopData = [
            [$stats['total'], 'Total Penduduk', 'bi-people-fill'],
            [$stats['laki'], 'Laki-laki', 'bi-gender-male'],
            [$stats['perempuan'], 'Perempuan', 'bi-gender-female'],
            [$stats['kk'], 'Kepala Keluarga', 'bi-house-heart-fill'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-6 col-lg-3">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="icon"><i class="bi <?php echo e($s[2]); ?>"></i></div>
                        <div>
                            <div class="fs-4 fw-bold lh-1 mb-1"><?php echo e(number_format($s[0])); ?></div>
                            <div class="small text-muted"><?php echo e($s[1]); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card h-100 grafik-card">
                <div class="card-body p-4">
                    <h5 class="section-title mb-1">Jumlah Warga per RT/RW</h5>
                    <p class="small text-muted mb-3">Laki-laki dan perempuan di setiap wilayah</p>
                    <div data-grafik="wilayah" data-props='<?php echo json_encode(['data' => $grafik['wilayah'], 'tinggi' => 300], 512) ?>' class="grafik-wadah" style="min-height: 300px"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100 grafik-card">
                <div class="card-body p-4">
                    <h5 class="section-title mb-1">Jenis Kelamin</h5>
                    <p class="small text-muted mb-3">Komposisi warga</p>
                    <div data-grafik="jenis-kelamin" data-props='<?php echo json_encode(['laki' => $stats['laki'], 'perempuan' => $stats['perempuan'], 'tinggi' => 300]) ?>' class="grafik-wadah" style="min-height: 300px"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100 grafik-card">
                <div class="card-body p-4">
                    <h5 class="section-title mb-1">Kelompok Umur</h5>
                    <p class="small text-muted mb-3">Balita (0–4), Anak (5–11), Remaja (12–17), Dewasa (18–59), Lansia (60+)</p>
                    <div data-grafik="umur" data-props='<?php echo json_encode(['data' => $grafik['umur'], 'tinggi' => 280], 512) ?>' class="grafik-wadah" style="min-height: 280px">
                        
                    <?php ($maks = max(1, max($kelompok))); ?>
                    <div class="d-grid gap-3">
                        <?php $__currentLoopData = $kelompok; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nama => $jumlah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="statbar">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="fw-semibold"><?php echo e($nama); ?>

                                        <span class="text-muted fw-normal"><?php echo e(['Balita' => '(0–4 th)', 'Anak' => '(5–11 th)', 'Remaja' => '(12–17 th)', 'Dewasa' => '(18–59 th)', 'Lansia' => '(60+ th)'][$nama] ?? ''); ?></span>
                                    </span>
                                    <span class="fw-bold"><?php echo e($jumlah); ?> <span class="text-muted fw-normal">jiwa</span></span>
                                </div>
                                <div class="statbar-track"><span style="width: <?php echo e(round($jumlah / $maks * 100)); ?>%"></span></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100 grafik-card">
                <div class="card-body p-4">
                    <h5 class="section-title mb-1">Pekerjaan</h5>
                    <p class="small text-muted mb-3">Pekerjaan warga terbanyak</p>
                    <div data-grafik="peringkat" data-props='<?php echo json_encode(['data' => $grafik['pekerjaan'], 'label' => 'Jiwa', 'warna' => 'var(--chart-1)']) ?>' class="grafik-wadah"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100 grafik-card">
                <div class="card-body p-4">
                    <h5 class="section-title mb-1">Agama</h5>
                    <p class="small text-muted mb-3">Jumlah warga menurut agama</p>
                    <div data-grafik="peringkat" data-props='<?php echo json_encode(['data' => $grafik['agama'], 'label' => 'Jiwa', 'warna' => 'var(--chart-4)']) ?>' class="grafik-wadah"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body p-4">
                    <h5 class="section-title mb-3">Rincian per RT/RW</h5>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light"><tr><th>RW</th><th>RT</th><th class="text-end">L</th><th class="text-end">P</th><th class="text-end">Jiwa</th></tr></thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $perRt; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($item->rw); ?></td><td><?php echo e($item->rt); ?></td>
                                        <td class="text-end"><?php echo e((int) $item->laki); ?></td><td class="text-end"><?php echo e((int) $item->perempuan); ?></td>
                                        <td class="text-end fw-semibold"><?php echo e($item->jumlah); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td colspan="5" class="text-center text-muted">Belum ada data.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="offline-ready-note mt-4">
        <i class="bi bi-shield-lock-fill"></i>
        <span>Halaman ini hanya menampilkan statistik. Data pribadi warga (nama, NIK, alamat) dilindungi dan hanya dapat diakses petugas desa yang berwenang, sesuai UU No. 27 Tahun 2022 tentang Pelindungan Data Pribadi.</span>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views/penduduk.blade.php ENDPATH**/ ?>