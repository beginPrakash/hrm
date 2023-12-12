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
            <h5 class="modal-title leave_m_title">Leave Details</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <?php if(empty($type)): ?>
            <form  action="/post_leave_transaction" method="post">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" class="leave_id" value="<?php echo e($leaveData->id ?? ''); ?>">

                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class=" table table-bordered table-striped table-hover user_leave_request_tbl <?php if(isset($leaveData) && ($leaveData->leave_type == 1)): ?> <?php else: ?> d-none <?php endif; ?>">
                                <thead>
                                    <tr>
                                        <th style="width: 30px;">Amount Type</th>
                                        <th>Available</th>
                                        <th>Payment</th>
                                        <th>Textbox</th>
                                        <th class="text-end">Remaining</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Annual Leave</td>
                                        <td><?php echo e($userdetails->opening_leave_days ?? 0); ?> Days</td>
                                        
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input an_checkbox" name="an_checkbox" type="checkbox" role="switch" id="flexSwitchCheckChecked" <?php echo e((isset($leaveData) && $leaveData->claimed_annual_days <= 0) ? '' : 'checked'); ?>>
                                            </div>
                                        </td>
                                        <input type="hidden" id="no_of_days" value="<?php echo e($leaveData->leave_days ?? 0); ?>">
                                        <input type="hidden" value="<?php echo e($userdetails->opening_leave_days ?? 0); ?>" class="an_avail">
                                        <td><input type="number" onkeypress="return digitKeyOnly(event,this)" value="<?php echo e($leaveData->claimed_annual_days ?? 0); ?>" name="annual_leave_days" class="annual_leave_days"  max="<?php echo e($leaveData->claimed_annual_days ?? 0); ?>" min="0" <?php echo e((isset($leaveData) && $leaveData->claimed_annual_days <= 0) ? 'disabled' : ''); ?>></td>
                                        <td class="annual_remaining_leave">
                                            <?php echo e(($userdetails->opening_leave_days ?? 0) - ($leaveData->claimed_annual_days ?? 0)); ?> Days   
                                        </td>
                                    </tr>
                                    <?php if(isset($leaveData) && ($leaveData->claimed_public_days > 0)): ?>
                                        <tr>
                                            <td>Public Holiday</td>
                                            <td><input type="hidden" class="ph_avail" value="<?php echo e($userdetails->public_holidays_balance ?? 0); ?>"><?php echo e($userdetails->public_holidays_balance ?? 0); ?> Days</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input ph_checkbox" name="ph_checkbox" type="checkbox" role="switch" id="flexSwitchCheckChecked" <?php echo e((isset($leaveData) && $leaveData->claimed_public_days <= 0) ? '' : 'checked'); ?>>
                                                </div>
                                            </td>
                                            <td><input type="number" onkeypress="return digitKeyOnlyPH(event,this)" name="public_holidays" class="public_holidays"  value="<?php echo e($leaveData->claimed_public_days ?? 0); ?>" max="<?php echo e($userdetails[0]->public_holidays_balance ?? ''); ?>" min="0" <?php echo e((isset($leaveData) && $leaveData->claimed_public_days <= 0) ? 'disabled' : ''); ?>></td>
                                            <td class="public_remaining_leave"><?php echo e(($userdetails->public_holidays_balance ?? 0) - ($leaveData->claimed_public_days ?? 0)); ?> Days</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            <table>
                        </div>
                    </div>
                </div>
                <div class="submit-section">
                    <button class="btn btn-primary submit-btn" type="submit" id="post_trans_btn">Post The Transaction</button>
                </div>
            </form>
            <?php else: ?>
            
            <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class=" table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 30px;">Amount Type</th>
                                        <th>Available</th>
                                        <th>Payment</th>
                                        <th>Text</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Annual Leave</td>
                                        <td><?php echo e($leaveData->claimed_annual_days_rem + $leaveData->claimed_annual_days); ?> Days</td>
                                        <td>Yes</td>
                                        <td><?php echo e($leaveData->claimed_annual_days ?? 0); ?> Days</td>
                                    </tr>
                                    <?php if(isset($leaveData) && ($leaveData->claimed_public_days > 0)): ?>
                                        <tr>
                                            <td>Public Holiday</td>
                                            <td><?php echo e($leaveData->claimed_public_days_rem + $leaveData->claimed_public_days); ?> Days</td>
                                            <td>Yes</td>
                                            <td><?php echo e($leaveData->claimed_public_days_rem ?? 0); ?> Days</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
           
            <form  action="/post_leave_transaction" method="post">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="type" id="trans_type" value="download">
                <input type="hidden" name="id" class="leave_id" value="<?php echo e($leaveData->id ?? ''); ?>">
                <div class="submit-section">
                    <button class="btn btn-primary submit-btn" type="submit" id="download_btn">Download Settlement</button>
                </div>
            </form>        
        </div>
    </div>
</div><?php /**PATH C:\wamp64_new\www\hrm\resources\views/edbr/an_leave_detail_modal.blade.php ENDPATH**/ ?>