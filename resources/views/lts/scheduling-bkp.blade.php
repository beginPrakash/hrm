@include('includes/header')
@include('includes/sidebar')
    <div class="main-wrapper">


    <!-- Page Wrapper -->
            <div class="page-wrapper">
            
                <!-- Page Content -->
                <div class="content container-fluid">
                    
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row">
                            <div class="col">
                                <h3 class="page-title">Daily Scheduling</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="employees.php">Employees</a></li>
                                    <li class="breadcrumb-item active">Shift Scheduling</li>
                                </ul>
                            </div>
                            <div class="col-auto float-end ms-auto">
                                <a href="/shifting" class="btn add-btn m-r-5">Shifts</a>
                                <a href="#" class="btn add-btn m-r-5" data-bs-toggle="modal" data-bs-target="#add_schedule"> Assign Shifts</a>
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->
                    
                    <!-- Content Starts -->
                    <!-- Search Filter -->
                    <form action="/scheduling" method="post">
                        @csrf
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
                                            <option value="<?php echo $dept->id; ?>" <?php echo (isset($depwhere['id']) && $depwhere['id']==$dept->id)?'selected':''; ?>><?php echo $dept->name?></option>
                                        <?php } ?>
                                    </select>
                                    <label class="focus-label">Department</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-2">  
                                <div class="form-group form-focus focused">
                                    <div class="cal-icon">
                                        <input class="form-control floating datetimepicker" type="text" name="from_date" value="<?php echo (isset($search['from_date']))?$search['from_date']:date('d/m/Y'); ?>">
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
                                            if(isset($search['from_date']))
                                            {
                                                $startDate = date('D d', strtotime(str_replace('/','-',$search['from_date'])));
                                                $today = date('Y-m-d', strtotime(str_replace('/','-',$search['from_date'])));
                                            }
                                            else
                                            {
                                                $startDate = date('D d');
                                                $today = date('Y-m-d');
                                            }
                                            echo '<th>'.$startDate.'</th>';
                                            
                                            for($i =1; $i <= 7; $i++)
                                            {
                                                $today = date('Y-m-d', strtotime('+1 day', strtotime($today)));
                                                echo '<th>'.date('D d', strtotime($today)).'</th>';
                                            }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if(isset($allEmployees))
                                        {
                                            $image = "assets/img/profiles/avatar-09.jpg";
                                            foreach($allEmployees as $empkey => $emp)
                                            {
                                                if($emp->profile != Null)
                                                {
                                                    $image = 'uploads/profile/'.$emp->profile;
                                                }

                                            ?>
                                                <tr>
                                                    <td>
                                                        <h2 class="table-avatar">
                                                            <a href="profile.php" class="avatar"><img alt="" src="<?php echo $image; ?>"></a>
                                                            <a href="profile.php"><?php echo $emp->first_name; ?> <?php echo (isset($emp->last_name))?$emp->last_name:''; ?> <span><em><?php echo (isset($emp->employee_designation->name))?': '.$emp->employee_designation->name:''; ?></em></span></a>
                                                        </h2>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if(isset($search['from_date']))
                                                        {
                                                            $tday = $search['from_date'];
                                                        }
                                                        else
                                                        {
                                                            $tday = date('Y-m-d');
                                                        }
                                                        // $tday = date('d/m/Y');
                                                        $emloyeeScheduleToday = App\Models\Scheduling::where('employee', $emp->user_id)->where('shift_on', $tday)->where('status', 'active')->first();
                                                        if(!empty($emloyeeScheduleToday))
                                                        {
                                                            $start = date('h:i a', strtotime($emloyeeScheduleToday->start_time));
                                                            $end = date('h:i a', strtotime($emloyeeScheduleToday->end_time));
                                                            $time1 = strtotime($emloyeeScheduleToday->start_time);
                                                            $time2 = strtotime($emloyeeScheduleToday->end_time);

                                                            $gap =  round(abs($time2 - $time1) / 3600,2);

                                                            // $gap = round(abs(strtotime($emloyeeScheduleToday->end_time)-strtotime($emloyeeScheduleToday->start_time)) / 3600,2);
                                                            ?>
                                                        <div class="user-add-shedule-list">
                                                            <h2>
                                                                <a href="#" data-bs-toggle="modal" data-bs-target="#edit_schedulex" style="border:2px dashed #1eb53a">
                                                                <span class="username-info m-b-10">
                                                                    <?php echo $start.' - '.$end; ?> (<?php echo $gap; ?> hrs)</span>
                                                                <!-- <span class="userrole-info">Web Designer - SMARTHR</span> -->
                                                                </a>
                                                            </h2>
                                                        </div>
                                                        <?php }
                                                        else
                                                        {
                                                        ?>
                                                            <div class="user-add-shedule-list">
                                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#add_schedule_user0<?php echo $empkey; ?>">
                                                                    <span><i class="fa fa-plus"></i></span>
                                                                    </a>
                                                                </div>
                                                                <div id="add_schedule_user0<?php echo $empkey; ?>" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Schedule</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="add_schedule_form" action="/scheduleInsert" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Department <span class="text-danger">*</span></label>
                                                <select class="select form-control" name="department_addschedule" id="department_addschedule">
                                                    <option value="0">Select Department</option>
                                                    <?php foreach ($department as $dept) {?>
                                                        <option value="<?php echo $dept->id?>" <?php echo ($emp->department==$dept->id)?'selected':'disabled'; ?>><?php echo $dept->name?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Employee Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="" value="<?php echo $emp->first_name.' '.$emp->last_name; ?>" readonly>
                                                <input type="hidden" name="employee_addschedule" value="<?php echo $emp->user_id; ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Date</label>
                                                <div class="cal-icon">
                                                    <input class="form-control datetimepicker" type="text" name="shift_date" value="<?php echo $tday; ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Shifts <span class="text-danger">*</span></label>
                                                <select class="select form-control shift_addschedule" name="shift_addschedule" id="shift_addschedule">
                                                    <option value="">Select Shift</option>
                                                    <?php foreach ($shifts as $shf) {?>
                                                        <option value="<?php echo $shf->id?>"><?php echo $shf->shift_name?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group" >
                                                <label>Min Start Time <span class="text-danger">*</span></label>
                                                <div class="input-group time timepicker">
                                                    <input class="form-control" id="min_start_time" name="min_start_time"><span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Start Time <span class="text-danger">*</span></label>
                                                <div class="input-group time timepicker">
                                                    <input class="form-control" name="start_time" id="start_time"><span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                                </div>                                  
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Max Start Time <span class="text-danger">*</span></label>
                                                <div class="input-group time timepicker">
                                                    <input class="form-control" name="max_start_time" id="max_start_time"><span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                                </div>                                          
                                            </div>
                                        </div>      
                                        <div class="col-md-4">
                                            <div class="form-group" >
                                                <label>Min End Time <span class="text-danger">*</span></label>
                                                <div class="input-group time timepicker">
                                                    <input class="form-control" name="min_end_time" id="min_end_time"><span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>End Time <span class="text-danger">*</span></label>
                                                <div class="input-group time timepicker">
                                                    <input class="form-control" name="end_time" id="end_time"><span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                                </div>                                  
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Max End Time <span class="text-danger">*</span></label>
                                                <div class="input-group time timepicker">
                                                    <input class="form-control" name="max_end_time" id="max_end_time"><span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                                </div>                                          
                                            </div>
                                        </div>  
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Break Time (In Minutes) </label>
                                                <input type="text" class="form-control" name="break_time" id="break_time">                                            
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
                                                        <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <?php
                                                    if(isset($search['from_date']))
                                                    {
                                                        $today = date('Y-m-d', strtotime(str_replace('/','-',$search['from_date'])));
                                                    }
                                                    else
                                                    {
                                                        $today = date('Y-m-d');
                                                    }
                                                    // $today = date('Y-m-d');
                                                    for($i =1; $i <= 7; $i++)
                                                    { ?>
                                                        <td>
                                                            <?php
                                                            $today = date('Y-m-d', strtotime('+1 day', strtotime($today)));
                                                            $days = date('d/m/Y', strtotime($today));
                                                            $emloyeeAttendanceDays = App\Models\Scheduling::where('employee', $emp->user_id)->where('shift_on', $days)->first();
                                                            if(!empty($emloyeeAttendanceDays))
                                                            {
                                                                $dstart = date('h:i a', strtotime($emloyeeAttendanceDays->start_time));
                                                                $dend = date('h:i a', strtotime($emloyeeAttendanceDays->end_time));
                                                                $dtime1 = strtotime($emloyeeAttendanceDays->start_time);
                                                                $dtime2 = strtotime($emloyeeAttendanceDays->end_time);

                                                                $dgap =  round(abs($dtime2 - $dtime1) / 3600,2);
                                                            ?>
                                                                <div class="user-add-shedule-list">
                                                                    <h2>
                                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#edit_schedule" style="border:2px dashed #1eb53a">
                                                                        <span class="username-info m-b-10"><?php echo $dstart.' - '.$dend; ?> (<?php echo $dgap; ?> hrs)</span>
                                                                        <!-- <span class="userrole-info">Web Designer - SMARTHR</span> -->
                                                                        </a>
                                                                    </h2>
                                                                </div>
                                                            <?php
                                                            }
                                                            else
                                                            {
                                                            ?>
                                                                <div class="user-add-shedule-list">
                                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#add_schedule_user<?php echo $i.$empkey; ?>">
                                                                    <span><i class="fa fa-plus"></i></span>
                                                                    </a>
                                                                </div>
                                                                <div id="add_schedule_user<?php echo $i.$empkey; ?>" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Schedule</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="add_schedule_form" action="/scheduleInsert" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Department <span class="text-danger">*</span></label>
                                                <select class="select form-control" name="department_addschedule" id="department_addschedule">
                                                    <option value="0">Select Department</option>
                                                    <?php foreach ($department as $dept) {?>
                                                        <option value="<?php echo $dept->id?>" <?php echo ($emp->department==$dept->id)?'selected':'disabled'; ?>><?php echo $dept->name?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Employee Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="" value="<?php echo $emp->first_name.' '.$emp->last_name; ?>" readonly>
                                                <input type="hidden" name="employee_addschedule" value="<?php echo $emp->user_id; ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Date</label>
                                                <div class="cal-icon">
                                                    <input class="form-control datetimepicker" type="text" name="shift_date" value="<?php echo  $days; ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Shifts <span class="text-danger">*</span></label>
                                                <select class="select form-control shift_addschedule" name="shift_addschedule" id="shift_addschedule">
                                                    <option value="">Select Shift</option>
                                                    <?php foreach ($shifts as $shf) {?>
                                                        <option value="<?php echo $shf->id?>"><?php echo $shf->shift_name?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group" >
                                                <label>Min Start Time <span class="text-danger">*</span></label>
                                                <div class="input-group time timepicker">
                                                    <input class="form-control" id="min_start_time" name="min_start_time"><span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Start Time <span class="text-danger">*</span></label>
                                                <div class="input-group time timepicker">
                                                    <input class="form-control" name="start_time" id="start_time"><span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                                </div>                                  
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Max Start Time <span class="text-danger">*</span></label>
                                                <div class="input-group time timepicker">
                                                    <input class="form-control" name="max_start_time" id="max_start_time"><span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                                </div>                                          
                                            </div>
                                        </div>      
                                        <div class="col-md-4">
                                            <div class="form-group" >
                                                <label>Min End Time <span class="text-danger">*</span></label>
                                                <div class="input-group time timepicker">
                                                    <input class="form-control" name="min_end_time" id="min_end_time"><span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>End Time <span class="text-danger">*</span></label>
                                                <div class="input-group time timepicker">
                                                    <input class="form-control" name="end_time" id="end_time"><span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                                </div>                                  
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Max End Time <span class="text-danger">*</span></label>
                                                <div class="input-group time timepicker">
                                                    <input class="form-control" name="max_end_time" id="max_end_time"><span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                                </div>                                          
                                            </div>
                                        </div>  
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Break Time (In Minutes) </label>
                                                <input type="text" class="form-control" name="break_time" id="break_time">                                            
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
                                                            <?php
                                                            }
                                                            ?>
                                                        </td>
                                                    <?php
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
                                <form id="add_schedule_form" action="/scheduleInsert" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Department <span class="text-danger">*</span></label>
                                                <select class="select" name="department_addschedule" id="department_addschedule">
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
                                                <select class="select" name="employee_addschedule" id="employee_addschedule">
                                                    <option value="">Select Employee</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Date</label>
                                                <div class="cal-icon">
                                                    <input class="form-control datetimepicker" type="text" name="shift_date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Shifts <span class="text-danger">*</span></label>
                                                <select class="select shift_addschedule" name="shift_addschedule" id="shift_addschedule">
                                                    <option value="">Select Shift</option>
                                                    <?php foreach ($shifts as $shf) {?>
                                                        <option value="<?php echo $shf->id?>"><?php echo $shf->shift_name?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group" >
                                                <label>Min Start Time <span class="text-danger">*</span></label>
                                                <div class="input-group time timepicker">
                                                    <input class="form-control" id="min_start_time" name="min_start_time"><span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Start Time <span class="text-danger">*</span></label>
                                                <div class="input-group time timepicker">
                                                    <input class="form-control" name="start_time" id="start_time"><span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                                </div>                                  
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Max Start Time <span class="text-danger">*</span></label>
                                                <div class="input-group time timepicker">
                                                    <input class="form-control" name="max_start_time" id="max_start_time"><span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                                </div>                                          
                                            </div>
                                        </div>      
                                        <div class="col-md-4">
                                            <div class="form-group" >
                                                <label>Min End Time <span class="text-danger">*</span></label>
                                                <div class="input-group time timepicker">
                                                    <input class="form-control" name="min_end_time" id="min_end_time"><span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>End Time <span class="text-danger">*</span></label>
                                                <div class="input-group time timepicker">
                                                    <input class="form-control" name="end_time" id="end_time"><span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                                </div>                                  
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Max End Time <span class="text-danger">*</span></label>
                                                <div class="input-group time timepicker">
                                                    <input class="form-control" name="max_end_time" id="max_end_time"><span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                                </div>                                          
                                            </div>
                                        </div>  
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Break Time (In Minutes) </label>
                                                <input type="text" class="form-control" name="break_time" id="break_time">                                            
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
                                <form>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Department <span class="text-danger">*</span></label>
                                                <select class="select">
                                                    <option value="">Select</option>
                                                    <option selected value="">Development</option>
                                                    <option value="1">Finance</option>
                                                    <option value="2">Finance and Management</option>
                                                    <option value="3">Hr & Finance</option>
                                                    <option value="4">ITech</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Employee Name <span class="text-danger">*</span></label>
                                                <select class="select">
                                                    <option value="">Select </option>
                                                    <option selected value="1">Richard Miles </option>
                                                    <option value="2">John Smith</option>
                                                    <option value="3">Mike Litorus </option>
                                                    <option value="4">Wilmer Deluna</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Date <span class="text-danger">*</span></label>
                                                <div class="cal-icon"><input class="form-control datetimepicker" type="text"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Shifts <span class="text-danger">*</span></label>
                                                <select class="select">
                                                    <option value="">Select </option>
                                                    <option value="1">10'o clock Shift</option>
                                                    <option value="2">10:30 shift</option>
                                                    <option value="3">Daily Shift </option>
                                                    <option  selected value="4">New Shift</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="col-form-label">Min Start Time  <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" value="06:11 am">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="col-form-label">Start Time  <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" value="06:30 am">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="col-form-label">Max Start Time  <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" value="08:12 am">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="col-form-label">Min End Time  <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" value="09:12 pm">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="col-form-label">End Time   <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" value="09:30 pm">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="col-form-label">Max End Time <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" value="09:45 pm">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="col-form-label">Break Time  <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text" value="45">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="custom-control form-check">
                                                <input type="checkbox" class="form-check-input" id="customCheck1">
                                                <label class="form-check-label" for="customCheck1">Recurring Shift</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="col-form-label">Repeat Every</label>
                                                <select class="select">
                                                    <option value="">1 </option>
                                                    <option value="1">2</option>
                                                    <option value="2">3</option>
                                                    <option value="3">4</option>
                                                    <option  selected value="4">5</option>
                                                    <option value="3">6</option>
                                                </select>
                                                <label class="col-form-label">Week(s)</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group wday-box">
                                                
                                                    <label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="monday" class="days recurring" checked="" onclick="return false;"><span class="checkmark">M</span></label>
                
                                                    <label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="tuesday" class="days recurring" checked="" onclick="return false;"><span class="checkmark">T</span></label>
                                                
                                                    <label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="wednesday" class="days recurring" checked="" onclick="return false;"><span class="checkmark">W</span></label>
                                                
                                                    <label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="thursday" class="days recurring" checked="" onclick="return false;"><span class="checkmark">T</span></label>
                                                
                                                    <label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="friday" class="days recurring" checked="" onclick="return false;"><span class="checkmark">F</span></label>
                                                
                                                    <label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="saturday" class="days recurring" onclick="return false;"><span class="checkmark">S</span></label>
                                                
                                                    <label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="sunday" class="days recurring" onclick="return false;"><span class="checkmark">S</span></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">End On <span class="text-danger">*</span></label>
                                                <div class="cal-icon"><input class="form-control datetimepicker" type="text"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="custom-control form-check">
                                                <input type="checkbox" class="form-check-input" id="customCheck2">
                                                <label class="form-check-label" for="customCheck2">Indefinite</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="col-form-label">Accept Extra Hours </label>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input" id="customSwitch3" checked="">
                                                    <label class="form-check-label" for="customSwitch3"></label>
                                                  </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="col-form-label">Publish </label>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input" id="customSwitch4" checked="">
                                                    <label class="form-check-label" for="customSwitch4"></label>
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


</div>
<!-- end main wrapper-->


</body>

</html>
@include('includes/footer')

<script>    
    $(document).ready(function() {
        $('#department_addschedule').on('change', function() {
            var departmentID = $(this).val();
           if(departmentID) {
               $.ajax({
                   url: '/employeeByDepartment/'+departmentID,
                   type: "GET",
                   dataType: "json",
                   // data: {id, departmentID},
                   success:function(response)
                   {
                        $('#employee_addschedule').empty();
                        $("#employee_addschedule").append('<option>Select Employee</option>');
                     if(response)
                     { 
                        $.each(response,function(key,value){
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
    $(document).ready(function() {
        $('.shift_addschedule').on('change', function() {
            var shiftId = $(this).val();
           if(shiftId) {
               $.ajax({
                   url: '/shiftDetails/'+shiftId,
                   type: "GET",
                   dataType: "json",
                   success:function(response)
                   {
                        $.each(response, function(key,value){
                            // console.log(value);
                            $.each(value, function(k,v){
                                // console.log(k);
                                $('#add_schedule_form #'+k).val(v);
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