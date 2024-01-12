<?php
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\EmployeeSalary;
use App\Models\EmployeeLoan;
use App\Models\FinancialYear;
use App\Models\Overtime;
use App\Models\AttendanceDetails;
use App\Models\Scheduling;
use App\Models\Leaves;
use App\Models\EmployeeSalaryData;
use App\Models\LeaveApprovalLogs;
use App\Models\EmployeeBonus;
use App\Models\EmployeeOvertimeData;
use App\Models\EmployeeDeduction;
use App\Models\Holidays;
use App\Models\Shifting;

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

function calculateSalaryByFilter($user_id,$empid,$mid,$year,$type='')
{
    //  $empid = 201;
    //  $user_id = 492; 
    $endDateOrg = $year.'-'.$mid.'-'.'19';//date('Y-m-20');
    $startDateOrg =  date('Y-m-d', strtotime('-1 month', strtotime($endDateOrg)));
    $startDateOrg = date('Y-m-d', strtotime('+1 days', strtotime($startDateOrg)));
    $total_calculate_salary = 0;
    if(!empty($mid)):
        $commonWorkingHoursDetails = Overtime::first();
        $commonWorkingHours = $commonWorkingHoursDetails->working_hours - 1;
        $commonWorkingDays = $commonWorkingHoursDetails->working_days;
        //dd($emp_id);
        $total_schedule_hours = AttendanceDetails::whereBetween('attendance_on', [$startDateOrg, $endDateOrg])->where('employee_id',$empid)->sum('schedule_hours');
        $total_working_days = AttendanceDetails::whereBetween('attendance_on', [$startDateOrg, $endDateOrg])->where('punch_state','clockin')->where('employee_id',$empid)->count();
        $total_off_days = AttendanceDetails::whereBetween('attendance_on', [$startDateOrg, $endDateOrg])->where('day_type','off')->where('employee_id',$empid)->count();
        if($total_off_days >= 5):
            $total_schedule_hours = $total_schedule_hours + $commonWorkingHours;
        endif;
        $find_fs_days = Scheduling::whereBetween('shift_on', [$startDateOrg, $endDateOrg])->where('employee',$user_id)->whereIn('shift',[3,8,2])->count();
        $find_fs_hours = $find_fs_days * 8;
        //find employee salary details
        $employee = Employee::where('user_id',$user_id)->where('emp_generated_id',$empid)->first();
        //calculate salary now
        if(!empty($employee)):
            $currentMonthSalary = (isset($employee->employee_salary))?$employee->employee_salary->basic_salary:0;
            $daySalary = ($currentMonthSalary>0)?($currentMonthSalary/$commonWorkingDays):0;
            $hourlySalary = ($daySalary>0)?($daySalary/$commonWorkingHours):0;
            $total_calculate_salary = ($total_schedule_hours + $find_fs_hours) * $hourlySalary;
            
        endif;
        $res_arr = [];
        if($type=='report_pdf'):
            $res_arr['total_schedule_hours'] = $total_schedule_hours;
            $res_arr['find_fs_hours'] = $find_fs_hours;
            $res_arr['hourly_salary'] = $hourlySalary;
            $res_arr['day_salary'] = $daySalary;
            $res_arr['total_salary'] = $total_calculate_salary;
            $res_arr['dates_between'] = $startDateOrg.','.$endDateOrg;
            return $res_arr;
        else:
            return $total_calculate_salary;
        endif;
    endif;
}

function calculateBonusByMonth($empid,$mid,$year,$type='')
{
    $endDateOrg = $year.'-'.$mid.'-'.'19';//date('Y-m-20');
    $startDateOrg =  date('Y-m-d', strtotime('-1 month', strtotime($endDateOrg)));
    $startDateOrg = date('Y-m-d', strtotime('+1 days', strtotime($startDateOrg)));
    $total = EmployeeBonus::whereBetween('bonus_date', [$startDateOrg, $endDateOrg])->where('employee_id',$empid)->sum('bonus_amount');
    return $total;
}

function calculateDeductionByMonth($empid,$mid,$year,$type='')
{
    $endDateOrg = $year.'-'.$mid.'-'.'19';//date('Y-m-20');
    $startDateOrg =  date('Y-m-d', strtotime('-1 month', strtotime($endDateOrg)));
    $startDateOrg = date('Y-m-d', strtotime('+1 days', strtotime($startDateOrg)));
    $total = EmployeeDeduction::whereBetween('deduction_date', [$startDateOrg, $endDateOrg])->where('employee_id',$empid)->sum('deduction_amount');
    return $total;
}

function clockalize($in){
    $explode_data =  explode('.',$in);
    $h = $explode_data[0] ?? 0;
    $m = 0;
    if(isset($explode_data[1]) && !empty($explode_data[1])):
        if(strlen($explode_data[1]) == 1):
            //add 0
            $addm = $explode_data[1].'0';
            $m = $addm;
        else:
            $m = $explode_data[1] ?? 0;
        endif;
    endif;
    $s=0;
    return sprintf('%02d:%02d:%02d', $h, $m,$s);
 }
 
 function calculateOvertimeByFilter($user_id,$empid,$mid,$year,$type='')
 {
    //   $empid = 1231;
    //   $user_id = 500;
     $endDateOrg = $year.'-'.$mid.'-'.'19';//date('Y-m-20');
     $startDateOrg =  date('Y-m-d', strtotime('-1 month', strtotime($endDateOrg)));
     $startDateOrg = date('Y-m-d', strtotime('+1 days', strtotime($startDateOrg)));
     $total_calculate_salary = 0;
     if(!empty($mid)):
         $commonWorkingHoursDetails = Overtime::first();
         $commonWorkingHours = $commonWorkingHoursDetails->working_hours - 1;
         $commonWorkingDays = $commonWorkingHoursDetails->working_days;
         $total_overtime_data = AttendanceDetails::whereBetween('attendance_on', [$startDateOrg, $endDateOrg])->where('employee_id',$empid)->whereNotNull('overtime_hours')->pluck('overtime_hours')->toArray();
         //dd($total_overtime_data);
         $sum = strtotime('00:00:00');
         $totaltime = 0;
         $h = $m = 0;
         if(count($total_overtime_data) > 0): 
             foreach( $total_overtime_data as $element ) {
                 $ctime = clockalize($element);
                 //echo $ctime;exit;
                 // Converting the time into seconds
                 $timeinsec = strtotime($ctime) - $sum;
                 
                 // Sum the time with previous value
                 $totaltime = $totaltime + $timeinsec;
                 //echo $totaltime;echo'<pre>';
             }
            // dd($totaltime );
             $h = intval($totaltime / 3600);
             $totaltime = $totaltime - ($h * 3600);
             
             // Minutes is obtained by dividing
             // remaining total time with 60
             $m = intval($totaltime / 60);
         
             // Remaining value is seconds
             $s = $totaltime - ($m * 60);
         endif;
         $total_overtime_hours = $h.'.'.$m;
         //find employee salary details
         $employee = Employee::where('user_id',$user_id)->where('emp_generated_id',$empid)->first();
         //calculate salary now
         if(!empty($employee)):
             $currentMonthSalary = (isset($employee->employee_salary))?$employee->employee_salary->basic_salary:0;
             $daySalary = ($currentMonthSalary>0)?($currentMonthSalary/$commonWorkingDays):0;
             $hourlySalary = ($daySalary>0)?($daySalary/$commonWorkingHours):0;
             $total_calculate_salary = $total_overtime_hours * $hourlySalary;
         endif;
         $res_arr = [];
         if($type=='report_pdf'):
             $res_arr['hourly_salary'] = $hourlySalary;
             $res_arr['day_salary'] = $daySalary;
             $res_arr['total_overtime_hours'] = $total_overtime_hours;
             $res_arr['total_salary'] = $total_calculate_salary;
             $res_arr['dates_between'] = $startDateOrg.','.$endDateOrg;
             return $res_arr;
         else:
             $res_arr['total_overtime_hours'] = $total_overtime_hours;
             $res_arr['total_salary'] = $total_calculate_salary;
             $res_arr['dates_between'] = $startDateOrg.','.$endDateOrg;
             return $res_arr;
         endif;
     endif;
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

    function _convert_time_to_12hour_format($time){
        if(!empty($time)):
            $convert_time = date("H:i", strtotime($time));
            return $convert_time;
        endif;
    }

    function _convert_time_to_12hour_dateformat($date){
        if(!empty($date)):
            $convert_time = date("Y-m-d H:i", strtotime($date));
            return $convert_time;
        endif;
    }

    function _convert_time_to_12hour_format_bydate($date){
        $convert_date = date("h:i a", strtotime($date));
        return $convert_date;
    }

    function get_total_hours($start_time,$end_time){
        $datetime1 = strtotime($start_time);
        $datetime2 = strtotime($end_time);
        $interval  = abs($datetime2 - $datetime1);
        $minutes   = round($interval / 60);
        $hours = $minutes/60;
        return $hours;
    }

    function get_time_slots_equal_val($start_time,$end_time)
	{
		$space_avai_time_slot =[];
		$space_avai_time_slot_arr = [];
		$interval = new DateInterval("PT1H");
		for ($now = clone $start_time; $now <= $end_time; $now->add($interval)) {
            $time_val = $now->format("H:i:s");
            //echo $time_val.'<br>';
            $space_avai_time_slot[$time_val] = $time_val;

        }
        $space_avai_time_slot_arr = $space_avai_time_slot;
        return $space_avai_time_slot_arr;
	}


    function _check_green_icon_attendance($attnDate,$attnUserId){
        $is_holiday = Holidays::whereDate('holiday_date',$attnDate)->first();
        $encoded = base64_encode(json_encode($attnDate.'/'.$attnUserId));

        $firstclockin = AttendanceDetails::where('user_id', $attnUserId)->where('punch_state', 'clockin')->whereDate('attendance_on', $attnDate 
        )->first();
       
        $lastclockout = AttendanceDetails::where('atte_ref_id', $firstclockin->id ?? '')->where('punch_state', 'clockout')->first();
        // get shift details
        $shiftDetails = Scheduling::where('employee',$attnUserId)->where('shift_on', date('Y-m-d',strtotime($attnDate)))->where('status','active')->first();
        $shcolor = '';
        $shicon = '';
        $flag = 0;

        $minStartTime_24 = (isset($shiftDetails->min_start_time))?date('H:i', strtotime($shiftDetails->min_start_time)):'0';
        $maxStartTime_24 = (isset($shiftDetails->max_start_time))?date('H:i', strtotime($shiftDetails->max_start_time)):'0';
        $minEndTime_24 = (isset($shiftDetails->min_end_time))?date('H:i', strtotime($shiftDetails->min_end_time)):'0';
        $maxEndTime_24 = (isset($shiftDetails->max_end_time))?date('H:i', strtotime($shiftDetails->max_end_time)):'0';
        if(!empty($shiftDetails) && (in_array($shiftDetails->shift, array(3))))
        {
            $flag = 2;
        }else{
            if((isset($firstclockin->attendance_time) && checkDateTimeInBetween($firstclockin->attendance_time, $minStartTime_24, $maxStartTime_24)==1) && (isset($lastclockout->attendance_time) && checkDateTimeInBetween($lastclockout->attendance_time, $minEndTime_24, $maxEndTime_24)==1))
            {
                $shcolor = 'text-success';
                $shicon = 'fa-check';
                    //update  public holiday balance
                    if(!empty($is_holiday)):
                    $emplo = Employee::where('user_id',$attnUserId)->first();
                    if(!empty($emplo)):
                        $commonWorkingHoursDetails = Overtime::first();
                        $commonWorkingHours = $commonWorkingHoursDetails->working_hours - 1;
                        $commonWorkingDays = $commonWorkingHoursDetails->working_days;
                        $currentMonthSalary = (isset($emplo->employee_salary))?$emplo->employee_salary->basic_salary:0;
                        $daySalary = ($currentMonthSalary>0)?($currentMonthSalary/$commonWorkingDays):0;
                        $hourlySalary = ($daySalary>0)?($daySalary/$commonWorkingHours):0;
                        $ph_amount = $hourlySalary * 8;
                        $ph_bal_amount = $emplo->public_holidays_amount;
                        $ph_bal = $emplo->public_holidays_balance;
                        $emplo->public_holidays_balance = $ph_bal + 1;
                        $emplo->public_holidays_amount = $ph_bal_amount + $ph_bal;
                        $emplo->save();
                    endif;
                endif;
            }else{
                $shcolor = 'text-warning';
                $shicon = 'fa-info-circle';
                $flag = 1;
            }
        }
        return $flag;
    }

    function save_schedule_overtime_hours($user_id,$att_date,$start_time,$end_time){
        $firstclockin = AttendanceDetails::where('user_id', $user_id)->where('punch_state', 'clockin')->whereDate('attendance_on', $att_date 
        )->first();
        $lastclockout = AttendanceDetails::where('atte_ref_id', $firstclockin->id)->where('punch_state', 'clockout')->value('attendance_on');
        $start_time = _convert_time_to_12hour_dateformat($att_date.' '.$start_time);
        $end_time = _convert_time_to_12hour_dateformat($lastclockout.' '.$end_time);
       // dd($start_time);
        $shiftDetails = Scheduling::where('employee', $user_id)->where('shift_on', $att_date)->where('status', 'active')->first();
        $is_cod = (isset($shiftDetails->shift_details) && !empty($shiftDetails->shift_details)) ? $shiftDetails->shift_details->is_cod : 0;
        $break_time_in_minute = $shiftDetails->break_time ?? 0;
        $break_time = 0;
        if(!empty($break_time_in_minute)):
            $hours = floor($break_time_in_minute / 60);
            $min = $break_time_in_minute - ($hours * 60);
            $break_time = $hours.".".$min;
        endif;

        $break_time = $break_time + 1;
        $commonWorkingHoursDetails = Overtime::first();
        $commonWorkingHours = $commonWorkingHoursDetails->working_hours;
        if(!empty($shiftDetails)):
            $schedule_start_time = _convert_time_to_12hour_dateformat($shiftDetails->start_time);
            $schedule_end_time = _convert_time_to_12hour_dateformat($shiftDetails->end_time);
            $total_schedule_hours = get_total_hours($schedule_start_time,$schedule_end_time);
            $total_attendanace_hours = get_total_hours($start_time,$end_time);
            $final_diff = 0;
            $total_attendanace_hours = floatval($total_attendanace_hours);
            $commonWorkingHours = (int)$commonWorkingHours;

            if($is_cod== '0'):
                if($total_attendanace_hours > $commonWorkingHours):
                    $diff = $total_attendanace_hours - ($commonWorkingHours - 1 + $break_time);
                    $final_diff =  $diff < 0 ? (-1) * $diff : $diff;
                    //$final_diff = $final_diff - 1;
                endif;
            endif;
            if($is_cod== '1'):
                //dd($total_attendanace_hours);
                //save schedule hours and overtime data in attanedance detail yable
                $save_data = AttendanceDetails::where('user_id',$user_id)->where('attendance_on',$att_date)->where('punch_state','clockin')->first();
                $save_data->schedule_hours = 0;
                $save_data->overtime_hours = $total_attendanace_hours-$break_time;
                $save_data->save();
                return true;
            else:
                //save schedule hours and overtime data in attanedance detail yable
                $save_data = AttendanceDetails::where('user_id',$user_id)->where('attendance_on',$att_date)->where('punch_state','clockin')->first();
                $save_data->schedule_hours = $commonWorkingHours-1;
                $save_data->overtime_hours = $final_diff ?? 0;
                $save_data->save();
                return true;
            endif;
        endif;
    }

    function calculate_employee_allowence($emp_id){
        $total_allowence = EmployeeSalary::where('emp_id',$emp_id)->sum(DB::raw('travel_allowance + food_allowance + house_allowance + position_allowance +phone_allowance + other_allowance'));
        return $total_allowence;
    }

    function calcualte_total_earning_by_month_company($month,$year,$company_id,$branch_id,$type,$atype=''){
        if($atype == 'total'):
            $total = EmployeeSalaryData::where('es_month',$month)->where('es_year',$year)->where('branch_id',$branch_id)->groupBy('branch_id')->sum('total_earning');
        //dd($company_id.'---'.$branch_id);
        else:
            $total = EmployeeSalaryData::where('es_month',$month)->where('es_year',$year)->where('company_id',$company_id)->where('branch_id',$branch_id)->groupBy('branch_id','company_id')->where('type',$type)->sum('total_earning');
        endif;
            return $total;
    }

    function calcualte_total_by_month_company($month,$year,$company_id,$type){
        $total = EmployeeSalaryData::where('es_month',$month)->where('es_year',$year)->where('company_id',$company_id)->where('type',$type)->groupBy('company_id')->sum('total_earning');
        return $total;
    }

    function calcualte_ot_total_earning_by_month_company($month,$year,$company_id,$branch_id,$type,$atype=''){
        if($atype == 'total'):
            $total = EmployeeOvertimeData::where('es_month',$month)->where('es_year',$year)->where('branch_id',$branch_id)->groupBy('branch_id')->sum('total_earning');
        //dd($company_id.'---'.$branch_id);
        else:
            $total = EmployeeOvertimeData::where('es_month',$month)->where('es_year',$year)->where('company_id',$company_id)->where('branch_id',$branch_id)->groupBy('branch_id','company_id')->where('type',$type)->sum('total_earning');
        endif;
            return $total;
    }

    function calcualte_ot_total_by_month_company($month,$year,$company_id,$type){
        $total = EmployeeOvertimeData::where('es_month',$month)->where('es_year',$year)->where('company_id',$company_id)->where('type',$type)->groupBy('company_id')->sum('total_earning');
        return $total;
    }
    
    function calculateLeave($joiningDate, $currentDate) {
        $joiningDateTime = new DateTime($joiningDate);

        $currentDateTime = new DateTime($currentDate);
       
        $curl_url = 'https://test.hrmado.com/annual_leave_calculator.php?date='.$joiningDate.'&cdate='.$currentDate.'';

        // create & initialize a curl session
        $curl = curl_init();

        // set our url with curl_setopt()
        curl_setopt($curl, CURLOPT_URL, $curl_url);

        // return the transfer as a string, also with setopt()
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // curl_exec() executes the started curl session
        // $output contains the output string
        $output = curl_exec($curl);

        // close curl resource to free up system resources
        // (deletes the variable made by curl_init)
        curl_close($curl);
        return $output;

    }

    function getAnnualLeaveDetails($id)
    {
        $employee = Employee::where('user_id', $id)->where('status','!=','deleted')->first();
        $todaydate = date('Y-m-d');
        if((isset($employee->resigned_date)) &&$employee->resigned_date!=null)
        {
            $todaydate = $employee->resigned_date;
        }
        $joining_date = (isset($employee->joining_date)) ? $employee->joining_date : $todaydate;
        $totalLeaveDays = calculateLeave($joining_date, $todaydate);
       
        $totalLeaveDays = intval($totalLeaveDays);
        $balance = (!empty($employee) && (isset($employee->opening_leave_days)))?$employee->opening_leave_days:0;
        $balance = floatval($balance);
        $used = ($balance > 0 && $totalLeaveDays > 0)?$totalLeaveDays - $balance:0;
        $leaveBalance = $totalLeaveDays - $used;
        $sal = (isset($employee->employee_salary->basic_salary))?$employee->employee_salary->basic_salary:0;
        $perday = $sal / 26;
        $leaveAmount = $perday * $leaveBalance;
        $total_request_leave = $employee->request_leave_days ?? 0;
        $remaining_leave = $totalLeaveDays - ($balance + $total_request_leave);
        $remaining_leave_withoutreq = $totalLeaveDays - $balance;
        $balance_leave_amount = $perday * $remaining_leave_withoutreq;
        $result = array(
            'totalLeaveDays'    =>  $totalLeaveDays,
            'used'              =>  $used,
            'leaveBalance'      =>  $leaveBalance,
            'leaveAmount'       =>  $leaveAmount,
            'remaining_leave'   =>  $remaining_leave,
            'remaining_leave_withoutreq'   =>  $remaining_leave_withoutreq,
            'balance_leave_amount' => $balance_leave_amount);
        return $result;
    }

    function getSickLeaveDetails($id)
    {
        $employee = Employee::where('user_id', $id)->where('status','!=','deleted')->first();
        $todaydate = date('Y-m-d');
        if((isset($employee->resigned_date)) &&$employee->resigned_date!=null)
        {
            $todaydate = $employee->resigned_date;
        }
        $joining_date = (isset($employee->joining_date)) ? $employee->joining_date : $todaydate;
        $totalLeaveYearMonths = getDateDiff($joining_date, $todaydate);
        // echo $totalLeaveYearMonths;
        $exYM = explode('.', $totalLeaveYearMonths);
        $totalLeaveMonths = ($exYM[0] * 12) +$exYM[1];
        // echo $employee->opening_leave_days;
        $totalLeaveDays = 15;
        $balance = (!empty($employee) && (isset($employee->sick_leave_days)))?$employee->sick_leave_days:0;
        $used = ($balance > 0 && $totalLeaveDays > 0)?$totalLeaveDays - $balance:0;
        $leaveBalance = $totalLeaveDays - $used;
        
        $sal = (isset($employee->employee_salary->basic_salary))?$employee->employee_salary->basic_salary:0;
        $perday = $sal / 26;
        $leaveAmount = $perday * $leaveBalance;
        $total_request_leave = $employee->sick_leave_request_days ?? 0;
        $remaining_leave = $totalLeaveDays - ($balance + $total_request_leave);
        $remaining_leave_withoutreq = $totalLeaveDays - $balance;
        $result = array(
            'totalLeaveDays'    =>  $totalLeaveDays,
            'used'              =>  $used,
            'leaveBalance'      =>  $leaveBalance,
            'leaveAmount'       =>  $leaveAmount,
            'remaining_leave'   =>  $remaining_leave,
            'remaining_leave_withoutreq' => $remaining_leave_withoutreq,
            'taken_leave' => $balance);
        return $result;
    }

    function is_leave_approved_any_approver($leave_id){
      
        $count = LeaveApprovalLogs::where('leave_id',$leave_id)->where('status','approved')->count();
        return $count;
    }

    function total_allowence_withput_food($id){
        $total = EmployeeSalaryData::where('id',$id)->sum(DB::raw('travel_allowence + house_allowence + position_allowence +phone_allowence + other_allowence'));
        return $total;
    }

    function _get_attendance_time($attnDate,$attnUserId,$type){
        $firstclockin = AttendanceDetails::where('user_id', $attnUserId)->where('punch_state', $type)->whereDate('attendance_on', $attnDate 
        )->value('attendance_time');
        return $firstclockin;
    }

    function _get_emp_manual_punchin($user_id){
        $data = Employee::where('user_id',$user_id)->value('is_manual_punchin');
        return $data;
    }

    function is_employee_exist($user_id){
        $data = Employee::where('user_id',$user_id)->value('id');
        return $data;
    }

    function _get_schedule_time_by_emp($att_date,$userId){
        $scheduling = Scheduling::where('shift_on',$att_date)->where('employee',$userId)->first();
        return $scheduling;
    }

    function _calculate_salary_by_days($currentMonthSalary=0,$days=0){
        $commonWorkingHoursDetails = Overtime::first();
        $commonWorkingHours = $commonWorkingHoursDetails->working_hours - 1;
        $commonWorkingDays = $commonWorkingHoursDetails->working_days;
        $daySalary = ($currentMonthSalary>0)?($currentMonthSalary/$commonWorkingDays):0;
        $hourlySalary = ($daySalary>0)?($daySalary/$commonWorkingHours):0;
        $total_salary = $daySalary * $days;
        return $total_salary;
    }

    function _get_cod_shift_name($shift_id){
        $data = Shifting::where('parent_shift',$shift_id)->first();
        return $data->suid ?? '';
    }

    function _is_user_role_owner($user_id){
        $emp_data = Employee::where('user_id',$user_id)->select('designation','branch')->first();
        if(!empty($emp_data)):
            if($emp_data->designation == 162):
                return $emp_data;
            endif;
        endif;
    }

    function convertToHoursMinutes($time,$break_time)
    {
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        $second = '00';
        $hours = $hours-$break_time;
        return sprintf('%02d:%02d:%02d', $hours, $minutes,$second);
    }

    function get_break_time_for_shift($shift){
        $shiftDetails = Shifting::where('id', $shift)->first();
        $break_time_in_minute = $shiftDetails->break_time ?? 0;
        $break_time = 0;
        if(!empty($break_time_in_minute)):
            $hours = floor($break_time_in_minute / 60);
            $min = $break_time_in_minute - ($hours * 60);
            $break_time = $hours.".".$min;
        endif;
        return $break_time;
        
    }

    
    
