<?php $__env->startSection('title', 'Layanan'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="container">
        <h1>Layanan Desa</h1>
        <p class="mb-0 opacity-75">Surat menyurat dan pengaduan masyarakat</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card mb-4">
                <div class="card-body p-4">
                    <h5 class="section-title mb-3"><i class="bi bi-file-earmark-text me-2"></i>Surat Menyurat</h5>
                    <p class="text-muted">Ajukan surat keterangan secara online. Beberapa jenis surat yang tersedia:</p>
                    <div class="row g-2">
                        <?php $__empty_1 = true; $__currentLoopData = $jenisSurats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jenis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="col-md-6">
                                <div class="border rounded p-2 small">
                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                    <span class="fw-semibold"><?php echo e($jenis->nama); ?></span>
                                    <?php if($jenis->keterangan): ?>
                                        <div class="text-muted ms-4"><?php echo e($jenis->keterangan); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-12 small text-muted">Jenis surat belum tersedia.</div>
                        <?php endif; ?>
                    </div>
                    <div class="mt-4">
                        <?php if(auth()->guard()->check()): ?>
                            <a href="<?php echo e(route('user.surat.create')); ?>" class="btn btn-hijau"><i class="bi bi-send me-1"></i> Ajukan Surat Sekarang</a>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="btn btn-hijau"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk untuk Mengajukan Surat</a>
                            <a href="<?php echo e(route('register')); ?>" class="btn btn-outline-success">Daftar Akun</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if($formulirs->count()): ?>
                <div class="card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="section-title mb-0"><i class="bi bi-journal-text me-2"></i>Formulir Online</h5>
                            <a href="<?php echo e(route('formulir.index')); ?>" class="small fw-semibold">Lihat semua</a>
                        </div>
                        <div class="d-grid gap-2">
                            <?php $__currentLoopData = $formulirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $formulir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border rounded p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div>
                                        <div class="fw-semibold small"><?php echo e($formulir->judul); ?></div>
                                        <?php if($formulir->deskripsi): ?>
                                            <div class="small text-muted"><?php echo e($formulir->deskripsi); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($formulir->isDinamis()): ?>
                                        <a href="<?php echo e(route('formulir.show', $formulir->slug)); ?>" class="btn btn-sm btn-biru">Isi <i class="bi bi-arrow-right ms-1"></i></a>
                                    <?php elseif($formulir->link): ?>
                                        <a href="<?php echo e($formulir->link); ?>" target="_blank" class="btn btn-sm btn-outline-success">Buka <i class="bi bi-box-arrow-up-right ms-1"></i></a>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-5" id="pengaduan">
            <div class="card mb-4">
                <div class="card-body p-4">
                    <h5 class="section-title mb-3"><i class="bi bi-megaphone me-2"></i>Layanan Pengaduan</h5>
                    <p class="text-muted small">Sampaikan keluhan, saran, atau laporan terkait pelayanan dan fasilitas desa. Pengaduan akan ditindaklanjuti oleh perangkat desa.</p>
                    <ul class="small text-muted ps-3">
                        <li>Laporan dapat dipantau statusnya.</li>
                        <li>Identitas pelapor hanya dilihat admin.</li>
                        <li>Mohon sampaikan informasi yang benar.</li>
                    </ul>
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('user.pengaduan.create')); ?>" class="btn btn-hijau"><i class="bi bi-pencil-square me-1"></i> Buat Pengaduan</a>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="btn btn-hijau"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk untuk Melapor</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-4">
                    <h5 class="section-title mb-3"><i class="bi bi-info-circle me-2"></i>Alur Pengajuan Surat</h5>
                    <ol class="small text-muted ps-3 mb-0 d-grid gap-2">
                        <li>Daftar / masuk ke akun Anda.</li>
                        <li>Pilih jenis surat dan isi data pemohon.</li>
                        <li>Pantau status pada menu Riwayat Surat.</li>
                        <li>Unduh surat (PDF) setelah status selesai.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views\layanan.blade.php ENDPATH**/ ?>