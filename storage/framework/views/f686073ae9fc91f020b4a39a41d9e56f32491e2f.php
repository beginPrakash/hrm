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

            <div class="row align-items-center">
                <div class="col-sm-4">
                    <h3 class="page-title mobile-center">Welcome
                        <?php echo ucfirst($username); ?>!
                    </h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ul>
                </div>
                <div class="col-sm-4">
                    <?php if($is_admin != 1): ?>
                    <?php $is_manual_punchin = _get_emp_manual_punchin($user_id ?? 0); ?>
                    <?php if($is_manual_punchin == 1): ?>
                    <?php if(empty($firstclockin) && empty($lastclockout)): ?>
                    <div class="d-flex justify-content-center punch-btn">
                        <a href="<?php echo e(route('save_clock_data','in')); ?>" class="btn add-btn"><i class="fa fa-clock"></i>Check
                            In</a>
                    </div>
                    <?php elseif(!empty($firstclockin) && empty($lastclockout)): ?>
                    <div class="d-flex justify-content-center punch-btn">
                        <a href="<?php echo e(route('save_clock_data','out')); ?>" class="btn success-btn"><i
                                class="fa fa-clock"></i>Check Out</a>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="col-sm-4">
                    <?php if($is_admin != 1): ?>
                    <div class="card dash-widget mb-0">
                        <div class="card-body py-4">
                            <span class="dash-widget-icon"><i class="fas fa-gem"></i></span>
                            <div class="dash-widget-info text-center">
                                <h3><?php echo e(number_format($totpayable,2) ?? 0); ?> KWD</h3>
                                <span>Total Indemnity Amount</span>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <!-- <div class="row">
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
        </div> -->
        <?php if($is_admin != 1): ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card dash-widget">
                    <div class="card-body">
                        <h3>Schedule</h3>
                    </div>
                    <div class="table-responsive" style="margin-left:20px">
                        <table class="table  custom-table" id="datatable">
                            <thead>
                                <tr>
                                    <?php
                                            $startDate = date('Y-m-d');
                                            $startingDate = new DateTime($startDate);
                                            $startingDateYMD = $startingDate->format('Y-m-d');
                                            for ($i = 0; $i < 7; $i++) {
                                                $currentDate = clone $startingDate;
                                                $currentDate->modify("+$i days");
                                                echo '<th>'.$currentDate->format('D-d') .'</th>';
                                            }
                                            ?>
                                </tr>
                            </thead>
                            <tbody>

                                <?php if(isset($sched_data) && count($sched_data) > 0): ?>
                                <?php $__currentLoopData = $sched_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <?php
                                                        for ($d = 0; $d < 7; $d++) 
                                                        {
                                                            $currentDate = clone $startingDate;
                                                            $currentDate->modify("+$d days");
                                                            $aday = $currentDate->format('Y-m-d');
                                                            $emloyeeScheduleToday = App\Models\Scheduling::where('employee', $sh->user_id)->where('shift_on', $aday)->where('status', 'active')->first();
                                                           
                                                            echo '<td>';

                                                            $sh->shift_on_date = $aday;
                                                            if(!empty($emloyeeScheduleToday))
                                                            {
                                                                if($emloyeeScheduleToday->shift==1)
                                                                {
                                                                    $sched = 'OFF Day';
                                                                }
                                                                else if($emloyeeScheduleToday->shift==2)
                                                                {
                                                                    $sched = 'PH Day';
                                                                }
                                                                else if($emloyeeScheduleToday->shift==3)
                                                                {
                                                                    $sched = 'Free Shift';
                                                                }
                                                                else if($emloyeeScheduleToday->shift==7)
                                                                {
                                                                    $sched = 'AL';
                                                                }
                                                                else if($emloyeeScheduleToday->shift==8)
                                                                {
                                                                    $sched = 'SL';
                                                                }
                                                                else if($emloyeeScheduleToday->shift==9)
                                                                {
                                                                    $sched = 'UL';
                                                                }
                                                                else
                                                                {
                                                                    $start = date('h:i a', strtotime($emloyeeScheduleToday->start_time));
                                                                    $end = date('h:i a', strtotime($emloyeeScheduleToday->end_time));
                                                                    $gap = getTimeDiff($start, $end);

                                                                    $sched = $start.' - '.$end.'('. $gap.' hrs)';
                                                                }
                                                                $sh->shift_details = json_encode($emloyeeScheduleToday);
                                                                    
                                                                $encodedData = base64_encode(json_encode($sh));
                                                            ?>
                                    <div class="user-add-shedule-list">
                                        <h2>
                                            <a href="javascript:void(0);" style="border:2px dashed #1eb53a">
                                                <span class="username-info m-b-10">
                                                    <?php echo $sched; ?>
                                                </span>
                                                <!-- <span class="userrole-info">Web Designer - SMARTHR</span> -->
                                            </a>
                                        </h2>
                                    </div>
                                    <?php
                                                            }
                                                            else
                                                            {
                                                                $encodedData = base64_encode(json_encode($sh));
                                                                $f = 0;
                                                                // if(isset($phDetails) && in_array($aday, $phDetails))
                                                                // {
                                                                //     $f = 1;
                                                                //     echo '<span class="badge bg-inverse-danger">PH</span>';
                                                                // }
                                                                // if($overtimeDetails->off_day == $d)
                                                                // {
                                                                //     $f = 1;
                                                                //     echo '<span class="badge bg-inverse-danger">OFF DAY</span>';
                                                                // }
                                                                if($f==0)
                                                                {
                                                            ?>
                                    <div class="user-add-shedule-list">
                                        <!-- <a href="#"  data-bs-toggle="modal" data-bs-target="#add_schedule" class="addSchedule" data-data="<?php echo $encodedData; ?>">
                                                                    <span><i class="fa fa-plus"></i></span>
                                                                    </a> -->
                                    </div>
                                    <?php
                                                                }
                                                            }
                                                            echo '</td>';
                                                        }
                                                        ?>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        <?php endif; ?>

        <div class="row three-box-main">
            <?php if($is_admin != 1): ?>
            <div class="col-md-4 col-sm-4 col-lg-4 col-xl-4">
                <div class="card dash-widget three-box " data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-tree" aria-hidden="true"></i></span>
                        <div class="dash-widget-info pl-2" style="text-align: left;">
                            <h4><?php echo e(number_format($balance_annual_leave_total['balance_leave_amount'],2) ?? 0); ?> KWD</h4>
                            <h4><?php echo e($balance_annual_leave_total['remaining_leave_withoutreq'] ?? 0); ?> DAYS</h4>
                            <span>Annual leave</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-lg-4 col-xl-4">
                <div class="card dash-widget three-box">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-briefcase" aria-hidden="true"></i></span>
                        <div class="dash-widget-info pl-2" style="text-align: left;">
                            <h4>250 KWD</h4>
                            <h4>20 DAYS</h4>
                            <span>Public Holidays</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-lg-4 col-xl-4">
                <div class="card dash-widget three-box" data-bs-toggle="modal" data-bs-target="#sickleaveModal">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-bed" aria-hidden="true"></i></span>
                        <div class="dash-widget-info pl-2" style="text-align: left;">

                            <h4><?php echo e($balance_sick_leave_total['totalLeaveDays'] ?? 0); ?> Days</h4>
                            <!-- <span>Sick leaves taken <?php echo e($balance_sick_leave_total['taken_leave'] ?? 0); ?></span> -->
                            <span>Sick Leaves</span>
                        </div>
                    </div>
                   
                </div>
            </div>
            <?php endif; ?>
        </div>


    </div>
    <!-- /Page Content -->

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">Annual leave</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <table width="100%" class="table-striped custom-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>No. of days</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($annual_leave_list) && count($annual_leave_list) > 0): ?>
                                <?php $__currentLoopData = $annual_leave_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <tr>
                                    <td><?php echo e(date('d-m-Y', strtotime($data->leave_from))); ?> to <?php echo e(date('d-m-Y',
                                        strtotime($data->leave_to))); ?></td>
                                    <td><?php echo e($data->leave_days); ?></td>
                                </tr>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2" align="center">No data found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="sickleaveModal" tabindex="-1" aria-labelledby="sickleaveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="sickleaveModalLabel">Sick leave</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <table width="100%" class="table-striped custom-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>No. of days</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($sick_leave_list) && count($sick_leave_list) > 0): ?>
                                <?php $__currentLoopData = $sick_leave_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <tr>
                                    <td><?php echo e(date('d-m-Y', strtotime($data->leave_from))); ?> to <?php echo e(date('d-m-Y',
                                        strtotime($data->leave_to))); ?></td>
                                    <td><?php echo e($data->leave_days); ?></td>
                                </tr>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="2" align="center">No dat found</td>
                                </tr>
                                <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</div>
<?php echo $__env->make('includes/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>;
<script type="text/javascript">
    $('#annual_data').hide();
    $('#sick_data').hide(); // Fix the typo here
    $(document).on('click', '.sick_history_btn', function () {
        $('#sick_data').toggle();
    });
</script><?php /**PATH C:\wamp64\www\hrm\resources\views/dashboard.blade.php ENDPATH**/ ?>