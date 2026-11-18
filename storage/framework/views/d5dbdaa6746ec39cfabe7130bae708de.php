
<?php
    $nm = 'data[' . $f['name'] . ']';
    $id = 'f_' . $f['name'];
    $req = !empty($f['required']);
    $old = old('data.' . $f['name']);
?>

<?php if($f['type'] === 'section'): ?>
    <div class="col-12">
        <div class="form-section-title"><?php echo e($f['label']); ?></div>
        <?php if(!empty($f['help'])): ?><div class="form-text mt-0"><?php echo e($f['help']); ?></div><?php endif; ?>
    </div>
<?php else: ?>
    <div class="<?php echo e(($f['width'] ?? 'full') === 'half' ? 'col-md-6' : 'col-12'); ?>" data-field="<?php echo e($f['name']); ?>">
        <label class="form-label" <?php if(!in_array($f['type'], ['radio', 'checkbox'])): ?> for="<?php echo e($id); ?>" <?php endif; ?>>
            <?php echo e($f['label']); ?> <?php if($req): ?><span class="text-danger">*</span><?php endif; ?>
        </label>

        <?php switch($f['type']):
            case ('textarea'): ?>
                <textarea id="<?php echo e($id); ?>" name="<?php echo e($nm); ?>" rows="4" class="form-control" placeholder="<?php echo e($f['placeholder'] ?? ''); ?>" maxlength="5000" <?php if($req): echo 'required'; endif; ?>><?php echo e($old); ?></textarea>
                <?php break; ?>
            <?php case ('select'): ?>
                <select id="<?php echo e($id); ?>" name="<?php echo e($nm); ?>" class="form-select" <?php if($req): echo 'required'; endif; ?>>
                    <option value=""><?php echo e(($f['placeholder'] ?? '') ?: 'Pilih...'); ?></option>
                    <?php $__currentLoopData = $f['options']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($o); ?>" <?php if($old === $o): echo 'selected'; endif; ?>><?php echo e($o); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php break; ?>
            <?php case ('radio'): ?>
                <div class="pilihan-group">
                    <?php $__currentLoopData = $f['options']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="<?php echo e($nm); ?>" id="<?php echo e($id . '_' . $i); ?>" value="<?php echo e($o); ?>" <?php if($old === $o): echo 'checked'; endif; ?> <?php if($req): echo 'required'; endif; ?>>
                            <label class="form-check-label" for="<?php echo e($id . '_' . $i); ?>"><?php echo e($o); ?></label>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php break; ?>
            <?php case ('checkbox'): ?>
                <div class="pilihan-group" <?php if($req): ?> data-required-group <?php endif; ?>>
                    <?php $__currentLoopData = $f['options']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="<?php echo e($nm); ?>[]" id="<?php echo e($id . '_' . $i); ?>" value="<?php echo e($o); ?>" <?php if(in_array($o, (array) $old, true)): echo 'checked'; endif; ?>>
                            <label class="form-check-label" for="<?php echo e($id . '_' . $i); ?>"><?php echo e($o); ?></label>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php break; ?>
            <?php case ('nik'): ?>
                <input id="<?php echo e($id); ?>" name="<?php echo e($nm); ?>" type="text" inputmode="numeric" pattern="[0-9]{16}" maxlength="16" class="form-control" placeholder="<?php echo e(($f['placeholder'] ?? '') ?: '16 digit NIK'); ?>" value="<?php echo e($old); ?>" title="NIK harus 16 digit angka" <?php if($req): echo 'required'; endif; ?>>
                <?php break; ?>
            <?php case ('number'): ?>
                <input id="<?php echo e($id); ?>" name="<?php echo e($nm); ?>" type="number" step="any" class="form-control" placeholder="<?php echo e($f['placeholder'] ?? ''); ?>" value="<?php echo e($old); ?>"
                       <?php if(isset($f['min'])): ?> min="<?php echo e($f['min']); ?>" <?php endif; ?> <?php if(isset($f['max'])): ?> max="<?php echo e($f['max']); ?>" <?php endif; ?> <?php if($req): echo 'required'; endif; ?>>
                <?php break; ?>
            <?php case ('date'): ?>
                <input id="<?php echo e($id); ?>" name="<?php echo e($nm); ?>" type="date" class="form-control" value="<?php echo e($old); ?>" <?php if($req): echo 'required'; endif; ?>>
                <?php break; ?>
            <?php case ('email'): ?>
                <input id="<?php echo e($id); ?>" name="<?php echo e($nm); ?>" type="email" class="form-control" placeholder="<?php echo e($f['placeholder'] ?? ''); ?>" value="<?php echo e($old); ?>" maxlength="150" <?php if($req): echo 'required'; endif; ?>>
                <?php break; ?>
            <?php case ('tel'): ?>
                <input id="<?php echo e($id); ?>" name="<?php echo e($nm); ?>" type="tel" inputmode="tel" pattern="[0-9+\-\s]{6,20}" class="form-control" placeholder="<?php echo e(($f['placeholder'] ?? '') ?: '08xxxxxxxxxx'); ?>" value="<?php echo e($old); ?>" <?php if($req): echo 'required'; endif; ?>>
                <?php break; ?>
            <?php default: ?>
                <input id="<?php echo e($id); ?>" name="<?php echo e($nm); ?>" type="text" class="form-control" placeholder="<?php echo e($f['placeholder'] ?? ''); ?>" value="<?php echo e($old); ?>" maxlength="255" <?php if($req): echo 'required'; endif; ?>>
        <?php endswitch; ?>

        <?php if(!empty($f['help'])): ?><div class="form-text"><?php echo e($f['help']); ?></div><?php endif; ?>
        <div class="invalid-feedback d-block field-error"><?php $__errorArgs = ['data.' . $f['name']];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><?php echo e($message); ?><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
    </div>
<?php endif; ?>
<?php /**PATH C:\laragon\www\sipades\resources\views/formulir/_field.blade.php ENDPATH**/ ?>