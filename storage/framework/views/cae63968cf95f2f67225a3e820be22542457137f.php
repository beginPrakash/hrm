<?php
$activity = '';
$start = ''; $last = '';

if(isset($emloyeeAttendance))
{
    $count = count($emloyeeAttendance);
    // echo '<pre>';print_r($emloyeeAttendance);
    $punchStates = array_column($emloyeeAttendance, 'punch_state');
    // echo '<pre>';print_r($attendanceHours);

    if(isset($punchStates))
    {
        $startIndex = array_search('clockin', $punchStates);
        $start = date('D, jS M Y',strtotime($emloyeeAttendance[$startIndex]['attendance_on'])).' '.date('h:i A', strtotime($emloyeeAttendance[$startIndex]['attendance_time']));

        $lastIndex = $count-1;
        if($punchStates[$lastIndex] == 'clockout')
        {
            $last = date('D, jS M Y',strtotime($emloyeeAttendance[$lastIndex]['attendance_on'])).' '.date('h:i A', strtotime($emloyeeAttendance[$lastIndex]['attendance_time']));
        }
        else
        {
            $last = 'Not yet Logged out';
        } 
    }
    foreach($emloyeeAttendance as $ea)
    {
        $activity .= '<li>
                        <p class="mb-0">'.(($ea['punch_state']=='clockin')?'Punch In':'Punch Out').' at</p>
                        <p class="res-activity-time">
                            <i class="fa fa-clock-o"></i>
                            '.date('h:i A', strtotime($ea['attendance_time'])).'.
                        </p>
                    </li>';
    }
}
?>
<div class="row">
    <div class="col-md-6">
        <div class="card punch-status">
            <div class="card-body">
                <h5 class="card-title">Timesheet <small class="text-muted"><?php echo date('d F, Y',strtotime($attnDate)); ?> </small></h5>
                <div class="punch-det">
                    <h6>Punch In at</h6>
                    <p><?php echo $start; ?></p>
                </div>
                <div class="punch-info">
                    <div class="punch-hours text-center">
                        <span><?php echo (isset($attendanceHours['totalWorkTimeHours']))?$attendanceHours['totalWorkTimeHours']['timetext']:'0hrs'; ?></span>
                    </div>
                </div>
                <div class="punch-det">
                    <h6>Punch Out at</h6>
                    <p><?php echo $last; ?></p>
                </div>
                <div class="statistics">
                    <div class="row">
                        <div class="col-md-6 col-6 text-center">
                            <div class="stats-box">
                                <p>Break</p>
                                <h6><?php echo (isset($attendanceHours['totalBreakTimeHours']))?$attendanceHours['totalBreakTimeHours']['timetext']:'0hrs'; ?></h6>
                            </div>
                        </div>
                        <div class="col-md-6 col-6 text-center">
                            <div class="stats-box">
                                <p>Overtime</p>
                                <h6><?php echo ($emloyeeAttendance[0]['ottime']!='')?$emloyeeAttendance[0]['ottime']:0; ?> hrs</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card recent-activity">
            <div class="card-body">
                <h5 class="card-title">Activity</h5>
                <ul class="res-activity-list">
                    <?php echo $activity; ?>
                </ul>
            </div>
        </div>
    </div>
    <?php //if($emloyeeAttendance[0]['ottime']=='' || $emloyeeAttendance[0]['ottime']==NULL) { ?>
    <div class="col-md-12">
        <!-- <button class="btn btn-info pull-right mb-3 text-white" type="button" <?php echo (count($emloyeeSchedule)==0)?'disabled':''; ?>>Edit Attendance</button>
        <br>
        <small class="text-danger pull-right"><?php echo (count($emloyeeSchedule)==0)?"**Can\'t edit as employee doesn\'t have a shift":''; ?></small> -->
    </div>
    <?php //echo '<pre>';print_r($emloyeeSchedule);

                    $twt = (isset($attendanceHours['totalWorkTimeHours']))?explode('.',$attendanceHours['totalWorkTimeHours']['timevalue']):0;
                    $finalOt = 0;$totalLeverage='';
                    if(count($emloyeeSchedule) > 0)
                    { 
                        $scheduleMinStart = $emloyeeSchedule[0]['min_start_time'];
                        $scheduleMaxStart = $emloyeeSchedule[0]['max_start_time'];
                        $sh_interval_start =getTimeDiff($scheduleMinStart, $scheduleMaxStart);

                        $scheduleMinEnd = $emloyeeSchedule[0]['min_end_time'];
                        $scheduleMaxEnd = $emloyeeSchedule[0]['max_end_time'];
                        $sh_interval_end =getTimeDiff($scheduleMinEnd, $scheduleMaxEnd);
// echo $sh_interval_start.'-'.$sh_interval_end;
                        $ex_sh_interval_start = explode(':', $sh_interval_start);
                        $ex_sh_interval_end = explode(':', $sh_interval_end);
                        
                        $totalLeverage = ($ex_sh_interval_start[0]+$ex_sh_interval_end[0]).'.'.($ex_sh_interval_start[1]+$ex_sh_interval_end[1]);//strtotime($sh_interval_start) + strtotime($sh_interval_end);
                        // echo $totalLeverage.'<br>';//echo date('h:i', $totalLeverage);
//                         $time1 = explode(':',$emloyeeSchedule[0]['end_time']);
//                         $time2 = explode(':',$emloyeeSchedule[0]['start_time']);
//                         $totalWorkTime = $time2[0] - $time1[0];
// echo $totalWorkTime;
// echo '-'.$totalWorkTime;
//                         if ($totalWorkTime < 0)
//                         {
//                             $totalWorkTime = $totalWorkTime*-1;
//                         }

//                         $ot = ($twt > 0)?($twt[0].'.'.$twt[1])-$totalWorkTime:0;
                        
//                     }
//                     else
//                     {
                    }
                        $overtimeDetails = App\Models\Overtime::first();
                        $workingHours = $overtimeDetails->working_hours;

                        $scheduleStart = $start;
                        $scheduleEnd = $last;
                        //echo '<pre>';print_r($twt);print_r($workingHours);print_r($totalLeverage);
                        $ot = ($twt > 0)?($twt[0].'.'.$twt[1])-$workingHours:0;
                        //echo '<br>';print_r($ot);
                        if($totalLeverage != '' && $ot>$totalLeverage)
                        {
                            $finalOt = $totalLeverage - $ot;
                        }
                    
                    if($finalOt < 0)
                    {
                        $finalOt = $finalOt * -1;
                    }
                    // echo $finalOt.'-'.$emloyeeAttendance[0]['ottime'].'-';
                    if($emloyeeAttendance[0]['ottime']=='' && $finalOt > 0) { ?>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="" method="post" id="otapprove">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="attnUserId" id="attnUserId" value="<?php echo $userId; ?>">
                    <input type="hidden" name="attnDate" id="attnDate" value="<?php echo $attnDate; ?>">
                    
                    Scheduled Shift <br>
                        Start Time : <input type="text" class="form-control" name="start_time" id="start_time" value="<?php echo $scheduleStart; ?>"><br>
                        End Time : <input type="text" class="form-control" name="end_time" id="end_time" value="<?php echo $scheduleEnd; ?>">
                    <br>
                    Worked Hours : <span><?php echo (isset($attendanceHours['totalWorkTimeHours']))?$attendanceHours['totalWorkTimeHours']['timetext']:'0hrs'; ?></span><br>
                    Over Time : <span id="ottime"><?php echo ($emloyeeAttendance[0]['ottime']!='')?$emloyeeAttendance[0]['ottime']:$finalOt; ?><?php //echo $ot; ?></span>hrs<br>
                    <input type="hidden" class="form-controlx" name="approve_remark" id="approve_remark" value="1">
                    Remarks : <textarea name="remarks" class="form-control"></textarea><br>
                    <button class="btn btn-info pull-right mb-3 text-white" type="button" onclick="saveOtApprove()">Update</button>

                </form>
            </div>
        </div>
    </div>
<?php } ?>
</div>
<?php /**PATH C:\xampp81\htdocs\hrmumair\resources\views/lts/attendancePopup.blade.php ENDPATH**/ ?>