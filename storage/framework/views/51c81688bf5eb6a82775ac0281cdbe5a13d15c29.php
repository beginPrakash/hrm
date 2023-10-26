<head> 
    <!-- <script type="text/javascript" src="<?php echo e(asset('assets/js/app.js')); ?>"></script> -->
        <script>$(document).ready(function() {
        // Add More Dept
        $('.add_more_dept_btn').click(function() {
            console.log('sds');
            var element = $('.add_dept_div:last').clone();
            element.find('.dept_select').val('');
            element.find('.title_select').val('');
            var j = $('.add_dept_div').length;
            element.insertAfter($(this).parents().find('.add_dept_div:last'));
            if(j>1){
                //$('.dept_select:last').select2('destroy');
                $('.dept_select:last').attr('id','dept_select'+j);
                $('#dept_select'+j).select2('destroy');
                $('#dept_select'+j).select2();
                $('.title_select:last').attr('id','title_select'+j);
                $('#title_select'+j).select2('destroy');
                $('#title_select'+j).select2();
            }
            if(j == 1){
                  $(".add_more_dept_btn:last").remove();
                  $('.add_btn_div:last').append('<button type="button" class="btn btn-primary plus-minus remove_dept_btn"><i class="fas fa-minus"></i></button>');
              }
            j++;
            if ($('.agenda_div').length > 1) {
                $('.agenda_div').find('.remove_agenda').show();
            }
        });

        //remove row when click remove button
        $(document).on('click','.remove_dept_btn',function(){
            $(this).closest('div').parent().remove();
        });

        $(document).on('change', '.title_select', function() {

            // for department hide/show
            var prio = $(this).find(":selected").data("priority");
            $(this).find('.department_div').show();
            if(prio == '1' || prio == '2')
            {
                $(this).find('.department_div').hide();
                $(this).find('.dep_hid').val(1);
            }

            //for multi user check
        });

        $("#add_leave").on("hidden.bs.modal", function(){
        
            $(".select").val(null).trigger("change");
            $('.leave_id').val('');
            $('.add_dept_div').slice(1).remove();
        });
    });</script></head>
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title leave_m_title"><?php echo e((isset($leaveData->id) && !empty($leaveData->id)) ? 'Edit' : 'Add'); ?> Leave Hierarchy</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form  action="<?php echo e(route('admin_leaves.store')); ?>" method="post" id="admin_leaves_form">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="id" class="leave_id" value="<?php echo e($leaveData->id ?? ''); ?>">
                                    <div class="form-group">
                                        <label>Leave Type <span class="text-danger">*</span></label>
                                        <select class="select" name="leave_type" id="leave_type">

                                            <option value="">Select Leave Type</option>
                                             <?php foreach ($leavetype as $value) {?>

                                            <option value="<?php echo $value->id?>" <?php echo e((isset($leaveData->leave_type) && !empty($leaveData->leave_type) && ($leaveData->leave_type == $value->id)) ? 'selected' : ''); ?>><?php echo $value->name?></option>
                                        <?php }?>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Title<span class="text-danger">*</span></label>
                                                <select class="select main_title" id="main_title" name="main_title">
                                                    <option value="">Select Title</option>
                                                    <?php if(isset($designations) && count($designations) > 0): ?>
                                                        <?php $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($val->id); ?>" data-priority="<?php echo e($val->priority_level); ?>" <?php echo e((isset($leaveData->main_desig_id) && !empty($leaveData->main_desig_id) && ($leaveData->main_desig_id == $val->id)) ? 'selected' : ''); ?>><?php echo e($val->name); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <?php if(isset($leaveData->id) && !empty($leaveData->id)): ?>
                                            <?php if(isset($leaveData->main_dept_id) && !empty($leaveData->main_dept_id)): ?>
                                                <div class="col-sm-6">
                                                    <div class="form-group main_department">
                                                        <label>Department <span class="text-danger">*</span></label>
                                                        <select class="select shift_addschedule addsched" id="main_department" name="main_department">
                                                            <option value="">Select Department</option>
                                                            <?php if(isset($departments) && count($departments) > 0): ?>
                                                                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($val->id); ?>" <?php echo e((isset($leaveData->main_dept_id) && !empty($leaveData->main_dept_id) && ($leaveData->main_dept_id == $val->id)) ? 'selected' : ''); ?>><?php echo e($val->name); ?></option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php endif; ?>
                                                        </select>
                                                        <input type="hidden" name="main_dep_hid" value="0" class="main_dep_hid">
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="col-sm-6">
                                                    <div class="form-group main_department" style="display:none">
                                                        <label>Department <span class="text-danger">*</span></label>
                                                        <select class="select shift_addschedule addsched" id="main_department" name="main_department">
                                                            <option value="">Select Department</option>
                                                            <?php if(isset($departments) && count($departments) > 0): ?>
                                                                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($val->id); ?>"><?php echo e($val->name); ?></option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php endif; ?>
                                                        </select>
                                                        <input type="hidden" name="main_dep_hid" value="0" class="main_dep_hid">
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <div class="col-sm-6">
                                                    <div class="form-group main_department">
                                                        <label>Department <span class="text-danger">*</span></label>
                                                        <select class="select shift_addschedule addsched" id="main_department" name="main_department">
                                                            <option value="">Select Department</option>
                                                            <?php if(isset($departments) && count($departments) > 0): ?>
                                                                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($val->id); ?>"><?php echo e($val->name); ?></option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php endif; ?>
                                                        </select>
                                                        <input type="hidden" name="main_dep_hid" value="0" class="main_dep_hid">
                                                    </div>
                                                </div>
                                        <?php endif; ?>
                                        
                                    </div>
                                    <div class="form-group">
                                        <label>Select Approver Title and Department</label>
                                    </div>
                                    <?php if(isset($leaveData) && !empty($leaveData)): ?>
                                        <?php $decode_data = json_decode($leaveData->leave_hierarchy); ?>
                                        <?php if(!empty($decode_data) && count($decode_data) > 0): ?>
                                            <?php $__currentLoopData = $decode_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dkey => $dval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="row add_dept_div">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <select class="select title_select" name="sub_title[]">
                                                                <option value="">Select Title</option>
                                                                <?php if(isset($designations) && count($designations) > 0): ?>
                                                                    <?php $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option value="<?php echo e($val->id); ?>" data-priority="<?php echo e($val->priority_level); ?>" <?php echo e(($dval->desig == $val->id) ? 'selected' : ''); ?>><?php echo e($val->name); ?></option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <?php endif; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <?php if(isset($dval->dept) && !empty($dval->dept)): ?>
                                                    <div class="col-md-5 department_div">
                                                        <div class="form-group">
                                                            <select class="select dept_select" name="sub_department[]">
                                                                <option value="">Select Department</option>
                                                                <?php if(isset($departments) && count($departments) > 0): ?>
                                                                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option value="<?php echo e($val->id); ?>" <?php echo e(($dval->dept == $val->id) ? 'selected' : ''); ?>><?php echo e($val->name); ?></option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <?php endif; ?>
                                                            </select>
                                                            <input type="hidden" name="dep_hid" value="0" class="dep_hid">
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                    <div class="col-md-2 add_btn_div">
                                                        <?php if($dkey == 0): ?>
                                                            <button type="button" class="btn btn-success <?php echo e((isset($leaveData->id) && !empty($leaveData->id)) ? 'add_more_dept_btn' : 'add_more_dept_btn'); ?>"><i class="fa fa-plus"></i></button>
                                                        <?php else: ?>
                                                            <button type="button" class="btn btn-primary plus-minus <?php echo e((isset($leaveData->id) && !empty($leaveData->id)) ? 'remove_dept_btn' : 'remove_dept_btn'); ?>"><i class="fa fa-minus"></i></button>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                    <div class="row add_dept_div">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <select class="title_select" name="sub_title[]">
                                                    <option value="">Select Title</option>
                                                    <?php if(isset($designations) && count($designations) > 0): ?>
                                                        <?php $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($val->id); ?>" data-priority="<?php echo e($val->priority_level); ?>"><?php echo e($val->name); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-5 department_div">
                                            <div class="form-group">
                                                <select class="dept_select" name="sub_department[]">
                                                    <option value="">Select Department</option>
                                                    <?php if(isset($departments) && count($departments) > 0): ?>
                                                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($val->id); ?>"><?php echo e($val->name); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                </select>
                                                <input type="hidden" name="dep_hid" value="0" class="dep_hid">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2 add_btn_div">
                                            <button type="button" class="btn btn-success add_more_dept_btn"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <div class="submit-section">
                                        <button class="btn btn-primary submit-btn" type="submit" id="addLeaveBtn">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div><?php /**PATH C:\wamp64_new\www\hrm\resources\views/policies/admin_leave_modal.blade.php ENDPATH**/ ?>