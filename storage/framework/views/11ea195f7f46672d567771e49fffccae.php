

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

            
            <div class="konflik-card mb-3" id="konflikBox" hidden></div>

            
            <div class="card isian-saya mb-3" id="isianSaya" hidden>
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-1"></i> Isian Anda</h6>
                        <span class="small text-muted">Salah isi? Koreksi selama belum diproses petugas.</span>
                    </div>
                    <ul class="isian-list" id="isianList"></ul>
                </div>
            </div>
            <script type="application/json" id="isianSayaData"><?php echo json_encode($isianSaya, 15, 512) ?></script>

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
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <button type="button" class="btn btn-kuning rounded-pill px-4" id="koreksiBaru" hidden><i class="bi bi-pencil-square me-1"></i> Koreksi Isian Ini</button>
                            <button type="button" class="btn btn-outline-biru rounded-pill px-4" id="isiLagi"><i class="bi bi-arrow-repeat me-1"></i> Isi Lagi</button>
                        </div>
                    </div>

                    <form action="<?php echo e(route('formulir.kirim', $formulir->slug)); ?>" method="POST" class="row g-3 dyn-form" id="dynForm" novalidate
                          data-offline-form
                          data-slug="<?php echo e($formulir->slug); ?>"
                          data-judul="<?php echo e($formulir->judul); ?>"
                          data-pesan="<?php echo e($formulir->pesan_sukses ?: 'Terima kasih, isian Anda sudah kami terima.'); ?>"
                          data-url-ubah="<?php echo e(route('formulir.ubah', $formulir->slug)); ?>"
                          data-url-isian="<?php echo e(url('/formulir/' . $formulir->slug . '/isian')); ?>"
                          <?php if($formulir->butuh_login && !auth()->check()): ?> data-locked <?php endif; ?>>
                        <?php echo csrf_field(); ?>
                        <div class="col-12" id="modeKoreksi" hidden>
                            <div class="mode-koreksi">
                                <i class="bi bi-pencil-square"></i>
                                <div class="flex-grow-1"><b>Mode koreksi</b> — <span id="modeKoreksiInfo"></span><div class="small">Ubah isian yang salah lalu simpan. Data lama tetap tersimpan sebagai riwayat.</div></div>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="batalKoreksi">Batal</button>
                            </div>
                        </div>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views\formulir\show.blade.php ENDPATH**/ ?>