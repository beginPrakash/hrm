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
    $('.ph_checkbox').click(function(){
        consoe.log('sd');
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
                    <label>Start Date <span class="text-danger">*</span></label>
                    <div class="cal-iconx">
                        <input class="form-control datetimepicker_fromx" type="date" name="from_date" min="" value="<?php echo e($leaveData->leave_from ?? ''); ?>" id="from_date">
                    <!-- <div class="cal-icon">
                        <input class="form-control datetimepicker" type="text" name="from_date" value="<?php echo date('d/m/Y'); ?>" id="from_date"> -->
                    </div>
                </div>
                <div class="form-group">
                    <label>End Date <span class="text-danger">*</span></label>

                    <div class="cal-iconx">
                        <input class="form-control datetimepicker_tox" type="date" name="to_date" id="to_date" min="" value="<?php echo e($leaveData->leave_to ?? ''); ?>">

                    <!-- <div class="cal-icon">
                        <input class="form-control datetimepicker" type="text" name="to_date" id="to_date" value="<?php echo date('d/m/Y'); ?>"> -->

                    </div>
                </div>
                <div class="form-group">
                    <label>Number of Days <span class="text-danger">*</span></label>
                    <input class="form-control" readonly type="text" name="days" id="no_of_days" value="<?php echo e($leaveData->leave_days ?? 0); ?>">
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class=" table table-bordered table-striped table-hover user_leave_request_tbl <?php if(isset($leaveData) && ($leaveData->leave_type == 1)): ?> <?php else: ?> d-none <?php endif; ?>">
                                <thead>
                                    <tr>
                                        <th style="width: 30px;">Amount Type</th>
                                        <th>Available</th>
                                        <th>Payment</th>
                                        <th>Claim</th>
                                        <th class="text-end">Remaining</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Annual Leave</td>
                                        <td><input type="hidden" value="<?php echo e($userdetails[0]->opening_leave_days ?? 0); ?>" class="an_avail"><?php echo e($userdetails[0]->opening_leave_days ?? 0); ?> Days</td>
                                        
                                        <?php if(isset($leaveData) && !empty($leaveData)): ?>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input an_checkbox" name="an_checkbox" type="checkbox" role="switch" id="flexSwitchCheckChecked" <?php echo e((isset($leaveData) && $leaveData->claimed_annual_days <= 0) ? '' : 'checked'); ?>>
                                                </div>
                                            </td>
                                            <td><input type="number" onkeypress="return digitKeyOnly(event,this)" value="<?php echo e($leaveData->claimed_annual_days ?? 0); ?>" name="annual_leave_days" class="annual_leave_days"  max="<?php echo e($leaveData->claimed_annual_days ?? 0); ?>" min="0" <?php echo e(($leaveData->claimed_annual_days <= 0) ? 'disabled' : ''); ?>></td>
                                        <?php else: ?>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input an_checkbox" name="an_checkbox" type="checkbox" role="switch" id="flexSwitchCheckChecked">
                                                </div>
                                            </td>
                                            <td><input type="number" onkeypress="return digitKeyOnly(event,this)" name="annual_leave_days" class="annual_leave_days"  max="0" min="0" disabled></td>
                                        <?php endif; ?>
                                        <td class="annual_remaining_leave">
                                            <?php if(isset($leaveData) && !empty($leaveData)): ?>
                                                <?php echo e($leaveData->claimed_annual_days_rem ?? 0); ?> Days
                                            <?php else: ?>
                                                <?php echo e($userdetails[0]->opening_leave_days ?? 0); ?> Days
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php if(isset($userdetails[0]) && $userdetails[0]->public_holidays_balance > 0): ?>
                                        <tr>
                                            <td>Public Holiday</td>
                                            <td><input type="hidden" value="<?php echo e($userdetails[0]->public_holidays_balance); ?>" class="ph_avail"><?php echo e($userdetails[0]->public_holidays_balance); ?> Days</td>
                                            
                                            <?php if(isset($leaveData) && !empty($leaveData)): ?>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input ph_checkbox" name="ph_checkbox" type="checkbox" role="switch" id="flexSwitchCheckChecked" <?php echo e((isset($leaveData) && $leaveData->claimed_public_days <= 0) ? '' : 'checked'); ?>>
                                                    </div>
                                                </td>
                                                <td><input type="number" onkeypress="return digitKeyOnlyPH(event,this)" name="public_holidays" class="public_holidays"  value="<?php echo e($leaveData->claimed_public_days ?? 0); ?>" max="<?php echo e($userdetails[0]->public_holidays_balance); ?>" min="0" <?php echo e(($leaveData->claimed_public_days <= 0) ? 'disabled' : ''); ?>></td>
                                            <?php else: ?>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input ph_checkbox" name="ph_checkbox" type="checkbox" role="switch" id="flexSwitchCheckChecked">
                                                    </div>
                                                </td>
                                                <td><input type="number" onkeypress="return digitKeyOnlyPH(event,this)" name="public_holidays" class="public_holidays"  value="<?php echo e($userdetails[0]->public_holidays_balance); ?>" max="<?php echo e($userdetails[0]->public_holidays_balance); ?>" min="0" disabled></td>
                                            <?php endif; ?>
                                            
                                            <td class="public_remaining_leave"><?php echo e($leaveData->claimed_public_days_rem ?? 0); ?> Days</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            <table>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Reason <span class="text-danger">*</span></label>
                    <textarea rows="4" class="form-control" name="leave_reason" id="leave_reason"><?php echo e($leaveData->leave_reason ?? ''); ?></textarea>
                </div>
                <div class="submit-section">
                    <button class="btn btn-primary submit-btn" type="submit" id="addLeaveBtn">Submit</button>
                </div>
            </form>
                    
        </div>
    </div>
</div><?php /**PATH C:\wamp64_new\www\hrm\resources\views/lts/leave_modal.blade.php ENDPATH**/ ?>