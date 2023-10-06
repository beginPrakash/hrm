<?php
use App\Models\Employee;
use App\Models\EmployeeSalary;
use App\Models\EmployeeLoan;
use App\Models\FinancialYear;
use App\Models\Overtime;
use App\Models\AttendanceDetails;
use App\Models\Scheduling;
use App\Models\Leaves;

function getLastId()
{
    $last_inserted_id = Employee::orderBy('id', 'desc')->first()->id;//dd($last_inserted);
    $auto_id = 1000 + $last_inserted_id + 1;
    
    return $auto_id;
}
function getInformation()
{
    return 'hi';
}

function calculateSalaryByFilter($empid,$mid,$year)
{
    // date_default_timezone_set("Asia/Kuwait");
                    
    //get current financial year
    $currentFY = FinancialYear::where('status', 'active')->first();
    $currentMonth = $mid;
    $currentYear = $year;
    $totalMonthDays = date('t');
    // echo '<br>'.$currentMonth;exit;
    //get working hours
    $commonWorkingHoursDetails = Overtime::get()->first();
    $commonWorkingDays = $commonWorkingHoursDetails->working_days;
    $commonWorkingOffDay = $commonWorkingHoursDetails->off_day;
    $commonWorkingHours = $commonWorkingHoursDetails->working_hours;

    $endDateOrg = $currentYear.'-'.$currentMonth.'-'.'19';//date('Y-m-20');
    $startDateOrg =  date('Y-m-d', strtotime('-1 month', strtotime($endDateOrg)));
    $startDateOrg = date('Y-m-d', strtotime('+1 days', strtotime($startDateOrg)));
    $dateString = $startDateOrg.','.$endDateOrg;
    // echo $startDateOrg;
    // echo '<br>'.$endDateOrg;exit;

    $excludeDates = [];
    // $phDetails = Holidays::whereBetween('holiday_date', [$startDateOrg, $endDateOrg])->get()->pluck('holiday_date')->toArray();
    $ph_days_no = 0;
    // if(isset($phDetails) && count($phDetails) > 0)
    // {
    //     $ph_days_no = count($phDetails);
    //     $excludeDates = $phDetails;
    // }
    // echo '<pre>';print_r($phDetails);
    $startDate = new DateTime($startDateOrg);
    $endDate = new DateTime($endDateOrg);
    $currentDate = clone $startDate;

    $offdays = 0;
    // while ($currentDate <= $endDate) 
    // {
    //     $dayNo = $currentDate->format('N');
    //     if($dayNo == $commonWorkingOffDay)
    //     {
    //         $offdays++;
    //         $excludeDates[count($excludeDates)+1] = $currentDate->format('Y-m-d');
    //     }
    //     // echo '<br>'.$currentDate->format('N D') . PHP_EOL;
    //     $currentDate->modify('+1 day');
    // }
    // echo $offdays;
    $emp = Employee::with('employee_salary')->where('status', 'active')->where('user_id',$empid)->first();
    // echo '<pre>';print_r($endDate);exit;
    
    if(!empty($emp))
    {
            // echo $emp->first_name;exit();
            $i = 0;$offtookdays = 0;
            $currentDateLoop = clone $startDate;
            $timeDiff = [];
            $timeDiffWork = [];
            
            $mCount = 0;//echo $endDate;exit;
            while($currentDateLoop <= $endDate) 
            {
                if($mCount<31)
                {
                    $date = $currentDateLoop->format('Y-m-d');
                    $currentDateLoop->modify('+1 day');
                    if(count($excludeDates) > 0 && in_array($date, $excludeDates))
                    {
                        continue;
                    } 
                    // echo $date.'<br>';//exit;
                    $firstclockin = AttendanceDetails::where('user_id', $emp->user_id)->where('punch_state', 'clockin')->whereDate('attendance_on', $date)->first();
                    $lastclockout = AttendanceDetails::where('user_id', $emp->user_id)->where('punch_state', 'clockout')->whereDate('attendance_on', $date)->limit(1)->orderBy('id', 'desc')->first();
                    // echo '<pre>';print_r($firstclockin);exit;
                    if(!empty($firstclockin) && !empty($lastclockout))
                    {//echo 'here';
                        // $totaltime = getTimeDiff($firstclockin->attendance_time, $lastclockout->attendance_time);
                        $breakTimes = Scheduling::join('attendance_details as a', 'scheduling.shift_on', '=', 'a.attendance_on')
                                    ->where('a.user_id', $emp->user_id)
                                    ->whereDate('attendance_on', $date)
                                    ->select('scheduling.break_time')
                                    ->first();
                        $breakTimes = isset($breakTimes ->break_time)?$breakTimes ->break_time:0;
                        $totaltime = getTimeDiff($firstclockin->attendance_time, $lastclockout->attendance_time);
                        // Split the start time into hours and minutes
                        list($hours, $minutes) = explode(':', $totaltime);

                        // Convert hours and minutes to total minutes
                        $totalStartMinutes = ($hours * 60) + $minutes;

                        // Subtract minutes from the total minutes
                        $newTotalMinutes = (int)$totalStartMinutes - (int)$breakTimes;

                        // Convert total minutes back to hours and minutes
                        $newHours = floor($newTotalMinutes / 60);
                        $newMinutes = $newTotalMinutes % 60;

                        // Format the result as "H:i" time format
                        $totaltime = sprintf("%02d:%02d", $newHours, $newMinutes);
                        if( $totaltime <= "00:00"){
                            $totaltime ="00:00";
                        }
                        $timeDiff[$i] = $totaltime;
                        $timeDiffWork[$i] = $totaltime;
                        $timeDiff[$i] = $totaltime;
                        $timeDiffWork[$i] = $totaltime;
                        if(strtotime($totaltime) > strtotime($commonWorkingHours.':00:00'))
                        { 
                            $diff = date('H:i:s', strtotime($totaltime. ' -'.$commonWorkingHours.' hours')); 
                            $timeDiffWork[$i] = $commonWorkingHours.":00";
                        }
                    }
                    else
                    {//echo 'no here';
                        $offtookdays++;
                    }
                    $i++;
                    $mCount+1;
                }
                
            }
            // echo $offtookdays;
            
            // echo '<pre>';print_r($timeDiffWork);//exit;
            //calculate salary now
            $currentMonthSalary = (isset($emp->employee_salary))?$emp->employee_salary->basic_salary:0;
            $daySalary = ($currentMonthSalary>0)?($currentMonthSalary/$commonWorkingDays):0;
            $hourlySalary = ($daySalary>0)?($daySalary/$commonWorkingHours):0;
            //sum timediff to get total hours
            if($timeDiffWork > 0)
            {
                $totalWorkinghours = addTimeDiff($timeDiffWork);
            }
            // echo '$totalWorkinghours-'.$totalWorkinghours;
            //reduce break minutes
            $shiftDetails = Scheduling::where('employee', $emp->user_id)->where('shift_on', $date)->where('status', 'active')->first();
            if(!empty($shiftDetails))
            {
                if($shiftDetails->break_time !==null)
                {
                    $break_time = $shiftDetails->break_time;
                    $totalWorkinghours = date('h:i', strtotime('- '.$break_time.' minutes', strtotime($totalWorkinghours)));
                }
            }

            //check no of offs
            

            $commonOffs = 30 - $commonWorkingDays;
            // echo $commonOffs.'<br>';
            // echo $offtookdays.'<br>';
            if($offtookdays > $commonOffs)
            {//echo 'here';
                $offDiff = $offtookdays - $commonOffs;
                $totalWorkDays = $commonWorkingDays - $offDiff;
            }
            else
            {//echo 'no here';
                //<= normal offs
                $totalWorkDays = $commonWorkingDays;
            }
            $totalWorkinghours = ($totalWorkDays * $commonWorkingHours).':00';
            // echo $totalWorkinghours.'<br>';
            // echo '$totalWorkinghours-'.$totalWorkinghours;exit;
            //calculate hourly salary
            //Split up string into hours/minutes
            list($h, $m) = explode(':',$totalWorkinghours);
            $decimal = $m/60;  //get minutes as decimal
            $hoursAsDecimal = $h+$decimal;
            $totalSalary=$hoursAsDecimal*$hourlySalary;
            // $totalSalary=$hoursAsDecimal*$hourlySalary;
            // echo $hoursAsDecimal.'<br>';echo $hourlySalary.'<br>';echo $totalSalary;exit;
            // selvan 
            $leaveDeduction = leaveSalaryCalculate($emp->user_id,$currentMonth,$daySalary,$totalSalary);
            //loan deduction
            $loanDeduction = 0;
            $loanDetails = EmployeeLoan::where('emp_id', $emp->user_id)->where('status', 'active')->first();
            $loanDeductionRNo = 0;
            if(!empty($loanDetails))
            {
                $loanDeduction = $loanDetails->install_amount;
                $loanDeductionRNo = $loanDetails->id;
            }

            //salary after deduction
            $finalSalary = $totalSalary - $loanDeduction;
            $insertArray = array(
                'salary'           =>   $totalSalary,
                'deductions'       =>   $loanDeduction,
                'total_salary'     =>   round($finalSalary,2),
                'total_work_hours' =>   $totalWorkinghours,
            );
            return $insertArray;
    }
}

function leaveSalaryCalculate($userId,$month,$daySalary,$totalSalary)
    {

        $netSalary = $totalSalary;

        $totalPaidSickLeaves = 15 ;
        $totalHalfPaidSickLeaves =30 ;
        $totalLessPaidSickLeaves = 45;
        $totalUnPaidSickLeaves = $totalLessPaidSickLeaves+1 ;
        $leaveType = 2 ; // SICK LEAVE
        $letoDate = date('Y')."-12-20";
        $lefromDate = date("Y-m-d", strtotime("-12 month", strtotime($letoDate)));

        $letothisDate = date('Y')."-$month-20";
        $lefromthisDate = date("Y-m-d", strtotime("-1 month", strtotime($letothisDate)));
        
        $sickLeaves = Leaves::where('leave_type', $leaveType)
                    ->where('leave_from', '>=', $lefromDate)
                    ->where('leave_to', '<=', $letoDate)
                    ->where('user_id', $userId)
                    ->sum('leave_days');

        $sickLeavesonMonth = Leaves::where('leave_type', $leaveType)
                    ->where('leave_from', '>=', $lefromthisDate)
                    ->where('leave_to', '<=', $letothisDate)
                    ->where('user_id', $userId)
                    ->sum('leave_days');
        // echo $letothisDate;
        if(!empty($sickLeaves) && !empty($sickLeavesonMonth)){
            $totalSickLeaves = 16;
            $sickLeavesonMonth = (int)$sickLeavesonMonth;
            if($totalPaidSickLeaves < $totalSickLeaves && $totalHalfPaidSickLeaves >=  $totalSickLeaves){
                
                // Calculate the deduction amount
                $deductionAmount = ($daySalary * 50) / 100;
                $totalDeductionAmount = $sickLeavesonMonth * $deductionAmount;
                // Calculate the net salary
                $netSalary = $totalSalary - $totalDeductionAmount;
                // print_r($totalSalary);

            }else if( $totalHalfPaidSickLeaves <  $totalSickLeaves && $totalLessPaidSickLeaves >= $totalSickLeaves){
                
                // Calculate the deduction amount
                $deductionAmount = ($daySalary * 75) / 100;

                $totalDeductionAmount = $sickLeavesonMonth * $deductionAmount;
                // Calculate the net salary
                $netSalary = $totalSalary - $totalDeductionAmount;

            }else if( $totalLessPaidSickLeaves <  $totalSickLeaves){

                $totalDeductionAmount = $sickLeavesonMonth * $daySalary;
                // Calculate the net salary
                $netSalary = $totalSalary - $totalDeductionAmount;
            }else{
                $netSalary = $totalSalary;
            }
        }

        return $netSalary;
    }
