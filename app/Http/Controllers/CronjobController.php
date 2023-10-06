<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Session;
use DB;
use DateTime;

use App\Models\Employee;
use App\Models\EmployeeSalary;
use App\Models\EmployeeLoan;
use App\Models\FinancialYear;
use App\Models\AttendanceDetails;
use App\Models\EmployeeMonthlySalary;
use App\Models\EmployeeSalaryHistory;
use App\Models\Holidays;
use App\Models\PayrollSettings;
use App\Models\Overtime;
use App\Models\Scheduling;
use App\Models\Leaves;

class CronjobController extends Controller
{
    public function __construct()
    {
        $this->title = 'Cronjobs';
    }

    /*******************************Salary Calculation*******************************/
    public function salaryEntry(Request $request)
    {
        // date_default_timezone_set("Asia/Kuwait");
                        
        //get current financial year
        $currentFY = FinancialYear::where('status', 'active')->first();
        $currentMonth = $request->mid;
        $currentYear = date('Y');
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
        $employees = Employee::with('employee_salary')->where('status', 'active')->get();
        // echo '<pre>';print_r($endDate);exit;
        
        if(isset($employees))
        {
            foreach($employees as $emp)
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
                $leaveDeduction = $this->leaveSalaryCalculate($emp->user_id,$currentMonth,$daySalary,$totalSalary);
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
                    'residency_id'     =>   $emp->company,
                    'branch_id'        =>   $emp->branch,
                    'financial_year'   =>   $currentFY->id,
                    'emp_id'           =>   $emp->user_id,
                    'es_month'         =>   $currentMonth,
                    'es_year'          =>   $currentYear,
                    'month_w_days'     =>   $commonWorkingDays,
                    'month_holidays'   =>   $ph_days_no,
                    'month_fridays'    =>   $offdays,
                    'month_salary'     =>   $currentMonthSalary,
                    'day_salary'       =>   $daySalary,
                    'hourly_salary'    =>   $hourlySalary,
                    'off_day'          =>   $commonWorkingOffDay,
                    'off_days_no'      =>   $offtookdays,
                    'ph_days_no'       =>   $ph_days_no,
                    'ph_dates'         =>   (isset($phDetails) && count($phDetails) > 0)?implode(',',$phDetails):NULL,
                    'day_hours'        =>   $commonWorkingHours,
                    'dates_between'    =>   $dateString,
                    'excluded_dates'   =>   (isset($excludeDates) && count($excludeDates) > 0)?implode(',',$excludeDates):NULL,
                    'salary_type'      =>   'salary',
                    'salary'           =>   $totalSalary,
                    'deductions'       =>   $loanDeduction,
                    'additions'        =>   0,
                    'overtime'         =>   0,
                    'total_salary'     =>   round($finalSalary,2),
                    'total_work_hours' =>   $totalWorkinghours,
                    'total_overtime_salary'     =>   0,//round($totalOvertimeSalary,2),
                    'total_work_overtime'       =>   0,//$totalOvertimehours,
                    'created_at'       =>   date('Y-m-d h:i:s'),
                    'status'           =>   'active'
                );
                // echo '<pre>';print_r($insertArray);exit;
                $emsId = EmployeeMonthlySalary::create($insertArray)->id;
                if($emsId && $loanDeduction > 0)
                {
                    //insert into salary history table
                    $insertESHArray = array(
                        'ems_id'        =>  $emsId,
                        'user_id'       =>  $emp->user_id,
                        'entry_type'    =>  'deduction',
                        'entry_value'   =>  $loanDeduction,
                        'entry_type_title' => 'loan',
                        'remarks'       =>  'Loan Deduction, Reference No: '.$loanDeductionRNo,
                        'created_at'    =>  date('Y-m-d h:i:s'),
                        'status'        =>  'active');
                    EmployeeSalaryHistory::create($insertESHArray)->id;
                }
            }
        }
    }

    public function leaveSalaryCalculate($userId,$month,$daySalary,$totalSalary)
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

    /*******************************Overtime Calculation*******************************/
    public function overtimeEntry(Request $request)
    {
        // date_default_timezone_set("Asia/Kuwait");
                        
        //get current financial year
        $currentFY = FinancialYear::where('status', 'active')->first();
        $currentMonth = $request->mid;
        $currentYear = date('Y');
        $totalMonthDays = date('t');

        //get working hours
        $commonWorkingHoursDetails = Overtime::get()->first();
        $commonWorkingDays = $commonWorkingHoursDetails->working_days;
        $commonWorkingOffDay = $commonWorkingHoursDetails->off_day;
        $commonWorkingHours = $commonWorkingHoursDetails->working_hours;

        $endDateOrg = $currentYear.'-'.$currentMonth.'-'.'10';//date('Y-m-10');
        $startDateOrg =  date('Y-m-d', strtotime('-1 month', strtotime($endDateOrg)));
        $startDateOrg = date('Y-m-d', strtotime('-1 days', strtotime($startDateOrg)));
        $dateString = $startDateOrg.','.$endDateOrg;
        // echo $endDateOrg;
        // echo '<br>'.$startDateOrg;

        $excludeDates = [];
        $phDetails = Holidays::whereBetween('holiday_date', [$startDateOrg, $endDateOrg])->get()->pluck('holiday_date')->toArray();
        $ph_days_no = 0;
        if(isset($phDetails) && count($phDetails) > 0)
        {
            $ph_days_no = count($phDetails);
            $excludeDates = $phDetails;
        }
        // echo '<pre>';print_r($phDetails);
        $startDate = new DateTime($startDateOrg);
        $endDate = new DateTime($endDateOrg);
        $currentDate = clone $startDate;

        $offdays = 0;
        while ($currentDate <= $endDate) 
        {
            $dayNo = $currentDate->format('N');
            if($dayNo == $commonWorkingOffDay)
            {
                $offdays++;
                $excludeDates[count($excludeDates)+1] = $currentDate->format('Y-m-d');
            }
            // echo '<br>'.$currentDate->format('N D') . PHP_EOL;
            $currentDate->modify('+1 day');
        }
        // echo $offdays;
        // echo '<pre>';print_r($excludeDates);
        $employees = Employee::with('employee_salary')->where('status', 'active')->get();
        
        if(isset($employees))
        {
            foreach($employees as $emp)
            {
                echo '/.................User: '.$emp->user_id.'................/';
                echo '<br>';
                $i = 0;
                $totaltime = '';
                $currentDateLoop = clone $startDate;
                $timeDiff = [];
                $timeDiffWork = [];
                while($currentDateLoop <= $endDate) 
                {
                    $date = $currentDateLoop->format('Y-m-d');
                    $currentDateLoop->modify('+1 day');
                    echo ' Date: '.$date;
                    $firstclockin = AttendanceDetails::where('user_id', $emp->user_id)->where('punch_state', 'clockin')->whereDate('attendance_on', $date)->first();
                    $lastclockout = AttendanceDetails::where('user_id', $emp->user_id)->where('punch_state', 'clockout')->whereDate('attendance_on', $date)->limit(1)->orderBy('id', 'desc')->first();
                    echo ' Clockin : '; echo ($firstclockin!=null && $lastclockout!=null)?$firstclockin->ot_approve_status:0; 
                    
                    if($firstclockin!=null && $lastclockout!=null && $firstclockin->ot_approve_status=='1' && $lastclockout->ot_approve_status=='1')
                    { echo '-'.$emp->user_id.'-'.$date;
                        //on ph and off days
                        if(count($excludeDates) > 0 && in_array($date, $excludeDates))
                        {echo 'excluded';
                            //consider this hours too as it is approved
                            $totaltime = getTimeDiff($firstclockin->attendance_time, $lastclockout->attendance_time);
                            $timeDiff[$i] = $totaltime;
                            $timeDiffWork[$i] = $totaltime;
                            $i++;
                            continue;
                        }

                        //on normal days
                        $totaltime = getTimeDiff($firstclockin->attendance_time, $lastclockout->attendance_time);
                        $timeDiff[$i] = $totaltime;
                        $timeDiffWork[$i] = $totaltime;
                        if(strtotime($totaltime) >= strtotime($commonWorkingHours.':00:00'))
                        { 
                            $diff = date('H:i:s', strtotime($totaltime. ' -'.$commonWorkingHours.' hours')); //this minuses time from time
                            $timeDiffWork[$i] = $diff;
                        }
                        
                    }
                    echo '<br>';    
                    $i++;
                }
                echo '<pre>';print_r($timeDiffWork);
                //calculate salary now
                $currentMonthSalary = (isset($emp->employee_salary))?$emp->employee_salary->basic_salary:0;
                $daySalary = ($currentMonthSalary>0)?($currentMonthSalary/$commonWorkingDays):0;
                $hourlySalary = ($daySalary>0)?($daySalary/8):0;
                //sum timediff to get total hours
                if($timeDiffWork > 0)
                {
                    $totalWorkinghours = addTimeDiff($timeDiffWork);
                }
                //calculate hourly salary
                list($h, $m) = explode(':',$totalWorkinghours);  //Split up string into hours/minutes
                $decimal = $m/60;  //get minutes as decimal
                $hoursAsDecimal = $h+$decimal;
                $totalOvertime=$hoursAsDecimal*$hourlySalary;
                echo $emp->user_id.'-'.$totalOvertime;

                echo '<br>';   
                $insertArray = array(
                    'residency_id'     =>   $emp->company,
                    'branch_id'        =>   $emp->branch,
                    'financial_year'   =>   $currentFY->id,
                    'emp_id'           =>   $emp->user_id,
                    'es_month'         =>   $currentMonth,
                    'es_year'          =>   $currentYear,
                    'month_w_days'     =>   $commonWorkingDays,
                    'month_holidays'   =>   $ph_days_no,
                    'month_fridays'    =>   $offdays,
                    'month_salary'     =>   $currentMonthSalary,
                    'day_salary'       =>   $daySalary,
                    'hourly_salary'    =>   $hourlySalary,
                    'off_day'          =>   $commonWorkingOffDay,
                    'off_days_no'      =>   $offdays,
                    'ph_days_no'       =>   $ph_days_no,
                    'ph_dates'         =>   (isset($phDetails) && count($phDetails) > 0)?implode(',',$phDetails):NULL,
                    'day_hours'        =>   $commonWorkingHours,
                    'dates_between'    =>   $dateString,
                    'excluded_dates'   =>   (isset($excludeDates) && count($excludeDates) > 0)?implode(',',$excludeDates):NULL,
                    'salary_type'      =>   'overtime',
                    'salary'           =>   0,
                    'deductions'       =>   0,
                    'additions'        =>   0,
                    'overtime'         =>   $totalOvertime,
                    'total_salary'     =>   0,
                    'total_work_hours' =>   0,
                    'total_overtime_salary'     =>   round($totalOvertime,2),
                    'total_work_overtime'       =>   $hoursAsDecimal,
                    'created_at'       =>   date('Y-m-d h:i:s'),
                    'status'           =>   'active'
                );//echo '<pre>';print_r($insertArray);exit;
                $emsId = EmployeeMonthlySalary::insertGetId($insertArray);
            }
        }
    }

    // //step 1 - insert basic details for salary
    // public function salaryEntryOld(Request $request)
    // {
    //     //get current financial year
    //     $currentFY = FinancialYear::where('status', 'active')->first();
    //     $currentMonth = $request->mid;//date('m');
    //     $currentYear = date('Y');
    //     $totalMonthDays = date('t');

    //     //get working hours
    //     $commonWorkingHoursDetails = Overtime::get();
    //     $commonWorkingHours = $commonWorkingHoursDetails[0]->working_hours;
    //     //get no of holidays of current month
    //     $holidaysDetails = Holidays::whereYear('holiday_date', '=', $currentYear)
    //           ->whereMonth('holiday_date', '=', $currentMonth)
    //           ->get();
    //     $noOfHolidays = (isset($holidaysDetails))?count($holidaysDetails):0;

    //     //get no of fridays of current month
    //     $noOfFridays = noOfFridays($currentMonth, $currentYear);
        
    //     $month_w_days = $totalMonthDays - ($noOfHolidays + $noOfFridays);

    //     $employees = Employee::with('employee_salary')->where('status', 'active')->get();
    //     if(isset($employees))
    //     {
    //         foreach($employees as $emp)
    //         {
    //             $currentMonthSalary = (isset($emp->employee_salary))?$emp->employee_salary->basic_salary:0;
    //             $daySalary = ($currentMonthSalary>0)?($currentMonthSalary/$month_w_days):0;
    //             $hourlySalary = ($daySalary>0)?($daySalary/8):0;
    //             /******Deductions******/
    //             //step - 1: loan
    //             $loanDeduction = 0;
    //             $loanDetails = EmployeeLoan::where('emp_id', $emp->user_id)->where('status', 'active')->first();
    //             $loanDeductionRNo = 0;
    //             if(!empty($loanDetails))
    //             {
    //                 $loanDeduction = $loanDetails->install_amount;
    //                 $loanDeductionRNo = $loanDetails->id;
    //             }

    //             //step - 2:salary
    //             $timeDiff = array();
    //             $timeDiffWork = array();
    //             $timeDiffOvertime = array();

    //             $prevMonth = date('m', strtotime(date('Y-'.$currentMonth)." -1 month"));
    //             $startDate = strtotime($currentYear."-".$prevMonth."-20");
    //             $endDate = strtotime($currentYear."-".$currentMonth."-20");
    //             for ( $i = $startDate; $i <= $endDate; $i = $i + 86400 ) 
    //             {
    //                 $date = date( 'Y-m-d', $i ); 
    //             // for($i=1; $i<=$totalMonthDays; $i++)
    //             // {
    //                 // $date = $currentYear."-".$currentMonth."-".$i;
    //                 $firstclockin = AttendanceDetails::where('user_id', $emp->user_id)->where('punch_state', 'clockin')->whereDate('attendance_on', $date)->first();
    //                 $lastclockout = AttendanceDetails::where('user_id', $emp->user_id)->where('punch_state', 'clockout')->whereDate('attendance_on', $date)->limit(1)->orderBy('id', 'desc')->first();

    //                 date_default_timezone_set("Asia/Kuwait");
    //                 if($firstclockin!=null && $lastclockout!=null)
    //                 {
    //                     $totaltime = getTimeDiff($firstclockin->attendance_time, $lastclockout->attendance_time);
    //                     $timeDiff[$i] = $totaltime;
    //                     $timeDiffWork[$i] = $totaltime;
    //                     $timeDiffOvertime[$i] = 0;
    //                     if(strtotime($totaltime) > strtotime($commonWorkingHours.':00:00'))
    //                     { 
    //                         $diff = date('H:i:s', strtotime($totaltime. ' -'.$commonWorkingHours.' hours')); 
    //                         $timeDiffWork[$i] = $commonWorkingHours.":00";
    //                         $timeDiffOvertime[$i] = $diff;
    //                     }
    //                 }
    //             }//echo '<pre>';print_r($timeDiff);echo '<pre>';print_r($timeDiffWork);echo '<pre>';print_r($timeDiffOvertime);
    //             //sum timediff to get total hours
    //             if($timeDiffWork > 0)
    //             {
    //                 $totalWorkinghours = addTimeDiff($timeDiffWork);
    //                 $totalOvertimehours = addTimeDiff($timeDiffOvertime);
    //             }
    //             //calculate hourly salary
    //             list($h, $m) = explode(':',$totalWorkinghours);  //Split up string into hours/minutes
    //             $decimal = $m/60;  //get minutes as decimal
    //             $hoursAsDecimal = $h+$decimal;
    //             $totalSalary=$hoursAsDecimal*$hourlySalary;

    //             //calculate hourly salary for overtime
    //             list($oh, $om) = explode(':',$totalOvertimehours);  //Split up string into hours/minutes
    //             $odecimal = $om/60;  //get minutes as decimal
    //             $ohoursAsDecimal = $oh+$odecimal;
    //             $totalOvertimeSalary=$ohoursAsDecimal*$hourlySalary;

    //             //salary after deduction
    //             $finalSalary = $totalSalary - $loanDeduction;
    //             // echo '<pre>';print_r($deductionDetails);
    //             $insertArray = array(
    //                 'residency_id'     =>   $emp->company,
    //                 'branch_id'        =>   $emp->branch,
    //                 'financial_year'   =>   $currentFY->id,
    //                 'emp_id'           =>   $emp->user_id,
    //                 'es_month'         =>   $currentMonth,
    //                 'es_year'          =>   $currentYear,
    //                 'month_w_days'     =>   $month_w_days,
    //                 'month_holidays'   =>   $noOfHolidays,
    //                 'month_fridays'    =>   $noOfFridays,
    //                 'month_salary'     =>   $currentMonthSalary,
    //                 'day_salary'       =>   $daySalary,
    //                 'hourly_salary'    =>   $hourlySalary,
    //                 'salary_type'      =>   'salary',
    //                 'salary'           =>   $totalSalary,
    //                 'deductions'       =>   $loanDeduction,
    //                 'additions'        =>   0,
    //                 'overtime'         =>   0,
    //                 'total_salary'     =>   round($finalSalary,2),
    //                 'total_work_hours' =>   $totalWorkinghours,
    //                 'total_overtime_salary'     =>   0,//round($totalOvertimeSalary,2),
    //                 'total_work_overtime'       =>   0,//$totalOvertimehours,
    //                 'created_at'       =>   date('Y-m-d h:i:s'),
    //                 'status'           =>   'active'
    //             );//echo '<pre>';print_r($insertArray);exit;
    //             $emsId = EmployeeMonthlySalary::insertGetId($insertArray);
    //             if($emsId && $loanDeduction > 0)
    //             {
    //                 //insert into salary history table
    //                 $insertESHArray = array(
    //                     'ems_id'        =>  $emsId,
    //                     'user_id'       =>  $emp->user_id,
    //                     'entry_type'    =>  'deduction',
    //                     'entry_value'   =>  $loanDeduction,
    //                     'entry_type_title' => 'loan',
    //                     'remarks'       =>  'Loan Deduction, Reference No: '.$loanDeductionRNo,
    //                     'created_at'    =>  date('Y-m-d h:i:s'),
    //                     'status'        =>  'active');
    //                 EmployeeSalaryHistory::insert($insertESHArray);
    //             }
    //             //exit;
    //         }
    //     }
        
    //     return true;
    // }

    // public function overtimeEntry(Request $request)
    // {
    //     //get current financial year
    //     $currentFY = FinancialYear::where('status', 'active')->first();
    //     $currentMonth = $request->mid;//date('m');
    //     $currentYear = date('Y');
    //     $totalMonthDays = date('t');

    //     //get working hours
    //     $commonWorkingHoursDetails = Overtime::get();
    //     $commonWorkingHours = $commonWorkingHoursDetails[0]->working_hours;
    //     //get no of holidays of current month
    //     $holidaysDetails = Holidays::whereYear('holiday_date', '=', $currentYear)
    //           ->whereMonth('holiday_date', '=', $currentMonth)
    //           ->get();
    //     $noOfHolidays = (isset($holidaysDetails))?count($holidaysDetails):0;

    //     //get no of fridays of current month
    //     $noOfFridays = noOfFridays($currentMonth, $currentYear);
        
    //     $month_w_days = $totalMonthDays - ($noOfHolidays + $noOfFridays);

    //     $employees = Employee::with('employee_salary')->where('status', 'active')->get();
    //     if(isset($employees))
    //     {
    //         foreach($employees as $emp)
    //         {
    //             $currentMonthSalary = (isset($emp->employee_salary))?$emp->employee_salary->basic_salary:0;
    //             $daySalary = ($currentMonthSalary>0)?($currentMonthSalary/$month_w_days):0;
    //             $hourlySalary = ($daySalary>0)?($daySalary/8):0;
    //             /******Deductions******/
               

    //             //step - 1:overtime
    //             $timeDiff = array();
    //             $timeDiffWork = array();
    //             $timeDiffOvertime = array();
    //             for($i=1; $i<=$totalMonthDays; $i++)
    //             {
    //                 $date = $currentYear."-".$currentMonth."-".$i;
    //                 $firstclockin = AttendanceDetails::where('user_id', $emp->user_id)->where('punch_state', 'clockin')->whereDate('attendance_on', $date)->first();
    //                 $lastclockout = AttendanceDetails::where('user_id', $emp->user_id)->where('punch_state', 'clockout')->whereDate('attendance_on', $date)->limit(1)->orderBy('id', 'desc')->first();

    //                 date_default_timezone_set("Asia/Kuwait");
    //                 if($firstclockin!=null && $lastclockout!=null)
    //                 {
    //                     $totaltime = getTimeDiff($firstclockin->attendance_time, $lastclockout->attendance_time);
    //                     $timeDiff[$i] = $totaltime;
    //                     $timeDiffWork[$i] = $totaltime;
    //                     $timeDiffOvertime[$i] = 0;
    //                     if(strtotime($totaltime) > strtotime($commonWorkingHours.':00:00'))
    //                     { 
    //                         $diff = date('H:i:s', strtotime($totaltime. ' -'.$commonWorkingHours.' hours')); 
    //                         $timeDiffWork[$i] = $commonWorkingHours.":00";
    //                         $timeDiffOvertime[$i] = $diff;
    //                     }
    //                 }
    //             }//echo '<pre>';print_r($timeDiff);echo '<pre>';print_r($timeDiffWork);echo '<pre>';print_r($timeDiffOvertime);
    //             //sum timediff to get total hours
    //             if($timeDiffWork > 0)
    //             {
    //                 $totalWorkinghours = addTimeDiff($timeDiffWork);
    //                 $totalOvertimehours = addTimeDiff($timeDiffOvertime);
    //             }
    //             //calculate hourly salary
    //             list($h, $m) = explode(':',$totalWorkinghours);  //Split up string into hours/minutes
    //             $decimal = $m/60;  //get minutes as decimal
    //             $hoursAsDecimal = $h+$decimal;
    //             $totalSalary=$hoursAsDecimal*$hourlySalary;

    //             //calculate hourly salary for overtime
    //             list($oh, $om) = explode(':',$totalOvertimehours);  //Split up string into hours/minutes
    //             $odecimal = $om/60;  //get minutes as decimal
    //             $ohoursAsDecimal = $oh+$odecimal;
    //             $totalOvertimeSalary=$ohoursAsDecimal*$hourlySalary;

    //             //salary after deduction
    //             $finalSalary = $totalSalary;
    //             // echo '<pre>';print_r($deductionDetails);
    //             $insertArray = array(
    //                 'residency_id'     =>   $emp->company,
    //                 'branch_id'        =>   $emp->branch,
    //                 'financial_year'   =>   $currentFY->id,
    //                 'emp_id'           =>   $emp->user_id,
    //                 'es_month'         =>   $currentMonth,
    //                 'es_year'          =>   $currentYear,
    //                 'month_w_days'     =>   $month_w_days,
    //                 'month_holidays'   =>   $noOfHolidays,
    //                 'month_fridays'    =>   $noOfFridays,
    //                 'month_salary'     =>   $currentMonthSalary,
    //                 'day_salary'       =>   $daySalary,
    //                 'hourly_salary'    =>   $hourlySalary,
    //                 'salary_type'      =>   'overtime',
    //                 'salary'           =>   $totalSalary,
    //                 'deductions'       =>   0,
    //                 'additions'        =>   0,
    //                 'overtime'         =>   0,
    //                 'total_salary'     =>   0,
    //                 'total_work_hours' =>   $totalWorkinghours,
    //                 'total_overtime_salary'     =>   round($totalOvertimeSalary,2),
    //                 'total_work_overtime'       =>   $totalOvertimehours,
    //                 'created_at'       =>   date('Y-m-d h:i:s'),
    //                 'status'           =>   'active'
    //             );//echo '<pre>';print_r($insertArray);exit;
    //             $emsId = EmployeeMonthlySalary::insertGetId($insertArray);
                
    //             //exit;
    //         }
    //     }
        
    //     return true;
    // }

    // public function calculateSalary($user_id)
    // {
        
    //     //get salary of currentFY
    //     $salaryDetails = EmployeeSalary::where(array('emp_id' => $user_id, 'financal_year' => $currentFY->id))->get();
    //     $totalSalary = $salaryDetails->basic_salary;

        

    //     //total working days in a month
    //     $totalWorkingDays = '';

    //     //total worked days of employee
    //     $totalWorkedDays = '';

    // }
        
}
