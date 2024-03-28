<script type="text/javascript" src="<?php echo e(asset('assets/js/app.js')); ?>"></script>

<script>
    $("#addEditForm").validate({
        rules: {
            role_id: {
                required : true
            },
            employee_id: {
                required : true
            },
        },    
        messages: {
            role_id: {
                required : 'Role is required',
            },
            employee_id: {
                required : 'Employee is required',
            },
        },
        errorPlacement: function (error, element) {
            if (element.prop("type") == "text" || element.prop("type") == "number" || element.prop("type") == "textarea") {
                error.insertAfter(element);
            } else {
                error.insertAfter(element.parent());
            }
        },
    });

    $(document).on('click','.company_check',function(){
        $('.branch_menu').remove();
        var sel_val = $('.company_check:checked').map(function() {
            return this.value;
        }).get().join(',');

        $.ajax({
            url: "<?php echo e(route('sales_target.branchlistbycompany')); ?>",
            type: "POST",
            dataType: "json",
            data: {"_token": "<?php echo e(csrf_token()); ?>", sel_val:sel_val},
            success:function(response)
                {
                    $('.branch_checklist').after(response.html).fadeIn();
                }
        });
    });
    $('.selectwith_search').select2({
		minimumResultsForSearch: 1,
		width: '100%'
	});


</script>
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title leave_m_title"><?php echo e((isset($rolesData->id) && !empty($rolesData->id)) ? 'Edit' : 'Add'); ?> User Role</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form  action="<?php echo e(route('user_roles.store')); ?>" method="post" id="addEditForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" class="role_id" value="<?php echo e($rolesData->id ?? ''); ?>">
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Roles <span class="text-danger">*</span></label>
                            <select class="select editsched" name="role_id" id="role_id">
                                <option value="">Select Roles</option>
                                <?php if(isset($role_list) && count($role_list) > 0): ?>  
                                    <?php $__currentLoopData = $role_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e((isset($rolesData->role_id) && ($key == $rolesData->role_id)) ? 'selected' : ''); ?>><?php echo e($val); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label class="col-form-label">Employee<span class="text-danger">*</span></label>
                            <select class="selectwith_search editsched" name="emp_id">
                                <option value="">Select Employee Profile</option>
                                <?php if(isset($emp_list) && count($emp_list) > 0): ?>  
                                    <?php $__currentLoopData = $emp_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>" <?php echo e((isset($rolesData->employee_id) && ($val->id == $rolesData->employee_id)) ? 'selected' : ''); ?>><?php echo e($val->first_name); ?> <?php echo e($val->last_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6"> 
                        <div class="form-group form-focus select-focus">
                            <label class="col-form-label">Select Company <span class="text-danger">*</span></label>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" 
                                        id="dropdownMenu1" data-toggle="dropdown" 
                                        aria-haspopup="true" aria-expanded="true">
                                    Select Company
                                
                                </button>
                                <ul class="dropdown-menu checkbox-menu allow-focus" aria-labelledby="dropdownMenu1">
                                    <?php if(isset($company_list) && count($company_list) > 0): ?>  
                                        <?php $__currentLoopData = $company_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php $selected = ''; ?>
                                            <?php if(isset($rolesData) && !empty($rolesData)): ?>
                                            <?php $com_data = _get_company_name_by_uroles($rolesData->id); ?> 
                                            <?php if(!empty($com_data)): ?>
                                                    <?php
                                                    if (in_array($key, $com_data)) { 
                                                        $selected = 'checked';
                                                    } else { 
                                                        $selected = '';
                                                    } 
                                                    ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <li>
                                                <label>
                                                    <input type="checkbox" class="company_check select_change" name="company[]" value="<?php echo e($key); ?>" <?php echo e($selected); ?>><?php echo e($val); ?>

                                                </label>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </ul>
                            </div> 
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6"> 
                        <div class="form-group form-focus select-focus">
                            <label class="col-form-label">Select Branch <span class="text-danger">*</span></label>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle branch_checklist" type="button" 
                                        id="dropdownMenu1" data-toggle="dropdown" 
                                        aria-haspopup="true" aria-expanded="true">
                                    Select Branch
                                </button>
                                
                                <?php if(isset($rolesData) && !empty($rolesData)): ?>
                                <ul class="dropdown-menu checkbox-menu allow-focus" aria-labelledby="dropdownMenu1">
                                    <?php if(isset($branch_data) && count($branch_data) > 0): ?> 
                                        <?php $__currentLoopData = $branch_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        
                                            <?php $selected = ''; ?>
                                            
                                            <?php $br_data = _get_branch_by_uroles($rolesData->id); ?> 
                                            <?php if(!empty($br_data)): ?>
                                                    <?php
                                                    if (in_array($val->id, $br_data)) { 
                                                        $selected = 'checked';
                                                    } else { 
                                                        $selected = '';
                                                    } 
                                                    ?>
                                                <?php endif; ?>
                                            
                                            <li>
                                                <label>
                                                    <input type="checkbox" class="select_change" name="brnach_list[]" value="<?php echo e($val->id); ?>" <?php echo e($selected); ?>><?php echo e($val->name); ?>

                                                </label>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </ul>
                                <?php endif; ?>
                            </div> 
                        </div>
                    </div>
                </div>
               <div class="submit-section">
                    <button class="btn btn-primary submit-btn" type="submit" id="addBonusBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH C:\wamp64_new\www\hrm\resources\views/roles_permission/user_roles_modal.blade.php ENDPATH**/ ?>