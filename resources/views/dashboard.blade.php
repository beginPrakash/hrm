@include('includes/header');
@include('includes/sidebar');

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
        @if($is_admin != 1)
            <div class="row">
                <div class="col-md-12">
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
                                        <td>OFF Day</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td> 
                                        <td></td> 
                                    </tr>
                                @elseif($data->shift==2)
                                    <tr>
                                        <td>PH Day</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td> 
                                        <td></td> 
                                    </tr>
                                @elseif($data->shift==3)
                                    <tr>
                                        <td>Free Shift</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td> 
                                        <td></td> 
                                    </tr>                            
                                @elseif($data->shift==7)
                                    <tr>
                                        <td>AL</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td> 
                                        <td></td>    
                                    </tr>                           
                                @elseif($data->shift==8)
                                    <tr>
                                        <td>SL</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td> 
                                        <td></td> 
                                    </tr>                            
                                @elseif($data->shift==9)
                                    <tr>
                                        <td>UL</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td> 
                                        <td></td> 
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
        @endif
    </div>
    <!-- /Page Content -->

</div>

@include('includes/footer');