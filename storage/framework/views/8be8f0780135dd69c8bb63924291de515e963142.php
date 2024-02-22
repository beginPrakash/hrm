<ul class="dropdown-menu checkbox-menu allow-focus sells_menu" aria-labelledby="dropdownMenu1">
    <?php if(isset($data) && count($data) > 0): ?>
        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <label>
                    <input type="checkbox" class="sells_check" name="sells_list[]" value="<?php echo e($val->id); ?>"><?php echo e($val->item_name); ?>

                </label>
            </li>    
        
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
</ul><?php /**PATH C:\wamp64_new\www\hrm\resources\views/selling_management/sells_p_list.blade.php ENDPATH**/ ?>