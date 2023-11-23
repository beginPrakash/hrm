<?php echo $__env->make('includes/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('includes/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php
$currentMonth = date('F', mktime(0, 0, 0, $month, 10));
$currentMonthNum = $month;

$start_date = "01-".$currentMonthNum."-".$year;
$start_time = strtotime($start_date);
$end_time = strtotime("+1 month", $start_time);

$maxDays=date('t', strtotime($start_date));
?>
    <div class="main-wrapper">
    

   <!-- Page Wrapper -->
            <div class="page-wrapper">
                <div class="content container-fluid">
                
                    <?php echo $__env->make('flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>   
                    <!-- Page Header -->
                    <?php echo $__env->make('includes/breadcrumbs', ['title' => $title], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    
                    <!-- Search Filter -->
                    <form action="/attendance" method="post">
                        <div class="row filter-row">
                            <?php echo csrf_field(); ?>
                            <div class="col-sm-6 col-md-3">  
                                <div class="form-group form-focus">
                                    <select class="select floating" name="employee">
                                        <option value="">-</option>
                                    <?php
                                    if(isset($allEmployees))
                                    {
                                        foreach($allEmployees as $ae)
                                        {
                                        ?>
                                            <option value="<?php echo $ae->user_id; ?>" <?php echo ($emp==$ae->user_id)?'selected':''; ?>><?php echo $ae->first_name.' '.$ae->last_name; ?></option>
                                        <?php
                                        }
                                    }?>
                                    </select>
                                    <label class="focus-label">Employee Name</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3"> 
                                <div class="form-group form-focus select-focus">
                                    <?php
                                    $monthArray = array(
                                        '01'    =>  'Jan', '02' => 'Feb', '03' => 'Mar',
                                        '04'    =>  'Apr', '05' => 'May', '06' => 'Jun',
                                        '07'    =>  'Jul', '08' => 'Aug', '09' => 'Sep',
                                        '10'    =>  'Oct', '11' => 'Nov', '12' => 'Dec',
                                    );
                                    ?>
                                    <select class="select floating" name="month"> 
                                        <option value="">-</option>
                                        <?php foreach($monthArray as $makey => $ma) { ?>
                                            <option value="<?php echo $makey; ?>" <?php echo ($makey==$month)?'selected':''; ?>><?php echo $ma; ?></option>
                                        <?php } ?>
                                    </select>
                                    <label class="focus-label">Select Month</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3"> 
                                <div class="form-group form-focus select-focus">
                                    <select class="select floating" name="year"> 
                                        <option value="">-</option>
                                        <?php for($y=date('Y');$y>=2015;$y--) { ?>
                                            <option value="<?php echo $y; ?>" <?php echo ($year==$y)?'selected':''; ?>><?php echo $y; ?></option>
                                        <?php } ?>
                                    </select>
                                    <label class="focus-label">Select Year</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">  
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success" name="search" value="search"> Search </button>  
                                </div>
                            </div>  
                        </div>
                    </form>   
                    <!-- /Search Filter -->
                    
                    <div class="row">
                        <div class="col-lg-12 small-box-line">
                            <div class="small-box green"></div><span class="smmr-6">All OK </span>
                            <div class="small-box blue"></div><span class="smmr-6">No schedule </span>
                            <div class="small-box red"></div><span class="smmr-6">Missing Punch </span>
                            <div class="small-box yellow"></div><span class="smmr-6">Not Following Schedule </span>
                            <!-- <div class="small-box black"></div><span class="smmr-6">Overtime </span> -->
                        </div>
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-striped custom-table table-nowrap mb-0">
                                    <thead>
                                        <tr>
                                            <th colspan="32">Month : <?php echo $currentMonth.', '.$year; ?></th>
                                        </tr>
                                        <tr>
                                            <th>Employee</th>
                                            <?php
                                            for($i=1; $i<=$maxDays; $i++)
                                            {
                                            ?>
                                            <th><?php echo $i; ?></th>
                                            <?php
                                            }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if(isset($attEmployees))
                                        {
                                            $image = "assets/img/profiles/avatar-09.jpg";
                                            foreach($attEmployees as $employee)
                                            { 
                                                //dd($employee);
                                                if($employee->resigned_date !== NULL && (int)date('m', strtotime($employee->resigned_date)) < (int)$month)
                                                {
                                                    continue;
                                                }//else{ echo $month;}
                                                if($employee->profile != Null)
                                                {
                                                    $image = 'uploads/profile/'.$employee->profile;
                                                }
                                            ?>
                                            <tr>
                                                <td>
                                                    <h2 class="table-avatar">
                                                        <a class="avatar avatar-xs" href="profile.php"><img alt="" src="<?php echo $image; ?>"></a>
                                                        <a href="profile.php"><?php echo $employee->first_name.' '.$employee->last_name;?></a>
                                                    </h2>
                                                </td>

                                                <?php // echo date('t');
                                                // for($i=$start_time; $i<$end_time; $i+=86400)
                                                for($i=1; $i <= $maxDays; $i++)
                                                { 
                                                    $date = $year."-".$currentMonthNum."-".$i;

                                                    $emloyeeAttendance = App\Models\AttendanceDetails::where('user_id', $employee->user_id)->where('employee_id', $employee->emp_generated_id)->whereDate('attendance_on', $date)->first();

                                                    // get shift details
                                                    $shiftDetails = App\Models\Scheduling::where('employee',$employee->user_id)->where('shift_on', date('Y-m-d',strtotime($date)))->where('status','active')->get()->first();

                                                    $tdValue = '';

                                                    if(!empty($shiftDetails) && (in_array($shiftDetails->shift, array(3))))
                                                    {
                                                        $tdValue = 'FS';
                                                    }
                                                    else
                                                    {
                                                        if(empty($emloyeeAttendance))
                                                        {
                                                           
                                                            $encoded = base64_encode(json_encode($date.'/'.$employee->user_id));
                                                            $tdValue = getAttendanceText($shiftDetails,$encoded);
                                                        }
                                                        else
                                                        {
                                                            if($emloyeeAttendance->day_type === 'off' && ($emloyeeAttendance->attendance_time === '0'))
                                                            {
                                                                //echo($emloyeeAttendance->id);
                                                                $encoded = base64_encode(json_encode($date.'/'.$employee->user_id));
                                                                $tdValue = getAttendanceText($shiftDetails,$encoded);
                                                            }
                                                            else
                                                            {
                                                                $encoded = base64_encode(json_encode($date.'/'.$employee->user_id));

                                                                $firstclockin = App\Models\AttendanceDetails::where('user_id', $employee->user_id)->where('employee_id', $employee->emp_generated_id)->where('punch_state', 'clockin')->whereDate('attendance_on', $date)->first();
                                                                $lastclockout = App\Models\AttendanceDetails::where('user_id', $employee->user_id)->where('employee_id', $employee->emp_generated_id)->where('punch_state', 'clockout')->whereDate('attendance_on', $date)->limit(1)->orderBy('id', 'desc')->first();
                                                                
                                                                $shcolor = '';
                                                                $shicon = '';
                                                                $flag = 0;

                                                                $minStartTime_24 = (isset($shiftDetails->min_start_time))?date('H:i', strtotime($shiftDetails->min_start_time)):'0';
                                                                $maxStartTime_24 = (isset($shiftDetails->max_start_time))?date('H:i', strtotime($shiftDetails->max_start_time)):'0';
                                                                $minEndTime_24 = (isset($shiftDetails->min_end_time))?date('H:i', strtotime($shiftDetails->min_end_time)):'0';
                                                                $maxEndTime_24 = (isset($shiftDetails->max_end_time))?date('H:i', strtotime($shiftDetails->max_end_time)):'0';
                                                                if((isset($firstclockin->attendance_time) && checkDateTimeInBetween($firstclockin->attendance_time, $minStartTime_24, $maxStartTime_24)==1) && (isset($lastclockout->attendance_time) && checkDateTimeInBetween($lastclockout->attendance_time, $minEndTime_24, $maxEndTime_24)==1))
                                                                {
                                                                    $shcolor = 'text-success';
                                                                    $shicon = 'fa-check';
                                                                }else{
                                                                    $shcolor = 'text-warning';
                                                                    $shicon = 'fa-info-circle';
                                                                    $flag = 1;
                                                                }

                                                                // if((empty($firstclockin) || empty($lastclockout)) || ($firstclockin->attendance_time ==='0' || $lastclockout->attendance_time ==='0'))
                                                                // {
                                                                //     $flag = 1;
                                                                //     $shcolor = 'text-danger';
                                                                //     $shicon = 'fa-info-circle';
                                                                // }

                                                                // if(empty($shiftDetails))
                                                                // {
                                                                //     $shcolor = 'text-info';
                                                                //     $shicon = 'fa-info-circle';
                                                                //     $flag = 1;
                                                                //     $overtimeDetails = App\Models\Overtime::first();
                                                                //     $workingHours = $overtimeDetails->working_hours;
                                                                // }

                                                                // if($flag === 0)
                                                                // {
                                                                //     $shcolor = 'text-success';
                                                                //     $shicon = 'fa-check';
                                                                // }
                                                                
                                                                $tdValue = '<a href="javascript:void(0);" class="popupAttn" data-id="'.$encoded.'"><i class="fa '.$shicon.' '.$shcolor.'"></i></a>';
                                                            }
                                                        }
                                                    }
                                                    
                                                    echo '<td>'.$tdValue.'</td>';
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
                </div>
                <!-- /Page Content -->
                
                <!-- Attendance Modal -->
                <div class="modal custom-modal fade" id="attendance_info" role="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Attendance Info</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="popup_body">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Attendance Modal -->

                

                <!-- Add Attendance Modal -->
                <div id="add_Form" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Attendance</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                              
                                <form  action="/attendanceInsert" method="post" id="addAttendance" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                    <!-- <div class="form-group">
                                        <label>Company <span class="text-danger">*</span></label>
                                        <select class="select" name="company" id="company">
                                            <option value="">Select company</option>
                                                <?php
                                                if(isset($companies))
                                                {
                                                    foreach ($companies as $company) {?>
                                                    <option  value="<?=$company->id?>"><?=$company->name?></option>
                                                <?php  } } ?> 
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Branch <span class="text-danger">*</span></label>
                                        <select class="select" id="branch" name="branch">>
                                            <option  value="">Select Branch</option>
                                        </select>
                                    </div> -->
                                    <!-- <div class="form-group">
                                        <label>Attendance Date <span class="text-danger">*</span></label>
                                        <div class="cal-icon">
                                            <input class="form-control datetimepicker" type="text" name="attendance_date" id="attendance_date">
                                        </div>
                                    </div> -->
                                    <div class="form-group">
                                        <label>Import File <span class="text-danger">*</span></label>
                                        <input class="form-control" value="" readonly type="file" name="attendance_file">
                                    </div>
                                    <div class="submit-section">
                                        <button class="btn btn-primary submit-btn">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Add Attendance Modal -->
                
            </div>
            <!-- Page Wrapper -->


</div>
<!-- end main wrapper-->

<?php echo $__env->make('includes/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#addAttendance").validate({
            rules: {
                company: {
                    required : true
                },
                branch: {
                    required : true
                },
                attendance_file: {
                    required : true,
                    accept: 'csv'
                }
            },
            messages: {
                company: {
                    required : 'Choose company',
                },
                branch: {
                    required : 'Choose branch',
                },
                attendance_file: {
                    required : 'Attendance file is required',
                    accept: 'Please upload CSV file'
                }            
            },
       });
       
    });
</script>

<script type="text/javascript">
    $(document).on('click', '.popupAttn', function()
    {
        var row_data = $(this).data('id');
        var row_decode = atob(row_data);
        var arr = row_decode.split('/');
        //console.log(row_decode);
        var userId = arr[1];
        var attnDate = arr[0];
        $.ajax({
            type: "POST",
            url: "<?php echo e(url('getAttendanceDetails/')); ?>",
            data: { userId: userId, attnDate:attnDate, "_token": "<?php echo e(csrf_token()); ?>"},
            dataType: 'json',
            success: function(res) { 
                if(res !='')
                {
                    $('#popup_body').empty().append(res);
                    $('#attendance_info').modal('show');
                }
            }
      });
    })

    $(document).on('click', '.CreateAttPopup', function()
    {
        var row_data = $(this).data('id');
        var row_decode = atob(row_data);
        var arr = row_decode.split('/');
        //console.log(row_decode);
        var userId = arr[1];
        var attnDate = arr[0];
        $.ajax({
            type: "POST",
            url: "<?php echo e(url('getAttendanceDetails/')); ?>",
            data: { userId: userId, attnDate:attnDate,'popup_type':'create_attn', "_token": "<?php echo e(csrf_token()); ?>"},
            dataType: 'json',
            success: function(res) { 
                if(res !='')
                {
                    $('#popup_body').empty().append(res);
                    $('#attendance_info').modal('show');
                }
            }
      });
    })

</script>

<script type="text/javascript">
    function saveOtApprove() {
       // e.preventDefault(); 
       var attnUserId = $('#attnUserId').val();
       var attnDate = $('#attnDate').val();
       var ottime = $('#ottime').text();
       var approve_status = $("#approve_status").attr("checked") ? 1 : 0;//$('#approve_status').val();
       var approve_remark = $('#approve_remark').val();
       var start_time = $('#start_time').val();
       var end_time = $('#end_time').val();
       $.ajax({
           type: "POST",
           url: "<?php echo e(url('approveOt/')); ?>",
           data: {attnUserId:attnUserId, attnDate:attnDate, ottime:ottime, approve_status:approve_status, approve_remark:approve_remark, start_time:start_time, end_time:end_time, "_token": "<?php echo e(csrf_token()); ?>"},
           success: function( msg ) {
               alert( 'Over Time approved successfully.' );
               location.reload();
           }
       });
   }

</script><?php /**PATH C:\wamp64_new\www\hrm\resources\views/lts/attendance.blade.php ENDPATH**/ ?>