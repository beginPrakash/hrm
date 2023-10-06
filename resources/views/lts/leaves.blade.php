@include('includes/header')
@include('includes/sidebar')


 <!-- Page Wrapper -->
            <div class="page-wrapper">

                <!-- Page Content -->
                <div class="content container-fluid">
                    
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
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stats-info">
                                <h6>Today Absent</h6>
                                <h4><?php echo $todayLeave; ?></h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-info">
                                <h6>New Requests</h6>
                                <h4><?php echo $newLeave; ?><!--  <span>Today</span> --></h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-info">
                                <h6>Rejected Requests</h6>
                                <h4><?php echo $rejectLeave; ?></h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-info">
                                <h6>Pending Requests</h6>
                                <h4><?php echo $pending; ?></h4>
                            </div>
                        </div>
                    </div>
                    <!-- /Leave Statistics -->
                    
                    <!-- Search Filter -->
                    <form action="/leaves" method="post">
                        @csrf
                        <div class="row filter-row">
                           <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
                                <div class="form-group form-focus">
                                    <input type="text" class="form-control floating" name="employee" value="<?php echo (isset($where['user_id']))?$where['user_id']:''; ?>">
                                    <label class="focus-label">Employee Name</label>
                                </div>
                           </div>
                           <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
                                <div class="form-group form-focus select-focus">
                                    <select class="select floating" name="leavetype"> 
                                        <option value="">Select</option>
                                        <?php foreach ($leavetype as $value) {?>
                                        <option value="<?php echo $value->id?>" data-id="<?php echo $value->days?>" <?php echo (isset($where['leave_type']) && $where['leave_type']==$value->id)?'selected':''; ?>><?php echo $value->name?></option>
                                        <?php } ?>
                                    </select>
                                    <label class="focus-label">Leave Type</label>
                                </div>
                           </div>
                           <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12"> 
                                <div class="form-group form-focus select-focus">
                                    <select class="select floating" name="leavestatus"> 
                                        <option value=""> -- Select -- </option>
                                        <option value="new" <?php echo (isset($where['leave_status']) && $where['leave_status']=='new')?'selected':''; ?>> New </option>
                                        <option value="pending" <?php echo (isset($where['leave_status']) && $where['leave_status']=='pending')?'selected':''; ?>> Pending </option>
                                        <option value="approved" <?php echo (isset($where['leave_status']) && $where['leave_status']=='approved')?'selected':''; ?>> Approved </option>
                                        <option value="rejected" <?php echo (isset($where['leave_status']) && $where['leave_status']=='rejected')?'selected':''; ?>> Rejected </option>
                                    </select>
                                    <label class="focus-label">Leave Status</label>
                                </div>
                           </div>
                           <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
                                <div class="form-group form-focus">
                                    <div class="cal-icon">
                                        <input class="form-control floating datetimepicker" type="text" name="from" value="<?php echo (isset($where['leave_from']))?$where['leave_from']:''; ?>">
                                    </div>
                                    <label class="focus-label">From</label>
                                </div>
                            </div>
                           <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
                                <div class="form-group form-focus">
                                    <div class="cal-icon">
                                        <input class="form-control floating datetimepicker" type="text" name="to" value="<?php echo (isset($where['leave_to']))?$where['leave_to']:''; ?>">
                                    </div>
                                    <label class="focus-label">To</label>
                                </div>
                            </div>
                           <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
                                <button type="submit" name="search" class="btn btn-success w-100"> Search </button>  
                           </div>     
                        </div>
                    </form>
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
                                            <?php if(((isset($userdetails[0]->employee_designation)) && $userdetails[0]->employee_designation->priority_level == 0)) { ?>
                                                <th class="text-end">Actions</th>
                                            <?php } ?>
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
                                                if(((isset($userdetails[0]->employee_designation)) && $userdetails[0]->employee_designation->priority_level == 3) && $leave->leave_user->department!==$userdepartment)
                                                {
                                                    continue;
                                                }
                                                //for info icon
                                                $color = App\Models\LeaveStatus::where('name', $leave->leave_status)->get()->first()->color;
                                                $gm_approval = 'Pending';
                                                if($leave->gm_approval > 0)
                                                {
                                                    $leave_status = App\Models\LeaveStatus::where('id', $leave->gm_approval)->get()->first();
                                                    $gm_approval = $leave_status->name;
                                                }

                                                $hr_approval = 'Pending';
                                                if($leave->hr_approval > 0)
                                                {
                                                    $leave_status = App\Models\LeaveStatus::where('id', $leave->hr_approval)->get()->first();
                                                    $hr_approval = $leave_status->name;
                                                }

                                                $dm_approval = 'Pending';
                                                if($leave->dm_approval > 0)
                                                {
                                                    $leave_status = App\Models\LeaveStatus::where('id', $leave->dm_approval)->get()->first();
                                                    $dm_approval = $leave_status->name;
                                                    // echo $dm_approval;exit;
                                                }

                                                $approval = 'GM : '.ucwords($gm_approval).'&#010;'.'HR : '.ucwords($hr_approval).'&#010;'.'DM : '.ucwords($dm_approval);

                                                //for approve/reject options
                                                $aflag = 0;
                                                if(isset($userdetails[0]->employee_designation) && $userdetails[0]->employee_designation->priority_level == 1)
                                                {
                                                    if($leave->gm_approval != 4 && $leave->hr_approval == 4)
                                                    {
                                                        $aflag = 1;
                                                    }
                                                    // else
                                                    // {
                                                    //     echo 'HR Approval pending.';
                                                    // }
                                                }
                                                if(isset($userdetails[0]->employee_designation) && $userdetails[0]->employee_designation->priority_level == 2)
                                                {
                                                    if($leave->hr_approval != 4 && $leave->dm_approval == 4)
                                                    {
                                                        $aflag = 2;
                                                    }
                                                    // else
                                                    // {
                                                    //     echo 'DM Approval pending.';
                                                    // }
                                                }
                                                if(isset($userdetails[0]->employee_designation) && $userdetails[0]->employee_designation->priority_level == 3)
                                                {
                                                    if($leave->dm_approval != 4)
                                                    {
                                                        $aflag = 3;
                                                    }
                                                }
                                                
                                            ?>
                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <?php
                                                    if(((isset($userdetails[0]->employee_designation)) && $userdetails[0]->employee_designation->priority_level > 0) || $user->is_admin == 1)
                                                    { ?>
                                                        <td><?php echo $leave->leave_user->first_name.' '.$leave->leave_user->last_name; ?></td>
                                                    <?php } ?>
                                                    <td><?php echo $leave->leaves_leavetype->name; ?></td>
                                                    <td><?php echo date('d-m-Y', strtotime($leave->leave_from)); ?></td>
                                                    <td><?php echo date('d-m-Y', strtotime($leave->leave_to)); ?></td>
                                                    <td><?php echo $leave->leave_days; ?></td>
                                                    <td><?php echo $leave->leave_reason; ?></td>
                                                    <td>
                                                        <div class="dropdown action-label">
                                                            <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="fa fa-dot-circle-o text-<?php echo $color; ?>"></i> <?php echo ucwords($leave->leave_status); ?>
                                                            </a>
                                                            <?php if($aflag > 0) { ?>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    <a class="dropdown-item approveButton" href="#" data-bs-toggle="modal" data-bs-target="#approve_leave" data-id="<?php echo $aflag; ?>" data-data="<?php echo $leave->id; ?>"><i class="fa fa-dot-circle-o text-success"></i> Approve</a>
                                                                    <a class="dropdown-item rejectButton" href="#" data-bs-toggle="modal" data-bs-target="#reject_leave" data-id="<?php echo $aflag; ?>" data-data="<?php echo $leave->id; ?>"><i class="fa fa-dot-circle-o text-danger"></i> Reject</a>
                                                                </div>
                                                            <?php } ?>

                                                            <?php if($leave->leave_status != 'cancelled') { ?>
                                                                <i class="fa fa-info-circle" data-bs-toggle="tooltip" title="<?php echo $approval; ?>"></i>
                                                            <?php } ?>

                                                            <?php if($leave->leave_status=='rejected') { ?>
                                                                <br><strong>Reason : </strong><?php echo $leave->reject_reason; ?>
                                                            <?php } ?>
                                                        </div>
                                                    </td>
                                                    <?php if(((isset($userdetails[0]->employee_designation)) && $userdetails[0]->employee_designation->priority_level == 0)) { 
                                                        $encodedData = base64_encode(json_encode($leave));
                                                        ?>
                                                        <td>
                                                            <?php if($leave->leave_status == 'new') { ?>
                                                                <div class="dropdown action-label">
                                                                    <!-- <div class="dropdown-menux dropdown-menu-rightx"> -->
                                                                        <a class="btn btn-white btn-sm btn-rounded cancelButton" href="#" data-bs-toggle="modal" data-bs-target="#cancel_leave" data-data="<?php echo $leave->id; ?>"><i class="fa fa-dot-circle-o text-danger"></i> Cancel</a>
                                                                    <!-- </div> -->
                                                                </div>

                                                                <!-- <a class="editButton" href="#" data-bs-toggle="modal" data-bs-target="#edit_leave" data-data="<?php //echo $encodedData; ?>"><i class="fa fa-edit text-info"></i> Edit</a> -->
                                                            <?php } ?>
                                                        </td>
                                                    <?php } ?>
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
                <div id="add_leave" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Leave</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form  action="/leaveInsert" method="post" id="addEditForm">
                                    @csrf
                                    <div class="form-group">
                                        <label>Leave Type <span class="text-danger">*</span></label>
                                        <select class="select" name="leave_type" id="leave_type">

                                            <option value="">Select</option>
                                             <?php foreach ($leavetype as $value) {?>

                                            <option value="<?php echo $value->id?>" data-id="<?php echo $value->days?>"><?php echo $value->name?></option>
                                        <?php }?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>From <span class="text-danger">*</span></label>
                                        <div class="cal-iconx">
                                            <input class="form-control datetimepicker_fromx" type="date" name="from_date" min="<?=date('Y-m-d'); ?>" value="<?=date('d-m-Y'); ?>" id="from_date">
                                        <!-- <div class="cal-icon">
                                            <input class="form-control datetimepicker" type="text" name="from_date" value="<?php echo date('d/m/Y'); ?>" id="from_date"> -->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>To <span class="text-danger">*</span></label>

                                        <div class="cal-iconx">
                                            <input class="form-control datetimepicker_tox" type="date" name="to_date" id="to_date" min="<?=date('Y-m-d'); ?>" value="<?=date('d-m-Y'); ?>">

                                        <!-- <div class="cal-icon">
                                            <input class="form-control datetimepicker" type="text" name="to_date" id="to_date" value="<?php echo date('d/m/Y'); ?>"> -->

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Number of days <span class="text-danger">*</span></label>
                                        <input class="form-control" value="1" readonly type="text" name="days" id="no_of_days">
                                    </div>
                                    <div class="form-group">
                                        <label>Remaining Leaves <span class="text-danger">*</span></label>
                                        <input class="form-control" readonly type="text" name="remaining_leaves" id="remaining_leaves" value="">
                                        <span class="text-danger" id="rl_count_err"></span>
                                    </div>
                                    <div class="form-group">
                                        <label>Leave Reason <span class="text-danger">*</span></label>
                                        <textarea rows="4" class="form-control" name="leave_reason"></textarea>
                                    </div>
                                    <div class="submit-section">
                                        <button class="btn btn-primary submit-btn" type="submit" id="addLeaveBtn">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Add Leave Modal -->
                
                <!-- Edit Leave Modal -->
                <div id="edit_leave" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Leave</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post" id="addEditForm">
                                    <div class="form-group">
                                        <label>Leave Type <span class="text-danger">*</span></label>
                                        <select class="select" id="leave_leave_type" name="leave_type">
                                            <option value="">Select</option>
                                            <?php foreach ($leavetype as $value) {?>
                                            <option value="<?php echo $value->id?>" data-id="<?php echo $value->days?>"><?php echo $value->name?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>From <span class="text-danger">*</span></label>
                                        <div class="cal-icon">
                                            <input class="form-control datetimepicker" value="01-01-2019" type="text" id="leave_leave_from" name="from_date">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>To <span class="text-danger">*</span></label>
                                        <div class="cal-icon">
                                            <input class="form-control datetimepicker" value="01-01-2019" type="text" id="leave_leave_to" name="to_date">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Number of days <span class="text-danger">*</span></label>
                                        <input class="form-control" readonly type="text" value="2" id="leave_leave_days" name="days">
                                    </div>
                                    <div class="form-group">
                                        <label>Remaining Leaves <span class="text-danger">*</span></label>
                                        <input class="form-control" readonly value="12" type="text" id="leave_remaining_leave" name="remaining_leaves">
                                    </div>
                                    <div class="form-group">
                                        <label>Leave Reason <span class="text-danger">*</span></label>
                                        <textarea rows="4" class="form-control" id="leave_leave_reason" name="leave_reason"></textarea>
                                    </div>
                                    <div class="submit-section">
                                        <button class="btn btn-primary submit-btn">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Edit Leave Modal -->

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
                                        @csrf
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
                                        @csrf
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
                                        @csrf
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


@include('includes/footer')

<script type="text/javascript">
    $(document).ready(function() {
        
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
                    required : true},

                remaining_leaves:  {
                    required : true},
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
                    required : 'Remaining Leaves is required',
                },
                leave_reason: {
                    required : 'Leaves reason is required',
                }
            },
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
        var leave_type = $(this).val();
        var max_leaves = $(this).find(':selected').data('id');
        $('#addLeaveBtn').attr('disabled', false);
        $('#rl_count_err').text('');
        $.ajax({
           url: '/getLeaveDetails/',
           type: "POST",
           dataType: "json",
           data: {"_token": "{{ csrf_token() }}", leave_type:leave_type},
           success:function(response)
            {
                //response = taken leaves
                var remaining_leave = max_leaves - response;
                $('#remaining_leaves').val(remaining_leave);
                if(remaining_leave == 0)
                {
                    $('#addLeaveBtn').attr('disabled', true);
                    $('#rl_count_err').text('Insufficient no of leaves');
                }
            }
        });
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
    });

    $(document).on('change','#leave_leave_from, #leave_leave_to',function(){
        var from_date = $('#leave_leave_from').val();
        $('#leave_leave_to').attr('min', from_date);
        var to_date = $('#leave_leave_to').val();

        var dt1 = new Date(from_date);
        var dt2 = new Date(to_date);
 
        var time_difference = dt2.getTime() - dt1.getTime();
        var result = time_difference / (1000 * 60 * 60 * 24);
  
        var no_days = (result >= 0 )?result+1:0;
        $('#leave_leave_days').val(no_days);
    });
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
</script>