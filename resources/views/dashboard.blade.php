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
    
        <!-- Page Header -->
        <div class="page-header">
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
        @php $is_admin = Session::get('is_admin'); @endphp
        <div class="row">
            @if($is_admin != 1)
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-6">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <!-- <span class="dash-widget-icon"><i class="fa fa-cubes"></i></span> -->
                            <div class="dash-widget-info" style="text-align: left;">
                                <h3>Annual leave</h3>
                                <span>Balance leaves {{$balance_annual_leave_total['remaining_leave_withoutreq'] ?? 0}}</span>
                                <span>Amount ${{$balance_annual_leave_total['balance_leave_amount'] ?? 0}}</span>   
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
                                <span>Sick leaves taken {{$balance_sick_leave_total['remaining_leave_withoutreq'] ?? 0}}</span>   
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
                                
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        @if($is_admin != 1)
            <div class="row">
                <div class="col-md-12">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <h3>Schedule</h3>
                        </div>
                        <div class="table-responsive">
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

                                    @if(isset($sched_data) && count($sched_data) > 0)
                                        @foreach($sched_data as $data)
                                            @php $clockin = _get_attendance_time($data->shift_on,$user_id,'clockin');
                                                $clockout = _get_attendance_time($data->shift_on,$user_id,'clockout');
                                            @endphp
                                            @if($data->shift==1)
                                                <tr>
                                                    <td>{{date('d-m-Y', strtotime($data->shift_on))}}</td>
                                                    <td colspan="6" align="center">OFF Day</td>
                                                </tr>
                                            @elseif($data->shift==2)
                                                <tr>
                                                    <td>{{date('d-m-Y', strtotime($data->shift_on))}}</td>
                                                    <td colspan="6" align="center">PH Day</td>
                                                </tr>
                                            @elseif($data->shift==3)
                                                <tr>
                                                    <td>{{date('d-m-Y', strtotime($data->shift_on))}}</td>
                                                    <td colspan="6" align="center">Free Shift</td>
                                                </tr>                            
                                            @elseif($data->shift==7)
                                                <tr>
                                                    <td>{{date('d-m-Y', strtotime($data->shift_on))}}</td>
                                                    <td colspan="6" align="center">AL</td>   
                                                </tr>                           
                                            @elseif($data->shift==8)
                                                <tr>
                                                    <td>{{date('d-m-Y', strtotime($data->shift_on))}}</td>
                                                    <td colspan="6" align="center">SL</td>
                                                </tr>                            
                                            @elseif($data->shift==9)
                                                <tr>
                                                    <td>{{date('d-m-Y', strtotime($data->shift_on))}}</td>
                                                    <td colspan="6" align="center">UL</td>
                                                </tr>  
                                            @else                         
                                            <tr>
                                                <td>{{date('d-m-Y', strtotime($data->shift_on))}}</td>
                                                <td>{{_convert_time_to_12hour_format_bydate($data->min_start_time)}}</td>
                                                <td>{{_convert_time_to_12hour_format_bydate($data->max_start_time)}}</td>
                                                <td>{{_convert_time_to_12hour_format_bydate($data->min_end_time)}}</td>
                                                <td>{{_convert_time_to_12hour_format_bydate($data->max_end_time)}}</td>
                                                <td>{{_convert_time_to_12hour_format_bydate($clockin)}}</td>
                                                <td>{{_convert_time_to_12hour_format_bydate($clockout)}}</td>
                                            </tr>
                                            @endif
                                        @endforeach
                                
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
        @endif
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

