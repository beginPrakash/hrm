@include('includes/header');
@include('includes/sidebar');
<style>
    .dash-widget .card-body .dash-widget-info-btn {
        width: calc(100% - 70px);
    }
</style>
<?php
$username = Session::get('username');
$user_id = Session::get('user_id');
$is_user_sale_designation = _is_user_sale_designation($user_id);
$get_analytics = _get_analytics('show_analytics');
?>
<div class="page-wrapper">

    <!-- Page Content -->
    <div class="content container-fluid">
        @include('flash-message')
        @php $is_admin = Session::get('is_admin'); @endphp
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
                    @if($is_admin != 1)
                    @php $is_manual_punchin = _get_emp_manual_punchin($user_id ?? 0); @endphp
                    @if($is_manual_punchin == 1)
                    @if(empty($firstclockin) && empty($lastclockout))
                    <div class="d-flex justify-content-center punch-btn">
                        <a href="{{route('save_clock_data','in')}}" class="btn add-btn"><i class="fa fa-clock"></i>Check
                            In</a>
                    </div>
                    @elseif(!empty($firstclockin) && empty($lastclockout))
                    <div class="d-flex justify-content-center punch-btn">
                        <a href="{{route('save_clock_data','out')}}" class="btn success-btn"><i
                                class="fa fa-clock"></i>Check Out</a>
                    </div>
                    @endif
                    @endif
                    @endif
                </div>
                <div class="col-sm-4">
                    @if($is_admin != 1)
                    <div class="card dash-widget mb-0">
                        <div class="card-body py-4">
                            <span class="dash-widget-icon"><i class="fas fa-gem"></i></span>
                            <div class="dash-widget-info text-center">
                                <h3>{{number_format(($indemnityDetails->total_amount ?? 0),2)}} KWD</h3>
                                <span>Total Indemnity Amount</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        
        @if($get_analytics == '1')
        @if(!empty($is_user_sale_designation))
        <!-- Search Filter -->
        <form method="post" action="{{route('dashboard')}}" id="search_form">
            @csrf
            <div class="row">            
                <div class="col-sm-6 col-md-2">  
                    <div class="form-group form-focus focused">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker" type="text" name="search_date" id="search_date" value="<?php echo (isset($search['search_date']) && !empty($search['search_date']))? $search['search_date']: date('d-m-Y'); ?>">
                        </div>
                        <label class="focus-label">From</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-2 srch_btn">
                    <div class="d-grid"> 
                        <button type="submit" id="fwb" class="btn add-btn"><i class="fa fa-search"></i>Search</button> 
                    </div>  
                </div>
            </div>
        </form>
        <div class="page-header">
            <section id="demos">
            <div class="row">
                <div class="large-12 columns">
                    <div class="owl-carousel owl-theme">
                        <div class="item">
                            <div class="row align-items-center  upselling-wrapper">
                                <div class="col-sm-3">
                                    <div class="card dash-widget mb-0">
                                        <div class="py-4">
                                        <a href="#" data-toggle="modal" data-target="#search_modal">
                                            <div class="dash-widget-info text-center">
                                                <h2 class="sales_title">Daily Target</h2>
                                                <span>{{number_format($daily_target ?? 0,2)}} KWD</span>
                                                <div class="mtd-data">
                                                    <b>MTD</b>
                                                    <span>{{number_format($mtd_target ?? 0,2)}} KWD</span>
                                                </div>
                                            </div>
                                        </a>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <div class="item">
                            <div class="row align-items-center  upselling-wrapper">
                                <div class="col-sm-3">
                                    <div class="card dash-widget mb-0">
                                        <div class="py-4">
                                            <a href="#" data-toggle="modal" data-target="#search_modal">
                                                <div class="dash-widget-info text-center">
                                                    <h2 class="sales_title">Daily Sales</h2>
                                                    <span>{{number_format($daily_sale ?? 0,2)}} KWD</span>
                                                        @php $calculate_per = _calculate_per($daily_target,$daily_sale); @endphp
                                                        {!!$calculate_per!!}
                                                    <div class="mtd-data">
                                                        <b>MTD</b>
                                                        <span>{{number_format($mtd_sale ?? 0,2)}} KWD</span>
                                                        @php $calculate_per = _calculate_per($mtd_target,$mtd_sale); @endphp
                                                        {!!$calculate_per!!}
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="row align-items-center  upselling-wrapper">
                                <div class="col-sm-3">
                                    <div class="card dash-widget mb-0">
                                        <div class="py-4">
                                            <div class="dash-widget-info text-center">
                                                <h2 class="sales_title">Daily Score</h2>
                                                <span>{{number_format($daily_score ?? 0,2)}}</span>
                                                <div class="mtd-data">
                                                    <b>MTD Average</b>
                                                    <span>{{number_format($mtd_score ?? 0,2)}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="row align-items-center  upselling-wrapper">
                                <div class="col-sm-3">
                                    <div class="card dash-widget mb-0">
                                        <div class="py-4">
                                            <div class="dash-widget-info text-center">
                                                <h2 class="sales_title">Complaint</h2>
                                                <span>{{$daily_cc ?? 0}}</span>
                                                <div class="mtd-data">
                                                    <b>MTD Total</b>
                                                    <span>{{$mtd_cc ?? 0}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        

                    </div>
                </div>
            </div>
        </section>
            
        </div>
        @endif
        @endif
        @if($is_admin != 1)
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

                                @if(isset($sched_data) && count($sched_data) > 0)
                                @foreach($sched_data as $sh)
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
                                                                    $date_a = strtotime($emloyeeScheduleToday->start_time);
                                                                    $date_b = strtotime($emloyeeScheduleToday->end_time);

                                                                    $diff = round(abs($date_a - $date_b) / 60,2);
                                                                    $break_time = get_break_time_for_shift($emloyeeScheduleToday->shift);
                                                                    $gap = convertToHoursMinutes($diff,$break_time);
                                                                    $sched = $emloyeeScheduleToday->shift_details->shift_name.'('. $gap.' hrs)';
                                                                }
                                                                $sh->shift_details = json_encode($emloyeeScheduleToday);
                                                                    
                                                                $encodedData = base64_encode(json_encode($sh));
                                                            ?>
                                    <div class="user-add-shedule-list">
                                        <h2>
                                            <a href="javascript:void(0);" class="editSchedule" data-toggle="modal" data-target="#edit_schedule" data-data="<?php echo $encodedData; ?>" style="border:2px dashed #1eb53a">
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
                                        <!-- <a href="#"  data-toggle="modal" data-target="#add_schedule" class="addSchedule" data-data="<?php echo $encodedData; ?>">
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
                                @endforeach

                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        @endif

        <div class="row three-box-main">
            @if($is_admin != 1)
            <div class="col-md-4 col-sm-4 col-lg-4 col-xl-4">
                <div class="card dash-widget three-box " data-toggle="modal" data-target="#exampleModal">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-tree" aria-hidden="true"></i></span>
                        <div class="dash-widget-info pl-2" style="text-align: left;">
                            <h4>
                            @php 
                                $e_sal = (isset($sched_data[0]->employee_salary) && !empty($sched_data[0]->employee_salary)) ? $sched_data[0]->employee_salary->basic_salary : 0;
                                $cal_leave = (isset($balance_annual_leave_total) && $balance_annual_leave_total['totalLeaveDays']>0 )?$balance_annual_leave_total['totalLeaveDays']:0; 
                                $used_leave = $sched_data[0]->used_leave ?? 0;
                                $bal_leave = (int)($cal_leave - $used_leave);
                            @endphp     
                            {{number_format(_calculate_salary_by_days($e_sal,$bal_leave ?? 0),2)}} KWD
                            </h4>
                            <h4>{{$bal_leave ?? 0}} DAYS</h4>
                            <span>Annual leave</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-lg-4 col-xl-4">
                <div class="card dash-widget three-box" data-toggle="modal" data-target="#phleaveModal">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-briefcase" aria-hidden="true"></i></span>
                        <div class="dash-widget-info pl-2" style="text-align: left;">
                            @php 
                            $days = $user->public_holidays_balance ?? 0;
                            $sal = (isset($user->employee_salary) && !empty($user->employee_salary))  ?$user->employee_salary->basic_salary : 0;
                            $bal = _calculate_salary_by_days($sal,$days);
                            @endphp
                            <h4>{{number_format($bal,2)}} KWD</h4>
                            <h4>{{$days}} DAYS</h4>
                            <span>Public Holidays</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-lg-4 col-xl-4">
                <div class="card dash-widget three-box" data-toggle="modal" data-target="#sickleaveModal">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-bed" aria-hidden="true"></i></span>
                        <div class="dash-widget-info pl-2" style="text-align: left;">

                            <h4>{{$balance_sick_leave_total['totalLeaveDays'] ?? 0}} Days</h4>
                            <!-- <span>Sick leaves taken {{$balance_sick_leave_total['taken_leave'] ?? 0}}</span> -->
                            <span>Sick Leaves</span>
                        </div>
                    </div>
                   
                </div>
            </div>
            @endif
        </div>


    </div>
    <!-- /Page Content -->

    <div id="search_modal" class="modal custom-modal fade " role="dialog">
        @include('up_selling_management/search_modal')
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">Annual leave</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
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
                            @if(isset($annual_leave_list) && count($annual_leave_list) > 0)
                                @foreach($annual_leave_list as $data)

                                <tr>
                                    <td>{{date('d-m-Y', strtotime($data->leave_from))}} to {{date('d-m-Y',
                                        strtotime($data->leave_to))}}</td>
                                    <td>{{$data->leave_days}}</td>
                                </tr>

                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2" align="center">No data found</td>
                                </tr>
                            @endif
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
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
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
                            @if(isset($sick_leave_list) && count($sick_leave_list) > 0)
                                @foreach($sick_leave_list as $data)

                                <tr>
                                    <td>{{date('d-m-Y', strtotime($data->leave_from))}} to {{date('d-m-Y',
                                        strtotime($data->leave_to))}}</td>
                                    <td>{{$data->leave_days}}</td>
                                </tr>

                                @endforeach
                                @else
                                <tr>
                                    <td colspan="2" align="center">No dat found</td>
                                </tr>
                                @endif
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="phleaveModal" tabindex="-1" aria-labelledby="phleaveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="phleaveModalLabel">Public Holidays</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <table width="100%" class="table-striped custom-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Day</th>
                                <th>Holiday</th>
                                <th>PH Leave Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($holidayWork) && count($holidayWork) > 0)
                                @foreach($holidayWork as $hw)

                                <tr>
                                    <td>{{date('d-m-Y', strtotime($hw->attendance_on))}}</td>
                                    <td><?php echo $hw->holiday_day; ?></td>
                                    <td><?php echo $hw->title ?></td>
                                    <td>+1</td>
                                </tr>

                                @endforeach
                            @else
                                    @if(isset($annual_leave_list) && count($annual_leave_list) < 0) 
                                        <tr>
                                            <td colspan="4" align="center">No data found</td>
                                        </tr>
                                    @endif
                               
                            @endif
                            @if(isset($annual_leave_list) && count($annual_leave_list) > 0) 
                                @foreach($annual_leave_list as $key => $val) 
                                    @if($val->is_post_transaction == 1 && $val->claimed_public_days > 0)
                                        <tr>
                                            <td>{{date('d-m-Y', strtotime($val->leave_from))}} to {{date('d-m-Y',
                                            strtotime($val->leave_to))}}</td>
                                            <td></td>
                                            <td></td>
                                            <td>-{{$val->claimed_public_days}}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                                <tr>    
                                    <td colspan="3">Today days worked <small>(Based on scheduling)</small></td>
                                    <td>{{$user->public_holidays_balance ?? 0}} - days </td>
                                </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- Edit Schedule Modal -->
    <div id="edit_schedule" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">View Schedule</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="add_schedule_form" action="/scheduleUpdate" method="post">
                                @csrf
                                <input type="hidden" name="schedule_id" id="schedule_id" value="">
                                <input type="hidden" name="edit_start_from_date" id="edit_start_from_date" value="">
                                <div class="row">
                                    <div class="col-sm-6" id="edit_dep_drop">
                                        <div class="form-group">
                                            <label class="col-form-label">Department <span class="text-danger">*</span></label>
                                            <select class="select editsched" name="department_addschedule" id="department_addschedule" disableddisabled>
                                                <option value="">Select Department</option>
                                                <?php foreach ($department as $dept) {?>
                                                    <option value="<?php echo $dept->id?>"><?php echo $dept->name?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Employee Name</label>
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
                                                <input class="form-control datetimepicker editsched" type="text" name="shift_date" id="edit_shift_date" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Shifts</label>
                                            <select class="select shift_addschedule editsched" name="shift_addschedule" id="edit_shift_addschedule" disabled>
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
                                            <label>Min Start Time</label>
                                            <div class="cal-icon">
                                                <input type="text" class="form-control editsched edit_min_s_time datewithtime" id="min_start_time" name="min_start_time" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Start Time</label>
                                            <div class="cal-icon">
                                                <input type="text" class="form-control editsched edit_s_time datewithtime" name="start_time" id="start_time" disabled>
                                            </div>                                  
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Max Start Time</label>
                                            <div class="cal-icon">
                                                <input type="text" class="form-control editsched edit_max_s_time datewithtime" name="max_start_time" id="max_start_time" disabled>
                                            </div>                                          
                                        </div>
                                    </div>      
                                    <div class="col-md-4">
                                        <div class="form-group" >
                                            <label>Min End Time</label>
                                            <div class="cal-icon">
                                                <input type="text" class="form-control editsched edit_min_e_time datewithtime" name="min_end_time" id="min_end_time" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>End Time</label>
                                            <div class="cal-icon">
                                                <input type="text" class="form-control editsched edit_e_time datewithtime" name="end_time" id="end_time" disabled>
                                            </div>                                  
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Max End Time </label>
                                            <div class="cal-icon">
                                                <input type="text" class="form-control editsched edit_max_e_time datewithtime" name="max_end_time" id="max_end_time" disabled>
                                            </div>                                          
                                        </div>
                                    </div>  
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Break Time (In Minutes) </label>
                                            <input type="text" class="form-control editsched edit_break_time" name="break_time" id="break_time" disabled>                                            
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label">Accept Extra Hours </label>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="customSwitch1" checked="" name="extra_hours" value="1" disabled>
                                                <label class="form-check-label" for="customSwitch1"></label>
                                              </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label">Publish </label>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="customSwitch2" checked="" name="publish" value="1" disabled>
                                                <label class="form-check-label" for="customSwitch2"></label>
                                              </div>
                                        </div>
                                    </div>
                                </div>
                            
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Edit Schedule Modal -->

</div>
@include('includes/footer');
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="{{ asset('assets/css/bootstrap-new.css') }}" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.js" integrity="sha512-gY25nC63ddE0LcLPhxUJGFxa2GoIyA5FLym4UJqHDEMHjp8RET6Zn/SHo1sltt3WuVtqfyxECP38/daUc/WVEA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
    $('#annual_data').hide();
    $('#sick_data').hide(); // Fix the typo here
    $(document).on('click', '.sick_history_btn', function () {
        $('#sick_data').toggle();
    });

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

                }
            });
    })

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
    $(document).on('click','.serc_btn',function(){
        var date_val = $('#searchsale_date').val();
        var sell_id_default = $('#sell_id_default').val();
        $.ajax({
            url: "{{route('dashboard.search_sales')}}",
            type: "POST",
            dataType: "json",
            data: {"_token": "{{ csrf_token() }}", date_val:date_val,sell_id_default:sell_id_default},
            success:function(response)
                {
                    $('#search_modal').html('');
                    $('#search_modal').html(response.html).fadeIn();
                }
        });
    })

    $('.owl-carousel').owlCarousel({
        loop:true,
        margin:0,
        nav:true,
        responsiveClass: true,
        dots: false,
        responsive: {
            0: {
            items: 1,
            },
            4: {
            items: 1,
            },
            1000: {
            items: 4,
            }
        }
    })
</script>