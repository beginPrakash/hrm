<ul class="dropdown-menu checkbox-menu allow-focus branch_menu" aria-labelledby="dropdownMenu1">
    <?php if(isset($data) && count($data) > 0): ?>
        <?php $res = ''; ?>
        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($res != $val->residency): ?>
                <?php $res = $val->residency; ?>
                <?php if($key != 0): ?>
                <hr class="hr_line">
                <?php endif; ?>
            
            <?php endif; ?>
            <li>
                <label>
                    <input type="checkbox" class="branch_check" name="brnach_list[]" value="<?php echo e($val->id); ?>"><?php echo e($val->name); ?>

                </label>
            </li>    
        
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
</ul><?php /**PATH C:\wamp64_new\www\hrm\resources\views/selling_management/branch_list.blade.php ENDPATH**/ ?>