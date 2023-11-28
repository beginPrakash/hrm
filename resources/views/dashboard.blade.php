@include('includes/header');
@include('includes/sidebar');
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
        @include('flash-message')  
        @php $is_admin = Session::get('is_admin'); @endphp
        <!-- Page Header -->
        <div class="page-header">
            
            <div class="row">
                <div class="col-sm-4">
                    <h3 class="page-title">Welcome <?php echo ucfirst($username); ?>!</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ul>
                </div>
                <div class="col-sm-4">
                    @if($is_admin != 1)
                        @php $is_manual_punchin = _get_emp_manual_punchin($user_id ?? 0); @endphp
                        @if($is_manual_punchin == 1)
                            @if(empty($firstclockin) && empty($lastclockout))
                                <div class="">
                                    <a href="{{route('save_clock_data','in')}}" class="btn add-btn"><i class="fa fa-clock"></i>Punch In</a>
                                </div>
                            @elseif(!empty($firstclockin) && empty($lastclockout))
                                <div class="">
                                    <a href="{{route('save_clock_data','out')}}" class="btn add-btn"><i class="fa fa-clock"></i>Punch Out</a>
                                </div>
                            @endif
                        @endif
                    @endif
                </div>
                <div class="col-sm-4">
                    @if($is_admin != 1)
                        <div class="card dash-widget">
                            <div class="card-body">
                                <span class="dash-widget-icon"><i class="fas fa-gem"></i></span>
                                <div class="dash-widget-info">
                                    <h3>{{number_format($totpayable,2) ?? 0}} KWD</h3>
                                    <span>Total Indemnity Amount</span>
                                </div>
                            </div>
                        </div>                        
                    @endif
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
        @if($is_admin != 1)
            <div class="row">
                <div class="col-md-12">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <h3>Schedule</h3>
                        </div>
                        <div class="table-responsive" style="margin-left:20px">
                            <table class="table table-striped custom-table" id="datatable">
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
                                        @endforeach
                                
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
        @endif
        
        <div class="row">
            @if($is_admin != 1)
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-6">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <!-- <span class="dash-widget-icon"><i class="fa fa-cubes"></i></span> -->
                            <div class="dash-widget-info" style="text-align: left;">
                                <h3>Annual leave</h3>
                                <span>Balance leaves {{$balance_annual_leave_total['remaining_leave_withoutreq'] ?? 0}}</span>
                                <span>Amount KWD {{$balance_annual_leave_total['balance_leave_amount'] ?? 0}}</span>   
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

                                    @if(isset($annual_leave_list) && count($annual_leave_list) > 0)
                                        @foreach($annual_leave_list as $data)
                                                                     
                                            <tr>
                                                <td>{{date('d-m-Y', strtotime($data->leave_from))}} to {{date('d-m-Y', strtotime($data->leave_to))}}</td>
                                                <td>{{$data->leave_days}}</td>
                                                <td>{{$data->leave_reason}}</td>
                                            </tr>
                                            
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" align="center">No dat found</td>
                                        </tr>
                                    @endif
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
                                <span>Sick leaves total {{$balance_sick_leave_total['totalLeaveDays'] ?? 0}}</span>
                                <span>Sick leaves taken {{$balance_sick_leave_total['taken_leave'] ?? 0}}</span>   
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

                                    @if(isset($sick_leave_list) && count($sick_leave_list) > 0)
                                        @foreach($sick_leave_list as $data)
                                                                     
                                            <tr>
                                                <td>{{date('d-m-Y', strtotime($data->leave_from))}} to {{date('d-m-Y', strtotime($data->leave_to))}}</td>
                                                <td>{{$data->leave_days}}</td>
                                                <td>{{$data->leave_reason}}</td>   
                                            </tr>
                                            
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" align="center">No dat found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        
    </div>
    <!-- /Page Content -->

</div>
@include('includes/footer');
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

