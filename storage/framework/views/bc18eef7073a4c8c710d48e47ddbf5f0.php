<?php $__env->startSection('title', 'Agenda Kegiatan'); ?>
<?php $__env->startSection('subtitle', 'Kelola jadwal kegiatan desa'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('partials.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Daftar Agenda</h6>
            <a href="<?php echo e(route('admin.agenda.create')); ?>" class="btn btn-sm btn-hijau"><i class="bi bi-plus-lg me-1"></i> Tambah Agenda</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr><th>Foto</th><th>Tanggal</th><th>Jam</th><th>Judul</th><th>Lokasi</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $agendas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agenda): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <?php if($agenda->foto): ?>
                                    <img src="<?php echo e($agenda->foto_url); ?>" alt="" class="agenda-thumb">
                                <?php else: ?>
                                    <span class="agenda-thumb agenda-thumb-kosong"><i class="bi bi-image"></i></span>
                                <?php endif; ?>
                            </td>
                            <td class="small"><?php echo e($agenda->tanggal->translatedFormat('d M Y')); ?></td>
                            <td class="small"><?php echo e($agenda->jam_formatted ?? '-'); ?></td>
                            <td>
                                <div class="fw-semibold"><?php echo e($agenda->judul); ?></div>
                                <?php if($agenda->deskripsi): ?><div class="small text-muted"><?php echo e(\Illuminate\Support\Str::limit($agenda->deskripsi, 60)); ?></div><?php endif; ?>
                            </td>
                            <td class="small"><?php echo e($agenda->lokasi ?? '-'); ?></td>
                            <td class="text-end text-nowrap">
                                <a href="<?php echo e(route('admin.agenda.edit', $agenda)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form action="<?php echo e(route('admin.agenda.destroy', $agenda)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus agenda ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada agenda.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php echo e($agendas->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sipades\resources\views/admin/agenda/index.blade.php ENDPATH**/ ?>