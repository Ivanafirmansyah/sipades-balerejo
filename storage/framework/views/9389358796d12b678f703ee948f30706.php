
<li style="--tunda: <?php echo e($level * 140); ?>ms">
    <div class="simpul-wadah">
        <div class="simpul <?php echo e($level === 0 ? 'simpul-akar' : ''); ?>" tabindex="0">
            <span class="simpul-foto">
                <?php if($p->foto): ?>
                    <img src="<?php echo e($p->foto_url); ?>" alt="<?php echo e($p->nama); ?>" loading="lazy">
                <?php else: ?>
                    <?php echo e($p->inisial); ?>

                <?php endif; ?>
            </span>
            <span class="simpul-jabatan"><?php echo e($p->jabatan); ?></span>
            <span class="simpul-nama"><?php echo e($p->nama); ?></span>
            <?php if($p->keterangan): ?><span class="simpul-ket"><?php echo e($p->keterangan); ?></span><?php endif; ?>
        </div>

        
        <?php if($level === 0 && $mitra->isNotEmpty()): ?>
            <div class="mitra-wadah">
                <?php $__currentLoopData = $mitra; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="simpul simpul-mitra" tabindex="0">
                        <span class="simpul-foto">
                            <?php if($m->foto): ?><img src="<?php echo e($m->foto_url); ?>" alt="<?php echo e($m->nama); ?>" loading="lazy"><?php else: ?><?php echo e($m->inisial); ?><?php endif; ?>
                        </span>
                        <span class="simpul-jabatan"><?php echo e($m->jabatan); ?></span>
                        <span class="simpul-nama"><?php echo e($m->nama); ?></span>
                        <?php if($m->keterangan): ?><span class="simpul-ket"><?php echo e($m->keterangan); ?></span><?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if($p->bawahan->isNotEmpty()): ?>
        <ul>
            <?php $__currentLoopData = $p->bawahan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('struktur._simpul', ['p' => $b, 'level' => $level + 1, 'mitra' => $mitra], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    <?php endif; ?>
</li>
<?php /**PATH E:\laragon\www\sipades\resources\views/struktur/_simpul.blade.php ENDPATH**/ ?>