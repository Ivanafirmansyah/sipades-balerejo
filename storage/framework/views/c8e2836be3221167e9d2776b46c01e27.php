<?php $__env->startSection('title', 'Formulir'); ?>
<?php $__env->startSection('subtitle', 'Buat formulir surat & pendataan sendiri dengan Form Builder'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('partials.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h6 class="fw-bold mb-0">Daftar Formulir</h6>
            <a href="<?php echo e(route('admin.formulir.create')); ?>" class="btn btn-sm btn-biru"><i class="bi bi-plus-lg me-1"></i> Buat Formulir</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr><th>Judul</th><th>Jenis</th><th>Isian</th><th>Masuk</th><th>Status</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $formulirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $formulir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?php echo e($formulir->judul); ?></div>
                                <div class="small text-muted"><?php echo e(\Illuminate\Support\Str::limit($formulir->deskripsi, 70)); ?></div>
                            </td>
                            <td class="small text-nowrap">
                                <?php if($formulir->isDinamis()): ?>
                                    <span class="badge badge-soft-info"><i class="bi bi-ui-checks me-1"></i>Dinamis</span>
                                    <div class="text-muted mt-1"><?php echo e($formulir->isSurat() ? 'Surat: ' . ($formulir->jenisSurat->nama ?? '-') : 'Pendataan'); ?></div>
                                <?php else: ?>
                                    <span class="badge badge-soft-secondary"><i class="bi bi-link-45deg me-1"></i>Tautan</span>
                                <?php endif; ?>
                            </td>
                            <td class="small"><?php echo e($formulir->isDinamis() ? count($formulir->fields()) . ' isian' : '-'); ?></td>
                            <td>
                                <?php if($formulir->isDinamis()): ?>
                                    <a href="<?php echo e(route('admin.formulir.jawaban', $formulir)); ?>" class="fw-bold text-decoration-none"><?php echo e($formulir->jawabans_count); ?> <span class="small fw-normal">data</span></a>
                                <?php else: ?> - <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-soft-<?php echo e($formulir->is_aktif ? 'success' : 'secondary'); ?>"><?php echo e($formulir->is_aktif ? 'Aktif' : 'Nonaktif'); ?></span>
                                <?php if($formulir->butuh_login): ?><span class="badge badge-soft-warning"><i class="bi bi-lock"></i> Login</span><?php endif; ?>
                            </td>
                            <td class="text-end text-nowrap">
                                <?php if($formulir->isDinamis()): ?>
                                    <a href="<?php echo e(route('formulir.show', $formulir->slug)); ?>" target="_blank" class="btn btn-sm btn-outline-secondary" title="Buka formulir"><i class="bi bi-box-arrow-up-right"></i></a>
                                    <a href="<?php echo e(route('admin.formulir.jawaban', $formulir)); ?>" class="btn btn-sm btn-outline-secondary" title="Lihat isian"><i class="bi bi-inbox"></i></a>
                                <?php elseif($formulir->link): ?>
                                    <a href="<?php echo e($formulir->link); ?>" target="_blank" class="btn btn-sm btn-outline-secondary" title="Buka tautan"><i class="bi bi-box-arrow-up-right"></i></a>
                                <?php endif; ?>
                                <a href="<?php echo e(route('admin.formulir.edit', $formulir)); ?>" class="btn btn-sm btn-outline-primary" title="Ubah"><i class="bi bi-pencil"></i></a>
                                <form action="<?php echo e(route('admin.formulir.destroy', $formulir)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus formulir ini beserta semua isiannya?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada formulir.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php echo e($formulirs->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views/admin/formulir/index.blade.php ENDPATH**/ ?>