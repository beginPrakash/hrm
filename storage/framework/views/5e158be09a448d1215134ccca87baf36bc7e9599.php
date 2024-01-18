<?php echo $__env->make('includes/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('includes/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="main-wrapper">

    <!-- Page Wrapper -->
            <div class="page-wrapper">
            
                <!-- Page Content -->
                <div class="content container-fluid">
                    <?php echo $__env->make('flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
                    <?php if(Session::get('sdate')!==null) { $startDate = Session::get('sdate'); }?>
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row">
                            <div class="col">
                                <h3 class="page-title">Daily Scheduling</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Scheduling</li>
                                </ul>
                            </div>
                            <div class="col-auto float-end ms-auto">
                                <a href="#" class="btn add-btn m-r-5" data-bs-toggle="modal" data-bs-target="#import_schedule"> Import Schedule</a>
                                <a href="#" class="btn add-btn m-r-5 addSchedule" data-data="" data-bs-toggle="modal" data-bs-target="#add_schedule"> Assign Shifts</a>
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->
                    
                    <!-- Content Starts -->
                    <!-- Search Filter -->
                    <form action="/user_scheduling" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="row filter-row">
                            <div class="col-sm-6 col-md-3">  
                                <div class="form-group form-focus">
                                    <select class="select" name="employee">
                                        <option value="">Select</option>
                                        <?php foreach ($allEmployees as $emps) {?>
                                            <option value="<?php echo $emps->user_id; ?>" <?php echo (isset($search['emp']) && $search['emp']==$emps->user_id)?'selected':''; ?>><?php echo $emps->first_name.' '.$emps->last_name; ?></option>
                                        <?php } ?>
                                    </select>
                                    <label class="focus-label">Employee</label>
                                </div>
                            </div>
                            
                            <div class="col-sm-6 col-md-3"> 
                                <div class="form-group form-focus select-focus">
                                    <select class="select" name="department">
                                        <option value="">Select</option>
                                        <?php foreach ($department as $dept) {?>
                                            <option value="<?php echo $dept->id; ?>" <?php echo (isset($search['department']) && $search['department']==$dept->id)?'selected':''; ?>><?php echo $dept->name?></option>
                                        <?php } ?>
                                    </select>
                                    <label class="focus-label">Department</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-2">  
                                <div class="form-group form-focus focused">
                                    <div class="cal-icon">
                                        <input class="form-control floating datetimepicker" type="text" name="from_date" id="from_date" value="<?php echo (isset($search['from_date']))?$search['from_date']:date('d-m-Y', strtotime($startDate)); ?>">
                                    </div>
                                    <label class="focus-label">From</label>
                                </div>
                            </div>
                            <!-- <div class="col-sm-6 col-md-2">  
                                <div class="form-group form-focus focused">
                                    <div class="cal-icon">
                                        <input class="form-control floating datetimepicker" type="text" name="to_date">
                                    </div>
                                    <label class="focus-label">To</label>
                                </div>
                            </div> -->
                            <div class="col-sm-6 col-md-2">  
                                <input type="submit" class="btn btn-success w-100" name="search" value="search"> 
                            </div>     
                        </div>
                    </form>
                    <!-- Search Filter -->

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped custom-table datatable">
                                    <thead>
                                        <tr>
                                            <th>Scheduled Shift</th>
                                            <?php
                                            $startingDate = new DateTime($startDate);
                                            $startingDateYMD = $startingDate->format('Y-m-d');
                                            for ($i = 0; $i < 7; $i++) {
                                                $currentDate = clone $startingDate;
                                                $currentDate->modify("+$i days");
                                                echo '<th>'.$currentDate->format('D d') .'</th>';
                                            }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if(isset($scheduling))
                                        {
                                            $image = "assets/img/profiles/avatar-09.jpg";
                                            foreach($scheduling as $sh)
                                            {
                                                if($sh->profile != Null)
                                                {
                                                    $image = 'uploads/profile/'.$sh->profile;
                                                }
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <h2 class="table-avatar">
                                                                <a href="profile.php" class="avatar"><img alt="" src="<?php echo $image; ?>"></a>
                                                                <a href="profile.php"><?php echo $sh->first_name; ?> <?php echo (isset($sh->last_name))?$sh->last_name:''; ?> <span><em>
                                                                    : 
                                                                    <?php echo ($sh->designation==3 && isset($sh->employee_department->name))?$sh->employee_department->name.' ':''; ?><?php echo (isset($sh->employee_designation->name))?$sh->employee_designation->name:''; ?></em></span></a>
                                                            </h2>
                                                        </td>
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
                                                                    $date_a = strtotime($emloyeeScheduleToday->start_time);
                                                                    $date_b = strtotime($emloyeeScheduleToday->end_time);

                                                                    $diff = round(abs($date_a - $date_b) / 60,2);
                                                                    $break_time = get_break_time_for_shift($emloyeeScheduleToday->shift);
                                                                    $gap = convertToHoursMinutes($diff,$break_time);

                                                                    $sched = $emloyeeScheduleToday->shift_details->shift_name.'('. $gap.' hrs)';
                                                                }
                                                                $sh->shift_details = json_encode($emloyeeScheduleToday);
                                                                $sh->is_twoday_shift = $emloyeeScheduleToday->shift_details->is_twoday_shift;   
                                                                $encodedData = base64_encode(json_encode($sh));
                                                            ?>
                                                                <div class="user-add-shedule-list">
                                                                    <h2>
                                                                        <a href="#" class="editSchedule" data-bs-toggle="modal" data-bs-target="#edit_schedule" data-data="<?php echo $encodedData; ?>" style="border:2px dashed #1eb53a">
                                                                        <span class="username-info m-b-10">
                                                                            <?php echo $sched; ?></span>
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
                                                                <a href="#"  data-bs-toggle="modal" data-bs-target="#add_schedule" class="addSchedule" data-data="<?php echo $encodedData; ?>">
                                                                    <span><i class="fa fa-plus"></i></span>
                                                                    </a>
                                                                </div>
                                                            <?php
                                                                }
                                                            }
                                                            echo '</td>';
                                                        }
                                                        ?>  
                                                    </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /Content End -->
                    
                </div>
                <!-- /Page Content -->
                
            </div>
            <!-- /Page Wrapper -->
            
            <!-- Add Schedule Modal -->
            <input type="hidden" id="is_twoday_shift" value="0">
            <div id="add_schedule" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Schedule</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="add_schedule_form" action="/user_scheduleInsert" method="post" class="add_sch_form">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="add_start_from_date" id="add_start_from_date" value="">
                                <div class="row">
                                    <div class="col-sm-6" id="dep_drop">
                                        <div class="form-group">
                                            <label class="col-form-label">Department <span class="text-danger">*</span></label>
                                            <select class="select addsched" name="department_addschedule" id="department_addschedule">
                                                <option value="">Select Department</option>
                                                <?php foreach ($department as $dept) {?>
                                                    <option value="<?php echo $dept->id?>"><?php echo $dept->name?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Employee Name <span class="text-danger">*</span></label>
                                            <input type="text" name="employee_addschedule_name" id="employee_addschedule_name" value="" class="form-control hideit addsched">
                                            
                                            <select class="select employee_drop addsched" name="employee_addschedule[]" id="employee_addschedule" multiple>
                                                <option value="">Select Employee</option>
                                            </select>
                                            <input type="hidden" name="employee_addschedule_id" id="employee_addschedule_id" value="" class="hideit">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Date</label>
                                            <div class="cal-icon">
                                                <input class="form-control datetimepicker addsched" type="text" name="shift_date" id="shift_date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Shifts <span class="text-danger">*</span></label>
                                            <select class="select shift_addschedule addsched" name="shift_addschedule" id="shift_addschedule">
                                                <option value="">Select Shift</option>
                                                <?php foreach ($shifts as $shf) {?>
                                                    <option value="<?php echo $shf->id?>"><?php echo $shf->shift_name?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="timedivadd">
                                    <div class="col-md-4">
                                        <div class="form-group" >
                                            <label>Min Start Time <span class="text-danger">*</span></label>
                                            <div class="cal-icon">
                                                <input type="text" class="form-control datewithtime addsched" id="min_start_time" name="min_start_time">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Start Time <span class="text-danger">*</span></label>
                                            <div class="cal-icon">
                                                <input type="text" class="form-control datewithtime addsched" name="start_time" id="start_time">
                                            </div>                                  
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Max Start Time <span class="text-danger">*</span></label>
                                            <div class="cal-icon">
                                                <input type="text" class="form-control datewithtime" name="max_start_time" id="max_start_time">
                                            </div>                                          
                                        </div>
                                    </div>      
                                    <div class="col-md-4">
                                        <div class="form-group" >
                                            <label>Min End Time <span class="text-danger">*</span></label>
                                            <div class="cal-icon">
                                                <input type="text" class="form-control datewithtime" name="min_end_time" id="min_end_time">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>End Time <span class="text-danger">*</span></label>
                                            <div class="cal-icon">
                                                <input type="text" class="form-control datewithtime addsched" name="end_time" id="end_time">
                                            </div>                                  
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Max End Time <span class="text-danger">*</span></label>
                                            <div class="cal-icon">
                                                <input type="text" class="form-control datewithtime addsched" name="max_end_time" id="max_end_time">
                                            </div>                                          
                                        </div>
                                    </div>  
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Break Time (In Minutes) </label>
                                            <input type="text" class="form-control addsched" name="break_time" id="break_time">                                            
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label">Accept Extra Hours </label>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="customSwitch1" checked="" name="extra_hours" value="1">
                                                <label class="form-check-label" for="customSwitch1"></label>
                                              </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label">Publish </label>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="customSwitch2" checked="" name="publish" value="1">
                                                <label class="form-check-label" for="customSwitch2"></label>
                                              </div>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Add Schedule Modal -->

            <!-- Edit Schedule Modal -->
            <div id="edit_schedule" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Schedule</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="add_schedule_form" action="/user_scheduleUpdate" method="post" class="edit_sch_form">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="schedule_id" id="schedule_id" value="">
                                <input type="hidden" name="edit_start_from_date" id="edit_start_from_date" value="">
                                <div class="row">
                                    <div class="col-sm-6" id="edit_dep_drop">
                                        <div class="form-group">
                                            <label class="col-form-label">Department <span class="text-danger">*</span></label>
                                            <select class="select editsched" name="department_addschedule" id="department_addschedule">
                                                <option value="">Select Department</option>
                                                <?php foreach ($department as $dept) {?>
                                                    <option value="<?php echo $dept->id?>"><?php echo $dept->name?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Employee Name <span class="text-danger">*</span></label>
                                            <input type="text" name="employee_addschedule_name" id="edit_employee_addschedule_name" value="" class="form-control hideit editsched">
                                            
                                            <select class="select employee_drop editsched" name="employee_addschedule" id="edit_employee_addschedule">
                                                <option value="">Select Employee</option>
                                            </select>
                                            <input type="hidden" name="employee_addschedule_id" id="edit_employee_addschedule_id" value="" class="hideit">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Date</label>
                                            <div class="cal-icon">
                                                <input class="form-control datetimepicker editsched" type="text" name="shift_date" id="edit_shift_date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Shifts <span class="text-danger">*</span></label>
                                            <select class="select shift_addschedule editsched" name="shift_addschedule" id="edit_shift_addschedule">
                                                <option value="">Select Shift</option>
                                                <?php foreach ($shifts as $shf) {?>
                                                    <option value="<?php echo $shf->id?>"><?php echo $shf->shift_name?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="timedivedit">
                                    <div class="col-md-4">
                                        <div class="form-group" >
                                            <label>Min Start Time <span class="text-danger">*</span></label>
                                            <div class="cal-icon">
                                                <input type="text" class="form-control editsched edit_min_s_time datewithtime" id="min_start_time" name="min_start_time">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Start Time <span class="text-danger">*</span></label>
                                            <div class="cal-icon">
                                                <input type="text" class="form-control editsched edit_s_time datewithtime" name="start_time" id="start_time">
                                            </div>                                  
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Max Start Time <span class="text-danger">*</span></label>
                                            <div class="cal-icon">
                                                <input type="text" class="form-control editsched edit_max_s_time datewithtime" name="max_start_time" id="max_start_time">
                                            </div>                                          
                                        </div>
                                    </div>      
                                    <div class="col-md-4">
                                        <div class="form-group" >
                                            <label>Min End Time <span class="text-danger">*</span></label>
                                            <div class="cal-icon">
                                                <input type="text" class="form-control editsched edit_min_e_time datewithtime" name="min_end_time" id="min_end_time">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>End Time <span class="text-danger">*</span></label>
                                            <div class="cal-icon">
                                                <input type="text" class="form-control editsched edit_e_time datewithtime" name="end_time" id="end_time">
                                            </div>                                  
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Max End Time <span class="text-danger">*</span></label>
                                            <div class="cal-icon">
                                                <input type="text" class="form-control editsched edit_max_e_time datewithtime" name="max_end_time" id="max_end_time">
                                            </div>                                          
                                        </div>
                                    </div>  
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Break Time (In Minutes) </label>
                                            <input type="text" class="form-control editsched edit_break_time" name="break_time" id="break_time">                                            
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label">Accept Extra Hours </label>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="customSwitch1" checked="" name="extra_hours" value="1">
                                                <label class="form-check-label" for="customSwitch1"></label>
                                              </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label">Publish </label>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="customSwitch2" checked="" name="publish" value="1">
                                                <label class="form-check-label" for="customSwitch2"></label>
                                              </div>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Edit Schedule Modal -->

            <!-- Import schedule Modal -->
            <div class="modal custom-modal fade" id="import_schedule" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-header">
                                <h3>Import Schedule</h3>
                            </div>
                            <div class="modal-btn import-action">
                                <div class="row">
                                    <div class="col-12">
                                        <form action="/user_scheduleImport" method="post" enctype="multipart/form-data">
                                            <?php echo csrf_field(); ?>
                                            <div class="form-group">
                                                <label>Import File <span class="text-danger">*</span></label>
                                                <input class="form-control" value="" readonly type="file" name="schedule_file">
                                            </div>
                                            <div class="submit-section">
                                                <button class="btn btn-primary submit-btn">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Import schedule Modal -->


</div>
<!-- end main wrapper-->


</body>

</html>
<?php echo $__env->make('includes/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script>  
// var days = daysdifference('03/19/2021', '03/31/2024');
// console.log(days);
function daysdifference(firstDate, secondDate){  
    var startDay = new Date(firstDate);  
    var endDay = new Date(secondDate);  
  
// Determine the time difference between two dates     
    var millisBetween = startDay.getTime() - endDay.getTime();  
  
// Determine the number of days between two dates  
    var days = millisBetween / (1000 * 3600 * 24);  
  
// Show the final number of days between dates     
    return Math.round(Math.abs(days));  
} 

var $valid = false;
var errorMsg = '';
var dynamicErrorMsg = function () { return errorMsg; }
jQuery.validator.addMethod("datecchange", function(value, element){
    var el_name = element.name;
    var sch_id = $('#schedule_id').val();
    var is_twoday_shift = $('#is_twoday_shift').val();
    if(sch_id != ''){
        var shift_date = $('#edit_shift_date').val();
    }else{
        var shift_date = $('#shift_date').val();
    }
    var changed_date = value;
   
    changed_date = changeDateFormatTimeForVal(changed_date);
    
    shift_date = changeDateFormatTimeForVal(shift_date);
    var diff_days = daysdifference(shift_date,changed_date);

    if(is_twoday_shift == '1'){
        if(el_name == 'end_time' || el_name == 'min_end_time' || el_name == 'max_end_time'){
            if(diff_days > 1){
                errorMsg = "Maximum 24 hours date allowed";
                return false;
            }else{
                return true;
            }
        }else if(el_name == 'start_time' || el_name == 'min_start_time' || el_name == 'max_start_time'){
            if(diff_days > 0){
                errorMsg = "Single date allowed";
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }
    }else{
        if(diff_days > 0){
            errorMsg = "Single date allowed";
            return false;
        }else{
            return true;
        }
    }
    console.log(dynamicErrorMsg);
},dynamicErrorMsg);

    $(document).ready(function() {

        $(".add_sch_form").validate({
            rules: {
                shift_date: {
                    required : true
                },
                shift_addschedule:{
                    required : true
                },
                min_start_time:{
                    required : true
                },
                start_time:{
                    required : true,
                    datecchange: true
                },
                max_start_time:{
                    required : true,
                    datecchange: true
                },
                min_end_time:{
                    required : true,
                    datecchange: true
                },
                end_time:{
                    required : true,
                    datecchange: true
                },
                max_end_time:{
                    required : true,
                    datecchange: true
                },
            },
            messages: {
                shift_date: {
                    required : 'Shift Date is required'
                },
                shift_addschedule:{
                    required : 'Shift is required'
                },
                min_start_time:{
                    required : 'Min Start Time is required'
                },
                start_time:{
                    required : 'Start Time is required',
                    greaterThan: 'Must be greater than Min Start time'
                },
                max_start_time:{
                    required : 'Max Start Time is required',
                    greaterThan: 'Must be greater than Start time'
                },
                min_end_time:{
                    required : 'Min End Time is required',
                    // greaterThan: 'Must be greater than Max Start time'
                },
                end_time:{
                    required : 'End Time is required',
                    greaterThan: 'Must be greater than Min End time'
                },
                max_end_time:{
                    required : 'Max End Time is required',
                    greaterThan: 'Must be greater than End time'
                },
                end_on:{
                    required : 'End On  is required'
                },
                break_time:{
                    required : 'Break time is required'
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

       $(".edit_sch_form").validate({
            rules: {
                shift_date: {
                    required : true
                },
                shift_addschedule:{
                    required : true
                },
                min_start_time:{
                    required : true
                },
                start_time:{
                    required : true,
                    datecchange: true
                },
                max_start_time:{
                    required : true,
                    datecchange: true
                },
                min_end_time:{
                    required : true,
                    datecchange: true
                },
                end_time:{
                    required : true,
                    datecchange: true
                },
                max_end_time:{
                    required : true,
                    datecchange: true
                },
            },
            messages: {
                shift_date: {
                    required : 'Shift Date is required'
                },
                shift_addschedule:{
                    required : 'Shift is required'
                },
                min_start_time:{
                    required : 'Min Start Time is required'
                },
                start_time:{
                    required : 'Start Time is required',
                    greaterThan: 'Must be greater than Min Start time'
                },
                max_start_time:{
                    required : 'Max Start Time is required',
                    greaterThan: 'Must be greater than Start time'
                },
                min_end_time:{
                    required : 'Min End Time is required',
                    // greaterThan: 'Must be greater than Max Start time'
                },
                end_time:{
                    required : 'End Time is required',
                    greaterThan: 'Must be greater than Min End time'
                },
                max_end_time:{
                    required : 'Max End Time is required',
                    greaterThan: 'Must be greater than End time'
                },
                end_on:{
                    required : 'End On  is required'
                },
                break_time:{
                    required : 'Break time is required'
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


        $('#department_addschedule').on('change', function() {
            var departmentID = $(this).val();
           if(departmentID) {
               $.ajax({
                   url: '/user_employeeByDepartment/'+departmentID,
                   type: "GET",
                   dataType: "json",
                   // data: {id, departmentID},
                   success:function(response)
                   {
                        $('#employee_addschedule').empty();
                        $("#employee_addschedule").append('<option>Select Employee</option>');
                     if(response)
                     { 
                        $.each(response,function(key,value){ //console.log(value.user_id);
                            $("#employee_addschedule").append('<option value="'+value.user_id+'">'+value.first_name+' '+value.last_name+'</option>');
                        });
                    }else{
                        $('#employee_addschedule').empty();
                    }
                 }
               });
           }else{
             $('#employee_addschedule').empty();
           }
        });
    });
    
</script>

<script>
    $(document).on('click','.addSchedule',function(){
        var rowData = $(this).data('data');
        $('.addsched').val('');
        
        var sdate = $('#from_date').val();
        $('#add_start_from_date').val(sdate);

        if(rowData!='')
        {
            var decodedData = atob(rowData);
            
            $('#dep_drop').hide(); 
            $('#employee_addschedule').next(".select2-container").hide();
            $('#employee_addschedule').hide();
            $('#employee_addschedule_name').removeClass('hideit');
            $('#employee_addschedule_id').removeClass('hideit');
            $.each(JSON.parse(decodedData), function(key,value){
                // console.log(key+'-'+value);
                if(key=='first_name')
                {
                    $('#employee_addschedule_name').val(value);
                    $('#employee_addschedule_name').attr('readonly', true);
                }
                if(key=='user_id')
                {
                    $('#employee_addschedule_id').val(value);
                }
                if(key=='shift_on_date')
                {
                    $('#shift_date').val(changeDateFormat(value));
                }
            });
        }
        else
        {
            $('#dep_drop').show(); 
            $('#employee_addschedule').show();
            $('.select2').show();
            $('#employee_addschedule_name').addClass('hideit');
            $('#employee_addschedule_id').addClass('hideit');
        }
    })
</script>

<script>
    $(document).on('click','.editSchedule',function(){
        var rowData = $(this).data('data');
        // if(rowData!='')
        // {
            $('.editsched').val('');
            var decodedData = atob(rowData);

            $('#edit_start_from_date').val($('#from_date').val());
            $('#edit_dep_drop').hide(); 
            $('#edit_employee_addschedule').next(".select2-container").hide();
            $('#edit_employee_addschedule').hide();
            $('#edit_employee_addschedule_name').removeClass('hideit');
            $('#edit_employee_addschedule_id').removeClass('hideit');
            $.each(JSON.parse(decodedData), function(key,value){
                //console.log(key+'-'+value);
                if(key=='first_name')
                {
                    $('#edit_employee_addschedule_name').val(value);
                    $('#edit_employee_addschedule_name').attr('readonly', true);
                }
                if(key=='user_id')
                {
                    $('#edit_employee_addschedule_id').val(value);
                }
                if(key=='shift_on_date')
                {
                    $('#edit_shift_date').val(changeDateFormat(value));
                }
                if(key=='shift_details')
                { 
                    var shdetails = JSON.parse(value);

                    // alert(parseInt(shdetails.shift));
                    if(parseInt(shdetails.shift) <= 2 && parseInt(shdetails.shift) >= 7 && parseInt(shdetails.shift) <= 9)
                    { 
                        $('#timedivedit').css('display', 'none');
                    }
                    else
                    {
                        $('#timedivedit').css('display', 'flex');
                    }

                   
                    $('#edit_shift_addschedule').val(shdetails.shift).select2();
                    $('#schedule_id').val(shdetails.id);
                    $('.edit_s_time').val(changeDateFormatTime(shdetails.start_time));
                    $('.edit_min_s_time').val(changeDateFormatTime(shdetails.min_start_time));
                    $('.edit_max_s_time').val(changeDateFormatTime(shdetails.max_start_time));
                    $('.edit_e_time').val(changeDateFormatTime(shdetails.end_time));
                    $('.edit_min_e_time').val(changeDateFormatTime(shdetails.min_end_time));
                    $('.edit_max_e_time').val(changeDateFormatTime(shdetails.max_end_time));
                    $('.edit_break_time').val(shdetails.break_time);

                    // var shiftdetai = JSON.parse(shdetails.shift_details);


                    $('#is_twoday_shift').val(shdetails.shift_details.is_twoday_shift);
                    
                    // $.each(JSON.parse(value), function(k,v)
                    // {
                    //     console.log(k+'-'+v);
                        //$('#edit_shift_addschedule').val(shdetails.shift).trigger('change');
                    // });
                }
            });
        // }
        // else
        // {
        //     $('#dep_drop').show(); 
        //     $('#employee_addschedule').show();
        //     $('.select2').show();
        //     $('#employee_addschedule_name').addClass('hideit');
        //     $('#employee_addschedule_id').addClass('hideit');
        // }
    })
</script>

<script>   
    $(document).ready(function() {
        $('.shift_addschedule').on('change', function() {
            var shiftId = $(this).val();
            var sch_id = $('#schedule_id').val();
            if(sch_id != ''){
                var shiftdate = $('#edit_shift_date').val();
            }else{
                var shiftdate = $('#shift_date').val();
            }
            if(parseInt(shiftId) <= 3 || (parseInt(shiftId) >= 7 && parseInt(shiftId) <= 9))
            { 
                $('#timedivadd,#timedivedit').css('display', 'none');
            }
            else if(parseInt(shiftId) > 3 && parseInt(shiftId) < 7 || parseInt(shiftId) > 9){ 
                $('#timedivadd,#timedivedit').css('display', 'flex');
               $.ajax({
                   url: '/shiftDetails/'+shiftId,
                   type: "GET",
                   dataType: "json",
                   success:function(response)
                   {
                        var termarray = ['min_start_time', 'start_time', 'max_start_time', 'min_end_time', 'end_time', 'max_end_time'];
                        $.each(response, function(key,value){
                            $('#is_twoday_shift').val(value.is_twoday_shift);
                            // console.log(value);
                            $.each(value, function(k,v){
                                final_date = shiftdate; 
                                // console.log(k+'-'+v);
                                if(jQuery.inArray(k, termarray) !== -1)
                                {
                                    var convertedTime = convert12HourTo24Hour(v);
                                    if(value.is_twoday_shift=='1'){
                                        var new_date = moment(shiftdate, "DD-MM-YYYY").add(1, 'd');
                                        final_date = moment(new_date._d).format('DD-MM-YYYY');
                                    }else{
                                        final_date = shiftdate;
                                    }

                                    if(k=='min_start_time' || k=='start_time' || k=='max_start_time'){
                                        $('#add_schedule_form [name="'+k+'"]').val(shiftdate+' '+convertedTime);//sp[0]+':'+sp[1]);
                                    }
                                    else{
                                        //console.log(final_date);
                                        $('#add_schedule_form [name="'+k+'"]').val(final_date+' '+convertedTime);//sp[0]+':'+sp[1]);
                                    }
                                }
                                else
                                {
                                    $('#add_schedule_form [name="'+k+'"]').val(v);
                                }
                            });
                        });
                    }
               });
           }else{
             $('#employee_addschedule').empty();
           }
        });
    });
    
</script>

<script type="text/javascript">
    function convert12HourTo24Hour(time12) {
        var [time, modifier] = time12.split(" ");
        var [hours, minutes] = time.split(":");

        if (hours === "12") {
          hours = "00";
        }

        if (modifier === "pm") {
          hours = parseInt(hours, 10) + 12;
        }

        return hours + ":" + minutes;
      }
</script>

<script type="text/javascript">
    function changeDateFormat(dateval)
    {
        var dateParts = dateval.split('-');
        var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
        return formattedDate;
    }
    function changeDateFormatTime(dateval)
    {
        var timeparts = dateval.split(' ');
        var dateParts = timeparts[0].split('-');
        var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0]+' '+timeparts[1];
        return formattedDate;
    }

    function changeDateFormatTimeForVal(dateval)
    {
        var timeparts = dateval.split(' ');
        var dateParts = timeparts[0].split('-');
        var formattedDate = dateParts[1] + '/' + dateParts[0] + '/' + dateParts[2];
        return formattedDate;
    }
</script><?php /**PATH C:\wamp64_new\www\hrm\resources\views/lts/user_scheduling.blade.php ENDPATH**/ ?>