<?php $__env->startSection('title', 'Detail Pengaduan'); ?>
<?php $__env->startSection('subtitle', 'Pengaduan #' . $pengaduan->id); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('partials.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge badge-soft-secondary"><?php echo e($pengaduan->kategori); ?></span>
                        <?php ($badge = ['baru' => 'warning', 'diproses' => 'info', 'selesai' => 'success'][$pengaduan->status]); ?>
                        <span class="badge badge-soft-<?php echo e($badge); ?>"><?php echo e($pengaduan->statusLabel()); ?></span>
                    </div>
                    <div class="small text-muted"><?php echo e($pengaduan->created_at->translatedFormat('d F Y, H:i')); ?></div>
                </div>

                <h6 class="fw-bold">Isi Pengaduan</h6>
                <p style="white-space: pre-line;"><?php echo e($pengaduan->isi); ?></p>

                <table class="table table-sm mt-3">
                    <tr><th class="text-muted fw-normal" width="150">Pelapor</th><td><?php echo e($pengaduan->nama_pelapor ?? $pengaduan->user->name ?? '-'); ?></td></tr>
                    <tr><th class="text-muted fw-normal">Email</th><td><?php echo e($pengaduan->user->email ?? '-'); ?></td></tr>
                    <tr><th class="text-muted fw-normal">Lokasi</th><td><?php echo e($pengaduan->lokasi ?? '-'); ?></td></tr>
                </table>

                <?php if($pengaduan->tanggapan): ?>
                    <div class="alert alert-success mt-3">
                        <div class="fw-semibold mb-1"><i class="bi bi-reply-fill me-1"></i>Tanggapan Tersimpan</div>
                        <div style="white-space: pre-line;"><?php echo e($pengaduan->tanggapan); ?></div>
                        <div class="small text-muted mt-2"><?php echo e($pengaduan->tanggapan_at?->translatedFormat('d F Y, H:i')); ?></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">Beri Tanggapan</h6>
                <form action="<?php echo e(route('admin.pengaduan.update', $pengaduan)); ?>" method="POST" class="d-grid gap-3">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <div>
                        <label class="form-label small fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <?php $__currentLoopData = ['baru' => 'Baru', 'diproses' => 'Diproses', 'selesai' => 'Selesai']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>" <?php if($pengaduan->status === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="form-label small fw-semibold">Tanggapan</label>
                        <textarea name="tanggapan" rows="5" class="form-control" placeholder="Tulis tanggapan untuk pelapor..."><?php echo e(old('tanggapan', $pengaduan->tanggapan)); ?></textarea>
                    </div>
                    <button class="btn btn-hijau"><i class="bi bi-send me-1"></i> Simpan Tanggapan</button>
                    <a href="<?php echo e(route('admin.pengaduan.index')); ?>" class="btn btn-outline-secondary btn-sm">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views\admin\pengaduan\show.blade.php ENDPATH**/ ?>