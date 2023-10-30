<script type="text/javascript" src="<?php echo e(asset('assets/js/app.js')); ?>"></script>
<script>
    $.validator.addMethod("checkreleave", function (value, element) {
        var result = true;

        if($('#leave_type').val() == 1){
                    var emp_remaining_leave = $('#emp_remaining_leave').val();
                }else if($('#leave_type').val() == 2){
                    var emp_remaining_leave = $('#emp_remainingsick_leave').val();
                }
                var days = $('#no_of_days').val();
                if(parseInt(days) <= parseInt(emp_remaining_leave)){
                    result =  true;
                }else{
                    //$('#rl_count_err').text('Remaing leave balance is 0.Please select unpaid leave');
                    result = false;
                }

        return this.optional(element) || result;
    }, "Insufficient no of leaves.");
    $("#addEditForm").validate({
        rules: {
            leave_type: {
                required : true},
            from_date:  {
                required : true},
            to_date:  {
                required : true},
            days:  {
                required : true},
            remaining_leaves:  {
                checkreleave: true,
            },
                leave_reason:  {
                required : true},
        },
        messages: {
            leave_type: {
                required : 'Leave Type is required',
            },
                from_date: {
                required : 'From Date is required',
            }
            ,
            to_date: {
                required : 'To Date is required',
            },
            days: {
                required : 'Days is required',
            },
            remaining_leaves: {
                required : 'Remaining leave balance is 0.Please select unpaid leave',
            },
            leave_reason: {
                required : 'Leaves reason is required',
            }
        },
        errorPlacement: function (error, element) {
            if (element.prop("type") == "text" || element.prop("type") == "textarea") {
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
                                <h5 class="modal-title leave_m_title"><?php echo e((isset($leaveData->id) && !empty($leaveData->id)) ? 'Edit' : 'Add'); ?> Leave</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form  action="/leaveInsert" method="post" id="addEditForm">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="id" class="leave_id" value="<?php echo e($leaveData->id ?? ''); ?>">
                                    <input type="hidden" id="edit_days" value="<?php echo e($leaveData->leave_days ?? 0); ?>">
                                    <div class="form-group">
                                        <label>Leave Type <span class="text-danger">*</span></label>
                                        <select class="select" name="leave_type" id="leave_type">

                                            <option value="">Select</option>
                                             <?php foreach ($leavetype as $value) {?>

                                            <option value="<?php echo $value->id?>" data-id="<?php echo $value->days?>" <?php echo e((isset($leaveData->leave_type) && ($value->id == $leaveData->leave_type)) ? 'selected' : ''); ?>><?php echo $value->name?></option>
                                        <?php }?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>From <span class="text-danger">*</span></label>
                                        <div class="cal-iconx">
                                            <input class="form-control datetimepicker_fromx" type="date" name="from_date" min="" value="<?php echo e($leaveData->leave_from ?? ''); ?>" id="from_date">
                                        <!-- <div class="cal-icon">
                                            <input class="form-control datetimepicker" type="text" name="from_date" value="<?php echo date('d/m/Y'); ?>" id="from_date"> -->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>To <span class="text-danger">*</span></label>

                                        <div class="cal-iconx">
                                            <input class="form-control datetimepicker_tox" type="date" name="to_date" id="to_date" min="" value="<?php echo e($leaveData->leave_to ?? ''); ?>">

                                        <!-- <div class="cal-icon">
                                            <input class="form-control datetimepicker" type="text" name="to_date" id="to_date" value="<?php echo date('d/m/Y'); ?>"> -->

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Number of days <span class="text-danger">*</span></label>
                                        <input class="form-control" readonly type="text" name="days" id="no_of_days" value="<?php echo e($leaveData->leave_days ?? 0); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Remaining Leaves <span class="text-danger">*</span></label>
                                        <input class="form-control remaining_leaves" readonly type="text" name="remaining_leaves" id="remaining_leaves" value="<?php echo e($leaveData->remaining_leave ?? 0); ?>">
                                        <span class="text-danger" id="rl_count_err"></span>
                                    </div>
                                    <div class="form-group">
                                        <label>Leave Reason <span class="text-danger">*</span></label>
                                        <textarea rows="4" class="form-control" name="leave_reason" id="leave_reason"><?php echo e($leaveData->leave_reason ?? ''); ?></textarea>
                                    </div>
                                    <div class="submit-section">
                                        <button class="btn btn-primary submit-btn" type="submit" id="addLeaveBtn">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div><?php /**PATH C:\wamp64_new\www\hrm\resources\views/lts/leave_modal.blade.php ENDPATH**/ ?>