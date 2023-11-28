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
        $start = ($emloyeeAttendance[$startIndex]['attendance_time']!=='0')?date('D, jS M Y',strtotime($emloyeeAttendance[$startIndex]['attendance_on'])).' '.date('h:i A', strtotime($emloyeeAttendance[$startIndex]['attendance_time'])):'No punch In';

        $lastIndex = $count-1;
        if($punchStates[$lastIndex] == 'clockout' && $emloyeeAttendance[$lastIndex]['attendance_time']!=0)
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
        $ea_att_time = '';
        if($ea['attendance_time']!=0)
        {
            $ea_att_time = date('h:i A', strtotime($ea['attendance_time']));
        }
        $activity .= '<li>
                        <p class="mb-0">'.(($ea['punch_state']=='clockin')?'Punch In':'Punch Out').' at</p>
                        <p class="res-activity-time">
                            <i class="fa fa-clock-o"></i>
                            '.$ea_att_time.'
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
                        <div class="col-md-4 col-4 text-center">
                            <div class="stats-box">
                                <p>Break</p>
                                <h6>1 hrs</h6>
                            </div>
                        </div>
                        <div class="col-md-4 col-4 text-center">
                            <div class="stats-box">
                                <p>Overtime</p>
                                <h6><?php echo ($emloyeeAttendance[0]['overtime_hours']!='')?$emloyeeAttendance[0]['overtime_hours']:0; ?> hrs</h6>
                            </div>
                        </div>
                        <div class="col-md-4 col-4 text-center">
                            <div class="stats-box">
                                <p>Working</p>
                                <h6><?php echo ($emloyeeAttendance[0]['schedule_hours']!='')?$emloyeeAttendance[0]['schedule_hours']:0; ?> hrs</h6>
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
                    $shiftScheduleStart = '';
                    $shiftScheduleEnd = '';

                    $breaktime = 0;
                    $twt = (isset($attendanceHours['totalWorkTimeHours']))?explode('.',$attendanceHours['totalWorkTimeHours']['timevalue']):0;
                    $finalOt = 0;$totalLeverage='';
                    if(count($emloyeeSchedule) > 0)
                    { 
                        $breaktime = $emloyeeSchedule[0]['break_time'];
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

                        $shiftScheduleStart = $emloyeeSchedule[0]['start_time'];
                        $shiftScheduleEnd = $emloyeeSchedule[0]['end_time'];
                    }
                        $overtimeDetails = App\Models\Overtime::first();
                        $workingHours = $overtimeDetails->working_hours;

                        $scheduleStart = $start;
                        $scheduleEnd = $last;
                        // echo '<pre>';print_r($twt);print_r($workingHours);print_r($totalLeverage);
                        $ot = ($twt[0] > 0)?($twt[0].'.'.$twt[1])-$workingHours:0;

                        // echo 'ot-'.$ot;echo '<br>';
                        // echo 'breaktime-'.$breaktime;
                        if($ot > 0 && $breaktime > 0)
                        {
                            // date_default_timezone_set("Asia/Kolkata");
                            $ot = date('h.i', strtotime('- '.$breaktime.' minutes', strtotime($ot)));
                        }
                        $finalOt = $ot;
                        
                        // echo '<br>';print_r($ot);
                        // if($totalLeverage != '' && $ot>$totalLeverage)
                        // {
                        //     $finalOt = $totalLeverage - $ot;
                        // }
                    
                    if($finalOt < 0)
                    {
                        $finalOt = $finalOt * -1;
                    }
                    // echo $finalOt.'-'.$emloyeeAttendance[0]['ottime'].'-';
                    //if(($emloyeeAttendance[0]['ottime']=='' && $finalOt > 0) || (!in_array('clockin', $punchStates) || !in_array('clockout', $punchStates))){
                    if(isset($emloyeeAttendance) && $emloyeeAttendance[0]['ot_approve_status']!=='0' && strtotime($workingHours.'.0') !== strtotime($attendanceHours['totalWorkTimeHours']['timevalue'])) { ?>

<?php } ?>
</div>

<script type="text/javascript">
    var dateNow = new Date();
    $(".att_timepicker").datetimepicker({
        format: 'HH:mm a',
        defaultDate:moment(dateNow).hours(0).minutes(0)
    });
$(document).on('change', '#start_time, #end_time', function()
{
    var start_time = $('#start_time').val();
    var end_time = $('#end_time').val();
    var break_time_val = $('#break_time_val').val();
    var normal_wt_val = $('#normal_wt_val').val();
// console.log('start_time'+start_time);console.log('end_time'+end_time);
    if (start_time === "" || end_time === "") {
        // $("#result").text("Please enter both start and end times.");
        return;
    }

    var startDate = parseCustomDate(start_time);
    var endDate = parseCustomDate(end_time);

    var timeDiff = endDate - startDate;
    timeDiff -= (normal_wt_val * 60 * 60 * 1000) + (break_time_val * 60 * 1000);

    var hours = Math.floor(timeDiff / (1000 * 60 * 60)); // Convert milliseconds to hours
    var minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60)); // Convert milliseconds to minutes

// console.log('startDate'+startDate);console.log('endDate'+endDate);
    // console.log('hours'+hours);console.log('minutes'+minutes);
    $('#ottime').text(hours+'.'+minutes);
    $('#ottime_val').val(hours+'.'+minutes);
    // $("#result").text("Time difference: " + hours + " hours and " + minutes + " minutes.");
});

function parseCustomDate(dateString) {
    var months = {
        "Jan": 0, "Feb": 1, "Mar": 2, "Apr": 3, "May": 4, "Jun": 5,
        "Jul": 6, "Aug": 7, "Sep": 8, "Oct": 9, "Nov": 10, "Dec": 11
    };

    var parts = dateString.split(" ");
    var day = parseInt(parts[1]);
    var month = months[parts[2]];
    var year = parseInt(parts[3]);
    var timeParts = parts[4].split(":");
    var hours = parseInt(timeParts[0]);
    var minutes = parseInt(timeParts[1]);

    if (parts[5] === "PM" && hours !== 12) {
        hours += 12;
    } else if (parts[5] === "AM" && hours === 12) {
        hours = 0;
    }

    return new Date(year, month, day, hours, minutes);
}
</script>