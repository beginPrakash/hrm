<?php echo $__env->make('includes/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('includes/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


 <!-- Page Wrapper -->
            <div class="page-wrapper">

                <!-- Page Content -->
                <div class="content container-fluid">
                <?php echo $__env->make('flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title">Leaves</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Leaves</li>
                                </ul>
                            </div>
                            <div class="col-auto float-end ms-auto">
                                <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_leave"><i class="fa fa-plus"></i> Add Leave</a>
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->

                    <?php if(((isset($userdetails[0]->employee_designation)) && $userdetails[0]->employee_designation->priority_level > 0) || $user->is_admin == 1) { ?>
                    <?php
                    $todayLeave = 0;//approved leaves today
                    $newLeave = 0;//approved leaves today
                    $rejectLeave = 0;
                    $pending = 0;
                    if(isset($leaveApplications))
                    {
                        $i = 0;
                        foreach($leaveApplications as $leaveNo)
                        {
                            if($leaveNo->leave_from == date('Y-m-d') && $leaveNo->leave_status == 'approved')
                            {
                                $todayLeave++;
                            }
                            if($leaveNo->leave_status == 'new')
                            {
                                $newLeave++;
                            }
                            if($leaveNo->leave_status == 'rejected')
                            {
                                $rejectLeave++;
                            }
                            if($leaveNo->leave_status == 'pending')
                            {
                                $pending++;
                            }
                            
                        }
                    }?>
                    <!-- Leave Statistics -->
                    
                    <!-- /Leave Statistics -->
                    
                    <!-- Search Filter -->
                    
                    <!-- /Search Filter -->
                <?php } ?>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class=" table table-bordered table-striped table-hover datatable datatable-LoanApplication">
                                    <thead>
                                        <tr>
                                            <th style="width: 30px;">#</th>
                                            <?php
                                            if(((isset($userdetails[0]->employee_designation)) && $userdetails[0]->employee_designation->priority_level > 0) || $user->is_admin == 1)
                                            { ?>
                                            <th>Applied By</th>
                                            <?php } ?>
                                            <th>Leave Type</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>No of Days</th>
                                            <th>Reason</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if(isset($leaveApplications))
                                        {
                                            $i = 0;
                                            foreach($leaveApplications as $leave)
                                            {
                                                $i++;
                                                
                                            ?>
                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <?php
                                                    if(((isset($userdetails[0]->employee_designation)) && $userdetails[0]->employee_designation->priority_level > 0) || $user->is_admin == 1)
                                                    { ?>
                                                        <td><?php echo e($leave->leave_user->first_name ?? ''); ?> <?php echo e($leave->leave_user->last_name ?? ''); ?></td>
                                                    <?php } ?>
                                                    <td><?php echo $leave->leaves_leavetype->name; ?></td>
                                                    <td><?php echo date('d-m-Y', strtotime($leave->leave_from)); ?></td>
                                                    <td><?php echo date('d-m-Y', strtotime($leave->leave_to)); ?></td>
                                                    <td><?php echo $leave->leave_days; ?></td>
                                                    <td><?php echo $leave->leave_reason; ?></td>
                                                    <td>
                                                    <?php echo ucwords($leave->leave_status); ?>
                                                    </td>
                                                    <td>
                                                        
                                                        <?php $leave_approve = is_leave_approved_any_approver($leave->id); ?>
                                                        <?php if($leave->leave_status=='pending'): ?>
                                                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#add_leave" data-id="<?php echo e($leave->id); ?>" data-leave_days = "<?php echo e($leave->leave_days); ?>" class="action-icon edit_hierarchy"><i class="fa fa-pencil"></i></a>
                                                        <?php else: ?>
                                                            <?php if($leave->leave_status!='approved'): ?>
                                                                <?php if($leave_approve <= 0): ?>
                                                                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#add_leave" data-id="<?php echo e($leave->id); ?>" data-leave_days = "<?php echo e($leave->leave_days); ?>" class="action-icon edit_hierarchy"><i class="fa fa-pencil"></i></a>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                        </td>
                                                </tr>
                                            <?php
                                            }
                                        }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Page Content -->
                
                <!-- Add Leave Modal -->
                <input type="hidden" id="request_leave" value="<?php echo e($userdetails[0]->request_leave_days ?? 0); ?>">
                <input type="hidden" id="emp_remaining_leave" value="<?php echo e($leave_details['remaining_leave'] ?? 0); ?>">
                <input type="hidden" id="emp_remainingsick_leave" value="<?php echo e($sick_leave_details['remaining_leave'] ?? 0); ?>">
                <div id="add_leave" class="modal custom-modal fade " role="dialog">
                    <?php echo $__env->make('lts/leave_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <!-- /Add Leave Modal -->

                <!-- Approve Leave Modal -->
                <div class="modal custom-modal fade" id="approve_leave" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="form-header">
                                    <h3>Leave Approve</h3>
                                    <p>Are you sure want to approve for this leave?</p>
                                </div>
                                <div class="modal-btn delete-action">
                                    <form action="/leaveApprove" method="post">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="status" value="1">
                                        <input type="hidden" name="approval_by" id="approval_by" value="">
                                        <input type="hidden" name="leave_id" id="leave_id" value="">
                                        <div class="row">
                                            <div class="col-6">
                                                <button type="submit" class="btn btn-primary continue-btn" style="width: 100%;">Approve</button>
                                            </div>
                                            <div class="col-6">
                                                <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-primary cancel-btn">Close</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Approve Leave Modal -->
                
                <!-- Delete Leave Modal -->
                <div class="modal custom-modal fade" id="reject_leave" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="form-header">
                                    <h3>Reject Leave</h3>
                                    <p>Are you sure want to reject this leave?</p>
                                </div>
                                <div class="modal-btn delete-action">
                                    <form action="/leaveReject" method="post">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="status" value="2">
                                        <input type="hidden" name="reject_by" id="reject_by" value="">
                                        <input type="hidden" name="leave_id" id="reject_leave_id" value="">
                                        <div class="row">
                                            <div class="col-12">
                                                <textarea class="form-control" name="reject_reason" required placeholder="Reason for Rejection"></textarea>
                                            </div>
                                            <div class="col-6">
                                                <button type="submit" class="btn btn-primary continue-btn" style="width: 100%;">Reject</button>
                                            </div>
                                            <div class="col-6">
                                                <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-primary cancel-btn">Close</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Delete Leave Modal -->

                <!-- Cancel Leave Modal -->
                <div class="modal custom-modal fade" id="cancel_leave" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="form-header">
                                    <h3>Cancel Leave</h3>
                                    <p>Are you sure want to cancel this leave?</p>
                                </div>
                                <div class="modal-btn cancel-action">
                                    <form action="/leaveCancel" method="post">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="status" value="2">
                                        <input type="hidden" name="leave_id" id="cancel_leave_id" value="">
                                        <div class="row">
                                            <div class="col-6">
                                                <button type="submit" class="btn btn-primary continue-btn" style="width: 100%;">Cancel</button>
                                            </div>
                                            <div class="col-6">
                                                <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-primary cancel-btn">Close</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Cancel Leave Modal -->
                
            </div>
            <!-- /Page Wrapper -->


<?php echo $__env->make('includes/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script type="text/javascript">
    $(document).ready(function() {
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

       $(document).on('click','.edit_hierarchy',function(){
            $('#add_leave').html('');
            var id= $(this).attr('data-id');
            $.ajax({
            url: '/getmainLeaveDetailsById/',
            type: "POST",
            dataType: "json",
            data: {"_token": "<?php echo e(csrf_token()); ?>", id:id},
            success:function(response)
                {
                    $('#add_leave').html(response.html).fadeIn();
                }
            });
        });

       $('#add_leave').on('hidden.bs.modal', function () {
            $('#leave_type').val('').trigger('change');
            $('#from_date').val('');
            $('#to_date').val('');
            $('#remaining_leaves').val(0);
            $('#no_of_days').val(0);
            $('#leave_reason').val('');
            $('.leave_m_title').text('Add Leave');
        });
       
    });

    
</script>

<script>
    $(document).on('click','.approveButton',function(){
        var id = $(this).data('id');
        var lid = $(this).data('data');
        $('#leave_id').val(lid);
        $('#approval_by').val(id);
    })
</script>

<script>
    $(document).on('click','.rejectButton',function(){
        var id = $(this).data('id');
        var lid = $(this).data('data');
        $('#reject_leave_id').val(lid);
        $('#reject_by').val(id);
    })
</script>

<script>
    $(document).on('click','.cancelButton',function(){
        // var id = $(this).data('id');
        var lid = $(this).data('data');
        $('#cancel_leave_id').val(lid);
        // $('#cancel_by').val(id);
    })
</script>

<script type="text/javascript">
    $(document).on('change','#leave_type, #leave_leave_type',function(){
        $('.user_leave_request_tbl').addClass('d-none');
        var leave_type = $(this).val();
        if(leave_type == '1'){
            $('.user_leave_request_tbl').removeClass('d-none');
        }
        
        var max_leaves = $(this).find(':selected').data('id');
        $('#addLeaveBtn').attr('disabled', false);
        $('#rl_count_err').text('');
        if(leave_type != '8'){
            $.ajax({
            url: '/getLeaveDetails/',
            type: "POST",
            dataType: "json",
            data: {"_token": "<?php echo e(csrf_token()); ?>", leave_type:leave_type},
            success:function(response)
                {
                    //response = taken leaves
                    if(leave_type == 1){
                        var emp_remaining_leave = $('#emp_remaining_leave').val();
                    }else if(leave_type == 2){
                        var emp_remaining_leave = $('#emp_remainingsick_leave').val();
                    }  
                    //console.log(emp_remaining_leave);
                    var remaining_leave = emp_remaining_leave;
                    $('#remaining_leaves').val(remaining_leave);
                    if(remaining_leave == 0)
                    {
                        $('#addLeaveBtn').attr('disabled', true);
                        //$('#rl_count_err').text('Insufficient no of leaves');
                    }
                }
            });
        }
    });
</script>

<script type="text/javascript">
    $(document).on('change','#from_date, #to_date',function(){
        var from_date = $('#from_date').val();
        $('#to_date').attr('min', from_date);
        var to_date = $('#to_date').val();

        var dt1 = new Date(from_date);
        var dt2 = new Date(to_date);
 
        var time_difference = dt2.getTime() - dt1.getTime();
        var result = time_difference / (1000 * 60 * 60 * 24);

        var no_days = (result >= 0 )?result+1:0;
        $('#no_of_days').val(no_days);
        $('.annual_leave_days').val(no_days);
        var request_leave = $('#request_leave').val();
        var leave_type = $('#leave_type').val();
        if(leave_type == 1){
            var emp_remaining_leave = $('#emp_remaining_leave').val();
        }else if(leave_type == 2){
            var emp_remaining_leave = $('#emp_remainingsick_leave').val();
        }  
        //console.log(emp_remaining_leave);
        var remaining_leave = emp_remaining_leave;

        if($('.leave_id').val() != ''){
            var edit_days = $('#edit_days').val();
            var remaining_leave = parseInt(emp_remaining_leave) + parseInt(edit_days);
                if(remaining_leave >= no_days){
                    var total_req_leave = parseInt(remaining_leave) - parseInt(no_days);
                    //console.log(total_req_leave);
                    $('#remaining_leaves').val(total_req_leave);
                    $('.annual_leave_days').prop('max',no_days);
                    var an_avail = $('.an_avail').val();
                    var an_taken = $('.annual_leave_days').val();
                    if(an_taken >= an_avail){
                        var total = an_avail - an_taken;
                        $('.annual_remaining_leave').text(total+' Days');
                    }
                }

        }else{
            if(remaining_leave >= no_days){
                var total_req_leave = remaining_leave - no_days;
                //console.log(total_req_leave);
                $('.annual_leave_days').prop('max',no_days);
                $('#remaining_leaves').val(total_req_leave);
                var an_avail = $('.an_avail').val();
                var an_taken = $('.annual_leave_days').val();
                if(an_taken >= an_avail){
                    var total = an_avail - an_taken;
                    $('.annual_remaining_leave').text(total+' Days');
                }
            }
        }
        
    });

    function digitKeyOnly(e,eln) {       
        var k = parseInt($('#no_of_days').val());
        var value = Number(e.target.value + e.key) || 0;      
        if (value > k) {
            e.preventDefault();
            return false;
        }
        return true;
    }

    function digitKeyOnlyPH(e,eln) {       
        var k = parseInt($('.ph_avail').val());
        var value = Number(e.target.value + e.key) || 0;      
        if (value > k) {
            e.preventDefault();
            return false;
        }
        return true;
    }

</script>

<script>
    $(document).on('click','.editButton',function(){
        var rowData = $(this).data('data');
        var decodedData = atob(rowData);
        $.each(JSON.parse(decodedData), function(key,value){
            // console.log(key);
            $('#leave_'+key).val(value);
            if(key == 'leave_type')
            {
                $("#leave_leave_type").val(value).change();
            }
        });
    })

    $('.ph_checkbox').click(function(){
        $('.public_holidays').attr('disabled',true);
        if ($(this).prop('checked')==true){ 
            $('.public_holidays').attr('disabled',false);
            var ph_avail = $('.ph_avail').val();
            var ph_taken = $('.public_holidays').val();
            if(ph_avail >= ph_taken){
                var total = ph_avail - ph_taken;
                $('.public_remaining_leave').text(total+' Days');
            }
        }
    });

    $(document).on('change','.public_holidays',function(){
        var ph_avail = $('.ph_avail').val();
        var ph_taken = $('.public_holidays').val();
        if(ph_avail >= ph_taken){
            var total = ph_avail - ph_taken;
            $('.public_remaining_leave').text(total+' Days');
        }
    });

    $('.an_checkbox').click(function(){
        $('.annual_leave_days').attr('disabled',true);
        if ($(this).prop('checked')==true){ 
            $('.annual_leave_days').attr('disabled',false);
            var an_avail = $('.an_avail').val();
            var an_taken = $('.annual_leave_days').val();
            if(an_avail >= an_taken){
                var total = an_avail - an_taken;
                $('.annual_remaining_leave').text(total+' Days');
            }
        }
    });

    $(document).on('change keyup','.annual_leave_days',function(){
        var an_avail = $('.an_avail').val();
        var an_taken = $('.annual_leave_days').val();
        if(an_avail >= an_taken){
            var total = an_avail - an_taken;
            $('.annual_remaining_leave').text(total+' Days');
        }
    });
</script><?php /**PATH C:\wamp64_new\www\hrm\resources\views/lts/leaves.blade.php ENDPATH**/ ?>