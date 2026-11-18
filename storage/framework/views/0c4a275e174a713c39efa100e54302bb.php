<?php $__env->startSection('title', $formulir->judul); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="container">
        <h1><?php echo e($formulir->judul); ?></h1>
        <p class="mb-0 opacity-75"><?php echo e($formulir->isSurat() ? 'Formulir pengajuan ' . ($formulir->jenisSurat->nama ?? 'surat') : 'Formulir online ' . ($profil->nama_desa ?? 'desa')); ?></p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4 justify-content-center">
        <div class="col-lg-8">
            <div class="offline-ready-note mb-3" id="offlineReady" hidden>
                <i class="bi bi-cloud-check-fill"></i>
                <span><b>Bisa diisi tanpa sinyal.</b> Jika koneksi terputus, isian disimpan di perangkat ini lalu terkirim otomatis saat sinyal kembali.</span>
            </div>

            <div class="card">
                <div class="card-body p-4 p-md-5">
                    <?php if($formulir->deskripsi): ?>
                        <p class="text-muted"><?php echo e($formulir->deskripsi); ?></p>
                    <?php endif; ?>

                    <?php if($formulir->butuh_login && !auth()->check()): ?>
                        <div class="alert alert-warning d-flex gap-2 align-items-start">
                            <i class="bi bi-lock-fill"></i>
                            <div>Formulir ini memerlukan akun warga. <a href="<?php echo e(route('login')); ?>" class="fw-bold">Masuk</a> atau <a href="<?php echo e(route('register')); ?>" class="fw-bold">daftar</a> terlebih dahulu.</div>
                        </div>
                    <?php endif; ?>

                    <div class="form-success" id="formSuccess" hidden>
                        <div class="success-icon"><i class="bi bi-check-lg"></i></div>
                        <h4 class="fw-bold" id="successTitle">Terkirim!</h4>
                        <p class="text-muted mb-4" id="successText"></p>
                        <button type="button" class="btn btn-outline-biru rounded-pill px-4" id="isiLagi"><i class="bi bi-arrow-repeat me-1"></i> Isi Lagi</button>
                    </div>

                    <form action="<?php echo e(route('formulir.kirim', $formulir->slug)); ?>" method="POST" class="row g-3 dyn-form" id="dynForm" novalidate
                          data-offline-form
                          data-slug="<?php echo e($formulir->slug); ?>"
                          data-judul="<?php echo e($formulir->judul); ?>"
                          data-pesan="<?php echo e($formulir->pesan_sukses ?: 'Terima kasih, isian Anda sudah kami terima.'); ?>"
                          <?php if($formulir->butuh_login && !auth()->check()): ?> data-locked <?php endif; ?>>
                        <?php echo csrf_field(); ?>
                        <div class="col-12"><div class="alert alert-danger small mb-0" id="formAlert" hidden></div></div>

                        <?php $__currentLoopData = $formulir->schema['fields'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo $__env->make('formulir._field', ['f' => $f], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <div class="col-12 d-flex flex-wrap align-items-center gap-3 mt-4">
                            <button class="btn btn-kuning rounded-pill px-4 py-2" id="btnKirim" <?php if($formulir->butuh_login && !auth()->check()): ?> disabled <?php endif; ?>>
                                <span class="label-kirim"><i class="bi bi-send-fill me-1"></i> Kirim Formulir</span>
                                <span class="label-proses" hidden><span class="spinner-border spinner-border-sm me-1"></span> Mengirim...</span>
                            </button>
                            <span class="small text-muted" id="draftInfo"><i class="bi bi-floppy me-1"></i>Isian otomatis disimpan sementara di perangkat ini</span>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="<?php echo e(route('formulir.index')); ?>" class="small text-decoration-none"><i class="bi bi-arrow-left me-1"></i> Semua formulir</a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views/formulir/show.blade.php ENDPATH**/ ?>