<?php $__env->startSection('title', 'Agenda Kegiatan'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="container">
        <h1>Agenda Kegiatan</h1>
        <p class="mb-0 opacity-75">Jadwal kegiatan dan acara desa</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <?php $__empty_1 = true; $__currentLoopData = $agendas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $agenda): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-md-6 col-lg-4 reveal" style="transition-delay: <?php echo e(($i % 3) * 100); ?>ms">
                <div class="agenda-foto-card">
                    <div class="agenda-foto">
                        <?php if($agenda->foto): ?>
                            <button type="button" class="agenda-foto-btn" data-bs-toggle="modal" data-bs-target="#fotoAgenda"
                                    data-foto="<?php echo e($agenda->foto_url); ?>" data-judul="<?php echo e($agenda->judul); ?>" aria-label="Perbesar foto <?php echo e($agenda->judul); ?>">
                                <img src="<?php echo e($agenda->foto_url); ?>" alt="<?php echo e($agenda->judul); ?>" loading="lazy">
                                <span class="agenda-zoom"><i class="bi bi-arrows-fullscreen"></i></span>
                            </button>
                        <?php else: ?>
                            <div class="agenda-foto-kosong"><i class="bi bi-calendar-event"></i></div>
                        <?php endif; ?>
                        <div class="agenda-tanggal">
                            <div class="tgl"><?php echo e($agenda->tanggal->format('d')); ?></div>
                            <div class="bln"><?php echo e($agenda->tanggal->translatedFormat('M Y')); ?></div>
                        </div>
                        <?php if($agenda->tanggal->isToday()): ?>
                            <span class="agenda-label hari-ini">Hari ini</span>
                        <?php elseif($agenda->tanggal->isPast()): ?>
                            <span class="agenda-label selesai">Selesai</span>
                        <?php endif; ?>
                    </div>
                    <div class="agenda-isi">
                        <h6><?php echo e($agenda->judul); ?></h6>
                        <div class="small text-muted mb-1"><i class="bi bi-clock me-1"></i><?php echo e($agenda->jam_formatted ?? '-'); ?> WIB</div>
                        <div class="small text-muted mb-2"><i class="bi bi-geo-alt me-1"></i><?php echo e($agenda->lokasi ?? '-'); ?></div>
                        <?php if($agenda->deskripsi): ?>
                            <p class="small text-muted mb-0"><?php echo e($agenda->deskripsi); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12"><div class="alert alert-light border text-center text-muted">Belum ada agenda kegiatan.</div></div>
        <?php endif; ?>
    </div>

    <div class="mt-4"><?php echo e($agendas->links()); ?></div>
</div>


<div class="modal fade" id="fotoAgenda" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0 shadow-none">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-white fw-semibold" id="fotoAgendaJudul"></span>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <img src="" alt="" id="fotoAgendaImg" class="img-fluid rounded-4 w-100">
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.getElementById('fotoAgenda').addEventListener('show.bs.modal', function (e) {
        var b = e.relatedTarget;
        document.getElementById('fotoAgendaImg').src = b.dataset.foto;
        document.getElementById('fotoAgendaImg').alt = b.dataset.judul;
        document.getElementById('fotoAgendaJudul').textContent = b.dataset.judul;
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views\agenda\index.blade.php ENDPATH**/ ?>