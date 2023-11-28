<?php echo $__env->make('includes/header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('includes/sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php
$currentMonth = date('F', mktime(0, 0, 0, $month, 10));
$currentMonthNum = $month;

$startingDate = strtotime($start_date);
$endingDate = strtotime($end_date);
             
?>

 <!-- Page Wrapper -->
            <div class="page-wrapper">

                <!-- Page Content -->
                <div class="content container-fluid">
                <?php echo $__env->make('flash-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title">Attendance</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="admin-dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Attendance</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- /Page Header -->

                    <!-- Search Filter -->
                    <form action="/emp_attendance_list" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="row filter-row">
                            
                        <div class="col-sm-6 col-md-2">  
                                <div class="form-group form-focus focused">
                                    <div class="cal-icon">
                                        <input class="form-control floating searchdatepicker" type="text" name="from_date" id="from_date" value="<?php echo (isset($search['from_date']))?$search['from_date']:'' ?>">
                                    </div>
                                    <label class="focus-label">From</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-2">  
                                <div class="form-group form-focus focused">
                                    <div class="cal-icon">
                                        <input class="form-control floating searchdatepicker" type="text" name="to_date" id="to_date" value="<?php echo (isset($search['to_date']))?$search['to_date']:''?>">
                                    </div>
                                    <label class="focus-label">To</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-2">  
                                <input type="submit" class="btn btn-success w-100" name="search" value="search"> 
                            </div>     
                        </div>
                    </form>
                    <!-- Search Filter -->
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class=" table table-bordered table-striped table-hover datatable datatable-LoanApplication">
                                    <thead>
                                        <tr>
                                            <th style="width: 30px;">Date</th>
                                            <th>Punch In</th>
                                            <th>Punch Out</th>
                                            <th>Start time</th>
                                            <th>End time</th>
                                            <th>Total hours</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <?php
                                            $is_employee_exist = is_employee_exist($user_id);
                                            for ($currentDate = $startingDate; $currentDate <= $endingDate; $currentDate += (86400)) {
                                                $date = date('Y-m-d', $currentDate);
                                                ?>
                                                <?php 
                                                    $schedule_data = _get_schedule_time_by_emp($date,$user_id);
                                                    $out_time = _get_attendance_time($date,$user_id,'clockout');
                                                    $in_time = _get_attendance_time($date,$user_id,'clockin');
                                                    $check_green_icon_attendance = _check_green_icon_attendance($date,$user_id);
                                                    $encoded = base64_encode(json_encode($date.'/'.$user_id));
                                                    ?>  
                                            <tr>
                                            <td><?php echo e(date('d-m-Y', $currentDate)); ?></td>
                                            
                                            <?php if(!empty($schedule_data)): ?>
                                                <?php if($schedule_data->shift==1): ?>
                                                   
                                                        <td colspan="6" align="center">OFF Day</td>
                                                        <td style="display:none"></td>
                                                        <td style="display:none"></td>
                                                        <td style="display:none"></td>
                                                        <td style="display:none"></td>
                                                        <td style="display:none"></td>
                                             
                                                <?php elseif($schedule_data->shift==2): ?>
                                                    
                                                        <td colspan="6" align="center">PH Day</td>
                                                        <td style="display:none"></td>
                                                        <td style="display:none"></td>
                                                        <td style="display:none"></td>
                                                        <td style="display:none"></td>
                                                        <td style="display:none"></td>
                                                <?php elseif($schedule_data->shift==3): ?>
                                                   
                                                        <td colspan="6" align="center">Free Shift</td>
                                                        <td style="display:none"></td>
                                                        <td style="display:none"></td>
                                                        <td style="display:none"></td>
                                                        <td style="display:none"></td>
                                                        <td style="display:none"></td>                    
                                                <?php elseif($schedule_data->shift==7): ?>
                                                   
                                                        <td colspan="6" align="center">AL</td>
                                                        <td style="display:none"></td>
                                                        <td style="display:none"></td>
                                                        <td style="display:none"></td>
                                                        <td style="display:none"></td>
                                                        <td style="display:none"></td>   
                                                                             
                                                <?php elseif($schedule_data->shift==8): ?>
                                                   
                                                        <td colspan="6" align="center">SL</td>
                                                        <td style="display:none"></td>
                                                        <td style="display:none"></td>
                                                        <td style="display:none"></td>
                                                        <td style="display:none"></td>
                                                        <td style="display:none"></td>
                                                                                
                                                <?php elseif($schedule_data->shift==9): ?>
                                                   
                                                        <td colspan="6" align="center">UL</td>
                                                        <td style="display:none"></td>
                                                        <td style="display:none"></td>
                                                        <td style="display:none"></td>
                                                        <td style="display:none"></td>
                                                        <td style="display:none"></td>
                                                    
                                                <?php elseif(!empty($schedule_data->min_start_time)): ?>
                                                    <?php if(empty($in_time)): ?>
                                                        <td colspan="2" align="center"><span class="text-danger">A</span></td>
                                                        <td style="display:none"></td>
                                                        <td style="display:none"></td>
                                                    <?php else: ?>
                                                        <?php if($check_green_icon_attendance == 0): ?>
                                                            <?php if(isset($in_time) && !empty($in_time)): ?>
                                                                <td><?php echo e(_convert_time_to_12hour_format_bydate($in_time)); ?></td>
                                                            <?php else: ?>
                                                                <td>NA</td>
                                                            <?php endif; ?>
                                                        
                                                            <?php if(isset($out_time) && !empty($out_time)): ?>
                                                                <td><?php echo e(_convert_time_to_12hour_format_bydate($out_time)); ?></td>
                                                            <?php else: ?>
                                                                <td>NA</td>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <td colspan="2" align="center"><i class="fa fa-info-circle text-warning"></i></td>
                                                            <td style="display:none"></td>
                                                            <td style="display:none"></td>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                    <td><?php echo e(_convert_time_to_12hour_format_bydate($schedule_data->min_start_time)); ?></td>
                                                    <td><?php echo e(_convert_time_to_12hour_format_bydate($schedule_data->min_end_time)); ?></td>
                                                    <?php if(!empty($in_time) && !empty($out_time)): ?>
                                                        <td><?php echo e(get_total_hours($in_time,$out_time)); ?></td>
                                                    <?php else: ?>
                                                        <td>NA</td>
                                                    <?php endif; ?>
                                                    <?php if(!empty($in_time) && !empty($is_employee_exist)): ?>
                                                        <td><a href="javascript:void(0);" class="popupAttn" data-id="<?php echo e($encoded); ?>"  class="action-icon"><i class="fa fa-eye"></i></a></td>
                                                    <?php else: ?>
                                                        <td></td>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                <?php endif; ?>
                                            <?php else: ?> 
                                                <?php if(empty($in_time)): ?>
                                                    <td colspan="2" align="center"><span class="text-danger">A</span></td>
                                                    <td colspan="4"></td>
                                                    <td style="display:none"></td>
                                                    <td style="display:none"></td>
                                                    <td style="display:none"></td>
                                                    <td style="display:none"></td>
                                                <?php else: ?>
                                                    <?php if($check_green_icon_attendance == 0): ?>
                                                            <?php if(isset($in_time) && !empty($in_time)): ?>
                                                                <td><?php echo e(_convert_time_to_12hour_format_bydate($in_time)); ?></td>
                                                            <?php else: ?>
                                                                <td>NA</td>
                                                            <?php endif; ?>
                                                        
                                                            <?php if(isset($out_time) && !empty($out_time)): ?>
                                                                <td><?php echo e(_convert_time_to_12hour_format_bydate($out_time)); ?></td>
                                                            <?php else: ?>
                                                                <td>NA</td>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <td colspan="2" align="center"><i class="fa fa-info-circle text-warning"></i></td>
                                                            <td colspan="3"></td>
                                                            <td style="display:none"></td>
                                                            <td style="display:none"></td>
                                                            <td style="display:none"></td>
                                                            <?php if(!empty($is_employee_exist)): ?>
                                                                <td><a href="javascript:void(0);" class="popupAttn" data-id="<?php echo e($encoded); ?>"  class="action-icon"><i class="fa fa-eye"></i></a></td>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                    
                                                <?php endif; ?>
                                                
                                            </tr>
                                            <?php
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
   
            </div>
            <!-- /Page Wrapper -->


<?php echo $__env->make('includes/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script type="text/javascript"> 
 var d = new Date();
$('.searchdatepicker').datetimepicker({
    format: 'DD-MM-YYYY',
    icons: {
        up: "fa fa-angle-up",
        down: "fa fa-angle-down",
        next: 'fa fa-angle-right',
        previous: 'fa fa-angle-left'
    },
    maxDate: new Date(d.setDate(d.getDate() + 1)) ,
});
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
            url: "<?php echo e(url('getempAttendanceDetails/')); ?>",
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
</script>
<?php /**PATH C:\wamp64_new\www\hrm\resources\views/lts/emp_attendance.blade.php ENDPATH**/ ?>