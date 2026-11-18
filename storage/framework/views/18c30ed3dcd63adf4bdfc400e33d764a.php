

<?php $__env->startSection('title', 'Data Penduduk'); ?>
<?php $__env->startSection('subtitle', 'Kelola data warga dan pengelompokan RT/RW'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('partials.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card"><div class="card-body">
            <div class="small text-muted">Total Penduduk</div>
            <div class="fs-4 fw-bold"><?php echo e(number_format($stats['total'])); ?></div>
        </div></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card"><div class="card-body">
            <div class="small text-muted">Laki-laki</div>
            <div class="fs-4 fw-bold"><?php echo e(number_format($stats['laki'])); ?></div>
        </div></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card"><div class="card-body">
            <div class="small text-muted">Perempuan</div>
            <div class="fs-4 fw-bold"><?php echo e(number_format($stats['perempuan'])); ?></div>
        </div></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card"><div class="card-body">
            <div class="small text-muted">Kepala Keluarga</div>
            <div class="fs-4 fw-bold"><?php echo e(number_format($stats['kk'])); ?></div>
        </div></div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body p-4">
        <form action="<?php echo e(route('admin.penduduk.index')); ?>" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Cari</label>
                <input type="text" name="q" value="<?php echo e(request('q')); ?>" class="form-control form-control-sm" placeholder="Nama / NIK / No. KK">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold">RT</label>
                <select name="rt" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <?php $__currentLoopData = $rtList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($rt); ?>" <?php if(request('rt') == $rt): echo 'selected'; endif; ?>>RT <?php echo e($rt); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold">RW</label>
                <select name="rw" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <?php $__currentLoopData = $rwList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($rw); ?>" <?php if(request('rw') == $rw): echo 'selected'; endif; ?>>RW <?php echo e($rw); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">JK</label>
                <select name="jenis_kelamin" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="L" <?php if(request('jenis_kelamin') === 'L'): echo 'selected'; endif; ?>>Laki-laki</option>
                    <option value="P" <?php if(request('jenis_kelamin') === 'P'): echo 'selected'; endif; ?>>Perempuan</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-sm btn-hijau"><i class="bi bi-funnel me-1"></i> Filter</button>
                <a href="<?php echo e(route('admin.penduduk.index')); ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h6 class="fw-bold mb-0">Daftar Penduduk</h6>
            <div class="d-flex gap-2">
                <a href="<?php echo e(route('admin.penduduk.export', request()->only(['rt', 'rw']))); ?>" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                </a>
                <a href="<?php echo e(route('admin.penduduk.create')); ?>" class="btn btn-sm btn-hijau">
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
                    <?php $__empty_1 = true; $__currentLoopData = $penduduks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $penduduk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="font-monospace small"><?php echo e($penduduk->nik); ?></td>
                            <td class="fw-semibold"><?php echo e($penduduk->nama); ?></td>
                            <td><?php echo e($penduduk->jenis_kelamin); ?></td>
                            <td class="small"><?php echo e($penduduk->tanggal_lahir?->format('d/m/Y') ?? '-'); ?></td>
                            <td class="small text-nowrap">
                                <span class="fw-semibold"><?php echo e($penduduk->umur_teks); ?></span>
                                <?php if($penduduk->kelompok_umur): ?><div><span class="badge badge-soft-<?php echo e(['Balita' => 'info', 'Anak' => 'success', 'Remaja' => 'warning', 'Dewasa' => 'secondary', 'Lansia' => 'danger'][$penduduk->kelompok_umur]); ?>"><?php echo e($penduduk->kelompok_umur); ?></span></div><?php endif; ?>
                            </td>
                            <td><?php echo e($penduduk->rt); ?>/<?php echo e($penduduk->rw); ?></td>
                            <td><span class="badge badge-soft-secondary"><?php echo e($penduduk->status); ?></span></td>
                            <td>
                                <?php ($akun = \App\Services\AkunWarga::status($penduduk)); ?>
                                <span class="badge badge-soft-<?php echo e($akun['warna']); ?> akun-badge" title="Login dengan NIK <?php echo e($penduduk->nik); ?>"><i class="bi <?php echo e($akun['ikon']); ?> me-1"></i><?php echo e($akun['label']); ?></span>
                            </td>
                            <td class="text-end text-nowrap">
                                <?php if($penduduk->user?->role !== 'admin'): ?>
                                    <form action="<?php echo e(route('admin.penduduk.reset-sandi', $penduduk)); ?>" method="POST" class="d-inline"
                                          onsubmit="return confirm('Kembalikan kata sandi <?php echo e($penduduk->nama); ?> ke tanggal lahir?')">
                                        <?php echo csrf_field(); ?>
                                        <button class="btn btn-sm btn-outline-secondary" title="Reset kata sandi ke tanggal lahir"><i class="bi bi-key"></i></button>
                                    </form>
                                <?php endif; ?>
                                <a href="<?php echo e(route('admin.penduduk.edit', $penduduk)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form action="<?php echo e(route('admin.penduduk.destroy', $penduduk)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus data <?php echo e($penduduk->nama); ?>?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="9" class="text-center text-muted py-4">Data penduduk tidak ditemukan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php echo e($penduduks->links()); ?>

    </div>
</div>

<div class="card mt-4">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-3">Pengelompokan per RT/RW</h6>
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead class="table-light"><tr><th>RT</th><th>RW</th><th class="text-end">Jumlah Jiwa</th></tr></thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $perRt; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($item->rt); ?></td>
                            <td><?php echo e($item->rw); ?></td>
                            <td class="text-end fw-semibold"><?php echo e($item->jumlah); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="3" class="text-center text-muted">Belum ada data.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views/admin/penduduk/index.blade.php ENDPATH**/ ?>