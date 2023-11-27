<?php echo $__env->make('includes/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>;
<?php echo $__env->make('includes/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>;
<style>
    .dash-widget .card-body .dash-widget-info-btn {
    width: calc(100% - 70px);
}
</style>
<?php
$username = Session::get('username');
?>
<div class="page-wrapper">
            
    <!-- Page Content -->
    <div class="content container-fluid">
        <?php echo $__env->make('flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  
        <?php $is_admin = Session::get('is_admin'); ?>
        <!-- Page Header -->
        <div class="page-header">
            <?php if($is_admin != 1): ?>
                <?php $is_manual_punchin = _get_emp_manual_punchin($user_id ?? 0); ?>
                <?php if($is_manual_punchin == 1): ?>
                    <?php if(empty($firstclockin) && empty($lastclockout)): ?>
                        <div class="col-auto float-end ms-auto">
                            <a href="<?php echo e(route('save_clock_data','in')); ?>" class="btn add-btn"><i class="fa fa-clock"></i>Punch In</a>
                        </div>
                    <?php elseif(!empty($firstclockin) && empty($lastclockout)): ?>
                        <div class="col-auto float-end ms-auto">
                            <a href="<?php echo e(route('save_clock_data','out')); ?>" class="btn add-btn"><i class="fa fa-clock"></i>Punch Out</a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">Welcome <?php echo ucfirst($username); ?>!</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
    
        <div class="row">
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-cubes"></i></span>
                        <div class="dash-widget-info">
                            <h3>112</h3>
                            <span>Projects</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-usd"></i></span>
                        <div class="dash-widget-info">
                            <h3>44</h3>
                            <span>Clients</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-diamond"></i></span>
                        <div class="dash-widget-info">
                            <h3>37</h3>
                            <span>Tasks</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-user"></i></span>
                        <div class="dash-widget-info">
                            <h3>218</h3>
                            <span>Employees</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if($is_admin != 1): ?>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-user"></i></span>
                        <div class="dash-widget-info">
                            <h3>KWD <?php echo e(number_format($totpayable,2) ?? 0); ?></h3>
                            <span>Total Indemnity Payable</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div class="row">
            <?php if($is_admin != 1): ?>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-6">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <!-- <span class="dash-widget-icon"><i class="fa fa-cubes"></i></span> -->
                            <div class="dash-widget-info" style="text-align: left;">
                                <h3>Annual leave</h3>
                                <span>Balance leaves <?php echo e($balance_annual_leave_total['remaining_leave_withoutreq'] ?? 0); ?></span>
                                <span>Amount KWD <?php echo e($balance_annual_leave_total['balance_leave_amount'] ?? 0); ?></span>   
                            </div>
                            <div class="dash-widget-info-btn">
                                <a href="javascript:void(0);" class="btn btn-primary annual_history_btn">History</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped custom-table" style="display:none" id="annual_data">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>No. of days</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php if(isset($annual_leave_list) && count($annual_leave_list) > 0): ?>
                                        <?php $__currentLoopData = $annual_leave_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                     
                                            <tr>
                                                <td><?php echo e(date('d-m-Y', strtotime($data->leave_from))); ?> to <?php echo e(date('d-m-Y', strtotime($data->leave_to))); ?></td>
                                                <td><?php echo e($data->leave_days); ?></td>
                                                <td><?php echo e($data->leave_reason); ?></td>
                                            </tr>
                                            
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" align="center">No dat found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-6">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <!-- <span class="dash-widget-icon"><i class="fa fa-cubes"></i></span> -->
                            <div class="dash-widget-info" style="text-align: left;">
                                <h3>Sick leave</h3>
                                <span>Sick leaves total <?php echo e($balance_sick_leave_total['totalLeaveDays'] ?? 0); ?></span>
                                <span>Sick leaves taken <?php echo e($balance_sick_leave_total['taken_leave'] ?? 0); ?></span>   
                            </div>
                            <div class="dash-widget-info-btn">
                                <a href="javascript:void(0);" class="btn btn-primary sick_history_btn">History</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped custom-table" style="display:none" id="sick_data">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>No. of days</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php if(isset($sick_leave_list) && count($sick_leave_list) > 0): ?>
                                        <?php $__currentLoopData = $sick_leave_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                     
                                            <tr>
                                                <td><?php echo e(date('d-m-Y', strtotime($data->leave_from))); ?> to <?php echo e(date('d-m-Y', strtotime($data->leave_to))); ?></td>
                                                <td><?php echo e($data->leave_days); ?></td>
                                                <td><?php echo e($data->leave_reason); ?></td>   
                                            </tr>
                                            
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" align="center">No dat found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if($is_admin != 1): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <h3>Schedule</h3>
                        </div>
                        <div class="table-responsive" style="margin-left:20px">
                            <table class="table table-striped custom-table datatable datatablex" id="datatable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Min Start Time</th>
                                        <th>Max Start Time</th>
                                        <th>Min End Time</th>
                                        <th>Max End Time</th>
                                        <th>Clock In</th>
                                        <th>Clock Out</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php if(isset($sched_data) && count($sched_data) > 0): ?>
                                        <?php $__currentLoopData = $sched_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php $clockin = _get_attendance_time($data->shift_on,$user_id,'clockin');
                                                $clockout = _get_attendance_time($data->shift_on,$user_id,'clockout');
                                            ?>
                                            <?php if($data->shift==1): ?>
                                                <tr>
                                                    <td><?php echo e(date('d-m-Y', strtotime($data->shift_on))); ?></td>
                                                    <td colspan="6" align="center">OFF Day</td>
                                                </tr>
                                            <?php elseif($data->shift==2): ?>
                                                <tr>
                                                    <td><?php echo e(date('d-m-Y', strtotime($data->shift_on))); ?></td>
                                                    <td colspan="6" align="center">PH Day</td>
                                                </tr>
                                            <?php elseif($data->shift==3): ?>
                                                <tr>
                                                    <td><?php echo e(date('d-m-Y', strtotime($data->shift_on))); ?></td>
                                                    <td colspan="6" align="center">Free Shift</td>
                                                </tr>                            
                                            <?php elseif($data->shift==7): ?>
                                                <tr>
                                                    <td><?php echo e(date('d-m-Y', strtotime($data->shift_on))); ?></td>
                                                    <td colspan="6" align="center">AL</td>   
                                                </tr>                           
                                            <?php elseif($data->shift==8): ?>
                                                <tr>
                                                    <td><?php echo e(date('d-m-Y', strtotime($data->shift_on))); ?></td>
                                                    <td colspan="6" align="center">SL</td>
                                                </tr>                            
                                            <?php elseif($data->shift==9): ?>
                                                <tr>
                                                    <td><?php echo e(date('d-m-Y', strtotime($data->shift_on))); ?></td>
                                                    <td colspan="6" align="center">UL</td>
                                                </tr>  
                                            <?php else: ?>                         
                                            <tr>
                                                <td><?php echo e(date('d-m-Y', strtotime($data->shift_on))); ?></td>
                                                <td><?php echo e(_convert_time_to_12hour_format_bydate($data->min_start_time)); ?></td>
                                                <td><?php echo e(_convert_time_to_12hour_format_bydate($data->max_start_time)); ?></td>
                                                <td><?php echo e(_convert_time_to_12hour_format_bydate($data->min_end_time)); ?></td>
                                                <td><?php echo e(_convert_time_to_12hour_format_bydate($data->max_end_time)); ?></td>
                                                <td><?php echo e(_convert_time_to_12hour_format_bydate($clockin)); ?></td>
                                                <td><?php echo e(_convert_time_to_12hour_format_bydate($clockout)); ?></td>
                                            </tr>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
        <?php endif; ?>
    </div>
    <!-- /Page Content -->

</div>
<?php echo $__env->make('includes/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>;
<script type="text/javascript">
    $('#annual_data').hide();
    $('#sick_data').hide();
    $(document).on('click','.annual_history_btn',function(){
        $('#annual_data').toggle();
    });
    $(document).on('click','.sick_history_btn',function(){
        $('#sick_data').toggle();
    });
</script>

<?php /**PATH C:\wamp64_new\www\hrm\resources\views/dashboard.blade.php ENDPATH**/ ?>