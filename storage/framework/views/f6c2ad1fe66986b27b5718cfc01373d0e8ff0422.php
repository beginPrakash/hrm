<script type="text/javascript" src="<?php echo e(asset('assets/js/app.js')); ?>"></script>
<script>
    $('.user_select').select2({
        minimumResultsForSearch: 4,
        width: '100%',
        //allowClear: true,
        dropdownParent: $(".modal_div"),
    });
    $("#addEditForm").validate({
        rules: {
            employee_id: {
                required : true},
            bonus_date:  {
                required : true},
            bonus_amount:  {
                required : true},
            title:  {
                required : true
            },
        },    
        messages: {
            employee_id: {
                required : 'User is required',
            },
            bonus_date: {
                required : 'Bonus Date is required',
            }
            ,
            bonus_amount: {
                required : 'Bonus Amount is required',
            },
            title: {
                required : 'Title is required',
            }
        },
        errorPlacement: function (error, element) {
            if (element.prop("type") == "text" || element.prop("type") == "number" || element.prop("type") == "textarea") {
                error.insertAfter(element);
            } else {
                error.insertAfter(element.parent());
            }
        },
    });


</script>
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title leave_m_title"><?php echo e((isset($bonusData->id) && !empty($bonusData->id)) ? 'Edit' : 'Add'); ?> Bonus</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form  action="<?php echo e(url('store_bonus')); ?>" method="post" id="addEditForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" class="leave_id" value="<?php echo e($bonusData->id ?? ''); ?>">
                <div class="form-group">
                    <label>Select User<span class="text-danger">*</span></label>
                    <select class="user_select" name="employee_id" id="employee_id">
                        <option value="">Select</option>
                        <?php if(isset($userdetails) && count($userdetails) > 0): ?>
                            <?php $__currentLoopData = $userdetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($val->id); ?>" <?php echo e((isset($bonusData->employee_id) && ($val->id == $bonusData->employee_id)) ? 'selected' : ''); ?>><?php echo e($val->first_name); ?> <?php echo e($val->last_name ?? ''); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date <span class="text-danger">*</span></label>
                    <div class="cal-iconx">
                        <input class="form-control datetimepicker_fromx" type="date" name="bonus_date" min="" value="<?php echo e($bonusData->bonus_date ?? ''); ?>" id="bonus_date">
                    </div>
                </div>
                <div class="form-group">
                    <label>Amount <span class="text-danger">*</span></label>
                    <input class="form-control" type="number" name="bonus_amount" id="bonus_amount" min="1" value="<?php echo e($bonusData->bonus_amount ?? 0); ?>">
                </div>
                <div class="form-group">
                    <label>Title <span class="text-danger">*</span></label>
                    <textarea rows="4" class="form-control" name="title" id="title"><?php echo e($bonusData->title ?? ''); ?></textarea>
                </div>
                <div class="submit-section">
                    <button class="btn btn-primary submit-btn" type="submit" id="addBonusBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH C:\wamp64_new\www\hrm\resources\views/lts/bonus_modal.blade.php ENDPATH**/ ?>