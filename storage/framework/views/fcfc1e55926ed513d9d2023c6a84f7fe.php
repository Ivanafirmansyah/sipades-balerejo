<?php $__env->startSection('title', 'Profil Desa'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="container">
        <h1>Profil Desa</h1>
        <p class="mb-0 opacity-75">Mengenal lebih dekat <?php echo e($profil->nama_desa); ?></p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body p-4">
                    <h4 class="section-title mb-3">Tentang Desa</h4>
                    <p style="text-align: justify;" class="text-muted"><?php echo e($profil->deskripsi ?? 'Belum ada deskripsi desa.'); ?></p>

                    <h4 class="section-title mt-4 mb-3">Sejarah Desa</h4>
                    <p style="text-align: justify;" class="text-muted"><?php echo e($profil->sejarah ?? 'Belum ada sejarah desa.'); ?></p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <h5 class="section-title mb-3"><i class="bi bi-eye me-2"></i>Visi</h5>
                            <p class="text-muted mb-0"><?php echo e($profil->visi ?? 'Belum ada visi desa.'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <h5 class="section-title mb-3"><i class="bi bi-list-check me-2"></i>Misi</h5>
                            <div class="text-muted mb-0" style="white-space: pre-line;"><?php echo e($profil->misi ?? 'Belum ada misi desa.'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="section-title mb-3">Identitas Desa</h5>
                    <table class="table table-sm align-middle mb-0">
                        <tr><th class="text-muted fw-normal">Nama Desa</th><td class="fw-semibold"><?php echo e($profil->nama_desa); ?></td></tr>
                        <tr><th class="text-muted fw-normal">Kecamatan</th><td><?php echo e($profil->kecamatan ?? '-'); ?></td></tr>
                        <tr><th class="text-muted fw-normal">Kabupaten</th><td><?php echo e($profil->kabupaten ?? '-'); ?></td></tr>
                        <tr><th class="text-muted fw-normal">Provinsi</th><td><?php echo e($profil->provinsi ?? '-'); ?></td></tr>
                        <tr><th class="text-muted fw-normal">Kode Pos</th><td><?php echo e($profil->kode_pos ?? '-'); ?></td></tr>
                        <tr><th class="text-muted fw-normal">Telepon</th><td><?php echo e($profil->telepon ?? '-'); ?></td></tr>
                        <tr><th class="text-muted fw-normal">Email</th><td><?php echo e($profil->email ?? '-'); ?></td></tr>
                        <tr><th class="text-muted fw-normal">Alamat</th><td><?php echo e($profil->alamat_kantor ?? '-'); ?></td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views\profil.blade.php ENDPATH**/ ?>