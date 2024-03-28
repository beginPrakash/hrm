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
use App\Models\Residency;
use App\Models\EmployeeDetails;
use App\Models\CompanyDocuments;
use App\Models\TransportationDoc;
use App\Models\Transportation;
use App\Models\Branch;
use App\Models\SellingPeriod;
use App\Models\SalesTargetMaster;
use App\Models\TrackingHeading;
use App\Models\StoreDailySales;
use App\Models\UpSellingHeading;
use App\Models\DailySalesTargetUpselling;
use App\Models\SettingsModel;
use App\Models\EmployeeOvertime;
use App\Models\UserRoles;

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

function _get_analytics($key){
    $data = SettingsModel::where('key',$key)->value('value');
    return $data;
}

function calculateSalaryByFilter($user_id,$empid,$mid,$year,$type='')
{
     // $empid = 50027;
      //$user_id = 736; 
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
        $total_working_days = AttendanceDetails::whereBetween('attendance_on', [$startDateOrg, $endDateOrg])->where('punch_state','clockin')->where('day_type','work')->where('employee_id',$empid)->count();
        $total_error_days = AttendanceDetails::whereBetween('attendance_on', [$startDateOrg, $endDateOrg])->where('punch_state','clockin')->where('employee_id',$empid)->whereNull('schedule_hours')->whereNull('overtime_hours')->count();
        $total_cod_days = AttendanceDetails::whereBetween('attendance_on', [$startDateOrg, $endDateOrg])->where('punch_state','clockin')->where('employee_id',$empid)->where('schedule_hours','0.00')->whereNotNull('overtime_hours')->count();
        $total_absent_days = AttendanceDetails::whereBetween('attendance_on', [$startDateOrg, $endDateOrg])->where('day_type','absent')->where('employee_id',$empid)->count();
        $total_off_days = AttendanceDetails::whereBetween('attendance_on', [$startDateOrg, $endDateOrg])->where('day_type','off')->where('employee_id',$empid)->count();
        $total_non_working_days = $commonWorkingDays - $total_working_days;
        $cal_off_days = 0;
        if($total_off_days >= 6):
            $total_schedule_hours = $total_schedule_hours + $commonWorkingHours;
            $cal_off_days = $cal_off_days + 1;
        endif;

        
        
        $find_fs_days = Scheduling::whereBetween('shift_on', [$startDateOrg, $endDateOrg])->where('employee',$user_id)->whereIn('shift',[7,9])->count();
        $find_fs_hours = $find_fs_days * 8;
        $total_count_days = $total_absent_days;
        $total_deduction_days = $total_error_days + $find_fs_days;
        //find employee salary details
        $employee = Employee::where('user_id',$user_id)->where('emp_generated_id',$empid)->first();
        //calculate salary now
        //dd($total_count_days);
        if(!empty($employee)):
            $currentMonthSalary = (isset($employee->employee_salary))?$employee->employee_salary->basic_salary:0;
            $daySalary = ($currentMonthSalary>0)?($currentMonthSalary/$commonWorkingDays):0;
            $hourlySalary = ($daySalary>0)?($daySalary/$commonWorkingHours):0;
            //$total_calculate_salary = ($total_schedule_hours + $find_fs_hours) * $hourlySalary;
            $total_calculate_salary = ($commonWorkingDays - $total_deduction_days) * $daySalary;
            
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
     //find employee salary details
     $employee = Employee::where('user_id',$user_id)->where('emp_generated_id',$empid)->first();
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
         $total_overtime_hours = (float)$total_overtime_hours;
         //calculate manual ot
         $total_manual_overtime_data = EmployeeOvertime::whereBetween('ot_date', [$startDateOrg, $endDateOrg])->where('employee_id',$employee->id ?? '')->whereNotNull('ot_hours')->pluck('ot_hours')->toArray();

         $msum = strtotime('00:00:00');
         $totalmtime = 0;
         $man_h = $man_m = 0;
         if(count($total_manual_overtime_data) > 0): 
             foreach( $total_manual_overtime_data as $element ) {
                 $ctime = clockalize($element);
                 //echo $ctime;exit;
                 // Converting the time into seconds
                 $timeinsec = strtotime($ctime) - $msum;
                 
                 // Sum the time with previous value
                 $totalmtime = $totalmtime + $timeinsec;
                 //echo $totaltime;echo'<pre>';
             }
            // dd($totaltime );
             $man_h = intval($totalmtime / 3600);
             $totalmtime = $totalmtime - ($man_h * 3600);
             
             // Minutes is obtained by dividing
             // remaining total time with 60
             $man_m = intval($totalmtime / 60);
         
             // Remaining value is seconds
             $s = $totalmtime - ($man_m * 60);
         endif;
         $total_manual_overtime_hours = $man_h.'.'.$man_m;
         $total_manual_overtime_hours = (float)$total_manual_overtime_hours;

         
         //calculate salary now
         if(!empty($employee)):
             $currentMonthSalary = (isset($employee->employee_salary))?$employee->employee_salary->basic_salary:0;
             $daySalary = ($currentMonthSalary>0)?($currentMonthSalary/$commonWorkingDays):0;
             $hourlySalary = ($daySalary>0)?($daySalary/$commonWorkingHours):0;
             $total_calculate_salary = $total_overtime_hours * $hourlySalary;
             $total_manual_ot_salary = $total_manual_overtime_hours * $hourlySalary;
         endif;
         $res_arr = [];
         if($type=='report_pdf'):
             $res_arr['hourly_salary'] = $hourlySalary;
             $res_arr['day_salary'] = $daySalary;
             $res_arr['total_overtime_hours'] = $total_overtime_hours;
             $res_arr['total_salary'] = $total_calculate_salary;
             $res_arr['total_manual_overtime_hours'] = $total_manual_overtime_hours;
             $res_arr['total_manual_ot_salary'] = $total_manual_ot_salary;
             $res_arr['dates_between'] = $startDateOrg.','.$endDateOrg;
             return $res_arr;
         else:
             $res_arr['total_overtime_hours'] = $total_overtime_hours;
             $res_arr['total_salary'] = $total_calculate_salary;
             $res_arr['total_manual_overtime_hours'] = $total_manual_overtime_hours;
             $res_arr['total_manual_ot_salary'] = $total_manual_ot_salary;
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
        $seconds = $interval;
        $hours = floor($seconds / 3600);
        $seconds -= $hours * 3600;
        $minutes = floor($seconds / 60);


        $data = $hours.'.'.$minutes;
        return (float)$data;
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
    //    / dd($end_time);
        $firstclockin = AttendanceDetails::where('user_id', $user_id)->where('punch_state', 'clockin')->whereDate('attendance_on', $att_date 
        )->first();
       
        $lastclockout = AttendanceDetails::where('atte_ref_id', $firstclockin->id)->where('punch_state', 'clockout')->value('attendance_on');
        $start_time = _convert_time_to_12hour_dateformat($att_date.' '.$start_time);
        $end_time = _convert_time_to_12hour_dateformat($lastclockout.' '.$end_time);
        //dd($end_time);
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
                    $total_shsche_att_hours = get_total_hours($schedule_start_time,$schedule_end_time);
                    $diff = $total_shsche_att_hours - ($commonWorkingHours - 1 + $break_time);
                    $final_diff =  $diff < 0 ? (-1) * $diff : $diff;
                    //$final_diff = $final_diff - 1;
                endif;
            endif;
            if($is_cod== '1'):
                //dd($total_attendanace_hours);
                //save schedule hours and overtime data in attanedance detail yable
                $save_data = AttendanceDetails::where('user_id',$user_id)->where('attendance_on',$att_date)->where('punch_state','clockin')->first();
                $total_shsche_att_hours = get_total_hours($schedule_start_time,$schedule_end_time);
                $save_data->schedule_hours = 0;
                $save_data->overtime_hours = $total_shsche_att_hours-$break_time;
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

        $interval = $currentDateTime->diff($joiningDateTime);
        $total_days = $interval->days ?? 0;
        $total_leave = ($total_days/30.41)*2.5;
        return $total_leave;
       
        // $curl_url = 'https://test.hrmado.com/annual_leave_calculator.php?date='.$joiningDate.'&cdate='.$currentDate.'';
        // $curl = curl_init();
        // curl_setopt($curl, CURLOPT_URL, $curl_url);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // $output = curl_exec($curl);
        // curl_close($curl);
        // return $output;

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
       
        $totalLeaveDays = number_format($totalLeaveDays,2);
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

    function _get_company_name($id){
        $data = Residency::where('id',$id)->value('name');
        return $data ?? '';
    }

    function _get_branch_name_by_comapny($id,$company_id){
        $data = Branch::where('id',$id)->where('residency',$company_id)->value('name');
        return $data ?? '';
    }

    function _get_sellingperiod_by_comapny($id,$company_id,$branch_id){
        $s_name = SellingPeriod::where('id',$id)->value('item_name');
        $data = SellingPeriod::where('item_name',$s_name)->where('company_id',$company_id)->where('branch_id',$branch_id)->where('is_show','1')->value('item_name');
        return $data ?? '';
    }
    

    function _sum_of_empcost($ids='',$type=''){
        $data = EmployeeDetails::whereIn('emp_id',explode(',',$ids))->whereNotNull($type)->sum($type);
        return $data;
    }

    function _sum_of_companycost($ids=''){
        $data = CompanyDocuments::whereIn('id',explode(',',$ids))->whereNotNull('cost')->sum('cost');
        return $data;
    }

    function _sum_of_transportcost($ids=''){
        $data = TransportationDoc::whereIn('id',explode(',',$ids))->whereNotNull('cost')->sum('cost');
        return $data;
    }

    function _get_sales_master_data_by_id($company_id,$branch_id,$sell_id,$month){
        $s_id = SellingPeriod::where('id',$sell_id)->value('id');
        $data = SalesTargetMaster::where('company_id',$company_id)->where('branch_id',$branch_id)->where('sell_p_id',$s_id)->where('month',$month)->first();
        return $data;
    }

    function _get_sales_master_sum_by_id($company_id,$branch_id,$sell_id,$month){
        $s_id = SellingPeriod::where('id',$sell_id)->value('id');
        $data = SalesTargetMaster::where('company_id',$company_id)->where('branch_id',$branch_id)->where('sell_p_id',$s_id)->where('month',$month)->sum('per_day_price');
        return $data;
    }

    function _tracking_heading_by_speriod($company_id,$branch_id,$sell_id){
        $data = TrackingHeading::where('company_id',$company_id)->where('branch_id',$branch_id)->where('sell_p_id',$sell_id)->get();
        return $data;
    }

    function _upselling_heading_by_speriod($company_id,$branch_id,$sell_id){
        $data = UpSellingHeading::where('company_id',$company_id)->where('branch_id',$branch_id)->where('sell_p_id',$sell_id)->get();
        return $data;
    }

    function _target_price_by_sell($company_id,$branch_id,$sell_id,$serch_date){
        if(!empty($serch_date)):
            $month = date('n',strtotime($serch_date));
            $data = SalesTargetMaster::where('company_id',$company_id)->where('branch_id',$branch_id)->where('sell_p_id',$sell_id)->where('month',$month)->value('per_day_price');
            return $data ?? 0;
        endif;
       
    }

    function _is_daily_sales_exists($company_id,$branch_id,$sell_id,$serch_date){
        if(!empty($serch_date)):
            $serch_date = date('Y-m-d',strtotime($serch_date));
            $data = StoreDailySales::where('company_id',$company_id)->where('branch_id',$branch_id)->where('sell_p_id',$sell_id)->whereDate('sales_date',$serch_date)->first();
            return $data;
        endif;
    }

    function _is_upseldaily_sales_exists($company_id,$branch_id,$sell_id,$usr_id,$serch_date){
       
        if(!empty($serch_date)):
            $serch_date = date('Y-m-d',strtotime($serch_date));
            $data = DailySalesTargetUpselling::where('company_id',$company_id)->where('user_id',$usr_id)->where('branch_id',$branch_id)->where('sell_p_id',$sell_id)->whereDate('sales_date',$serch_date)->first();
            return $data;
        endif;
    }


    function compareByTimeStamp($time1, $time2) 
{ 
    if (strtotime($time1) < strtotime($time2)) 
        return 1; 
    else if (strtotime($time1) > strtotime($time2))  
        return -1; 
    else
        return 0; 
}

    function _displayDates($date1, $date2, $format = 'Y-m-d' ) {
        $dates = array();
        $current = strtotime($date1);
        $date2 = strtotime($date2);
        $stepVal = '+1 day';
        while( $current <= $date2 ) {
           $dates[] = date($format, $current);
           $current = strtotime($stepVal, $current);
        }
        usort($dates, "compareByTimeStamp"); 
  
        return $dates;
      }

      function _find_upsales_detail_by_date($company_id,$branch_id,$uid,$sell_id_default,$search_date){
        $search_date = date('Y-m-d',strtotime($search_date));
        $search_data = DailySalesTargetUpselling::where('company_id',$company_id)->where('user_id',$uid)
                        ->where('branch_id',$branch_id)->where('sell_p_id',$sell_id_default)
                        ->whereDate('sales_date',$search_date)->first();
        return $search_data;
      }

    function _target_total_cal_by_sell($company_id,$branch_id,$sells_id,$serch_date){
        if(!empty($serch_date)):
            $month = date('n',strtotime($serch_date));
            $data = SalesTargetMaster::where('company_id',$company_id)->where('branch_id',$branch_id)->whereIn('sell_p_id',$sells_id)->where('month',$month)->sum('per_day_price');
            return $data ?? 0;
        endif;  
    }

    function _updailytarget_total_cal_by_sell($company_id,$branch_id,$sells_id,$serch_date,$user_id,$type=''){
        if(!empty($serch_date)):
            $serch_date = date('Y-m-d',strtotime($serch_date));
            if($type=='mtd'):
                $first_date = date('Y-m-01',strtotime($serch_date));
                $data = DailySalesTargetUpselling::where('company_id',$company_id)->where('branch_id',$branch_id)->whereIn('sell_p_id',$sells_id)->whereBetween('sales_date',[$first_date,$serch_date])->where('user_id',$user_id)->sum('target_price');
            else:
                $data = DailySalesTargetUpselling::where('company_id',$company_id)->where('branch_id',$branch_id)->whereIn('sell_p_id',$sells_id)->where('sales_date',$serch_date)->where('user_id',$user_id)->sum('target_price');
            endif;
            return $data ?? 0;
        endif;
       
    }

    function _updailysale_total_cal_by_sell($company_id,$branch_id,$sells_id,$serch_date,$user_id,$type=''){
        if(!empty($serch_date)):
            $serch_date = date('Y-m-d',strtotime($serch_date));
            if($type=='mtd'):
                $first_date = date('Y-m-01',strtotime($serch_date));
                $data = DailySalesTargetUpselling::where('company_id',$company_id)->where('branch_id',$branch_id)->whereIn('sell_p_id',$sells_id)->whereBetween('sales_date',[$first_date,$serch_date])->where('user_id',$user_id)->sum('sale_price');
            else:
                $data = DailySalesTargetUpselling::where('company_id',$company_id)->where('branch_id',$branch_id)->whereIn('sell_p_id',$sells_id)->where('sales_date',$serch_date)->where('user_id',$user_id)->sum('sale_price');
            endif;
            return $data ?? 0;
        endif;
       
    }

    function _updailycc_count_cal_by_sell($company_id,$branch_id,$sells_id,$serch_date,$user_id,$type=''){
        if(!empty($serch_date)):
            $serch_date = date('Y-m-d',strtotime($serch_date));
            if($type=='mtd'):
                $first_date = date('Y-m-01',strtotime($serch_date));
                $data = DailySalesTargetUpselling::where('company_id',$company_id)->where('branch_id',$branch_id)->whereIn('sell_p_id',$sells_id)->whereBetween('sales_date',[$first_date,$serch_date])->where('user_id',$user_id)->sum('cc_count');
            else:
                $data = DailySalesTargetUpselling::where('company_id',$company_id)->where('branch_id',$branch_id)->whereIn('sell_p_id',$sells_id)->where('sales_date',$serch_date)->where('user_id',$user_id)->sum('cc_count');
            endif;
            return $data ?? 0;
        endif;
       
    }

    function _updailyscore_avg($company_id,$branch_id,$sells_id,$serch_date,$user_id,$type=''){
        if(!empty($serch_date)):
            $serch_date = date('Y-m-d',strtotime($serch_date));
            if($type=='mtd'):
                $first_date = date('Y-m-01',strtotime($serch_date));
                $data = DailySalesTargetUpselling::where('company_id',$company_id)->where('branch_id',$branch_id)->whereIn('sell_p_id',$sells_id)->whereBetween('sales_date',[$first_date,$serch_date])->where('user_id',$user_id)->avg('total_cal');
            else:
                $data = DailySalesTargetUpselling::where('company_id',$company_id)->where('branch_id',$branch_id)->whereIn('sell_p_id',$sells_id)->where('sales_date',$serch_date)->where('user_id',$user_id)->avg('total_cal');
            endif;
            return $data ?? 0;
        endif;
       
    }

    function _dailysale_total_cal($company_id,$branch_id,$sells_id,$serch_date,$type=''){
        if(!empty($serch_date)):
            $serch_date = date('Y-m-d',strtotime($serch_date));
            if($type=='mtd'):
                $first_date = date('Y-m-01',strtotime($serch_date));
                $data = StoreDailySales::where('company_id',$company_id)->where('branch_id',$branch_id)->whereIn('sell_p_id',$sells_id)->whereBetween('sales_date',[$first_date,$serch_date])->sum('achieve_target');
            else:
                $data = StoreDailySales::where('company_id',$company_id)->where('branch_id',$branch_id)->whereIn('sell_p_id',$sells_id)->whereDate('sales_date',$serch_date)->sum('achieve_target');
            endif;
            return $data;
        endif;   
    }
    

    function _dailysale_bill_avg($company_id,$branch_id,$sells_id,$serch_date,$type=''){
        if(!empty($serch_date)):
            $serch_date = date('Y-m-d',strtotime($serch_date));
            if($type=='mtd'):
                $first_date = date('Y-m-01',strtotime($serch_date));
                $data = StoreDailySales::where('company_id',$company_id)->where('branch_id',$branch_id)->whereIn('sell_p_id',$sells_id)->whereBetween('sales_date',[$first_date,$serch_date])->whereNotNull('avg_bill_count')->avg('avg_bill_count');
            else:
                $data = StoreDailySales::where('company_id',$company_id)->where('branch_id',$branch_id)->whereIn('sell_p_id',$sells_id)->whereDate('sales_date',$serch_date)->whereNotNull('avg_bill_count')->avg('avg_bill_count');
            endif;
                return $data;
        endif;
       
    }

    function _calculate_per($total_val,$achieve_val){
        $cal_val = '';
        if(!empty($total_val) && !empty($achieve_val)):
            if($achieve_val > $total_val):
                $val =  $achieve_val -  $total_val; 
                $total = $val / $total_val * 100;
                $cal_val = '<span class="success_pr">'.number_format($total,2).'%</span>';
            else:
                $val =  $total_val -  $achieve_val; 
                $total = $val / $total_val * 100;  
                if(!empty($total)):
                    $cal_val = '<span class="nega_pr">'.number_format($total,2).'%</span>';
                endif;      
            endif;
            return $cal_val;
        endif;
    }

    
    function _is_user_sale_designation($user_id){
        $emp_data = Employee::where('user_id',$user_id)->select('designation','branch','company','company')->first();
        $branch_id  = $emp_data->branch ?? '';
        $company_id  = $emp_data->company ?? '';
        $designation  = $emp_data->designation ?? '';
        $is_sales = Employee::where('branch',$branch_id)->where('company',$company_id)->where('designation',$designation)->join('designations','employees.designation','designations.id')->where('designations.is_sales','1')->first();

        if(!empty($is_sales)):
            return $is_sales;
        endif;
    }

    function _get_att_icon($userId,$attnDate){
        $emp_detail = Employee::where('user_id',$userId)->where('status','active')->first();
        $emloyeeAttendance = AttendanceDetails::where('user_id', $userId)->where('employee_id', $emp_detail->emp_generated_id)->whereDate('attendance_on', $attnDate)->where('punch_state','clockin')->first();
                                                  
        // get shift details
        $shiftDetails = Scheduling::where('employee',$emp_detail->user_id)->where('shift_on', date('Y-m-d',strtotime($attnDate)))->where('status','active')->get()->first();

        $tdValue = '';

        if(!empty($shiftDetails) && (in_array($shiftDetails->shift, array(3))))
        {
            $tdValue = 'FS';
        }
        else
        {
            if(empty($emloyeeAttendance))
            {
                
                $encoded = base64_encode(json_encode($attnDate.'/'.$emp_detail->user_id));
                $tdValue = getAttendanceText($shiftDetails,$encoded);
            }
            else
            {
                if($emloyeeAttendance->day_type === 'off' && ($emloyeeAttendance->attendance_time === '0'))
                {
                    //echo($emloyeeAttendance->id);
                    $encoded = base64_encode(json_encode($attnDate.'/'.$emp_detail->user_id));
                    $tdValue = getAttendanceText($shiftDetails,$encoded);
                }
                else
                {
                    $encoded = base64_encode(json_encode($attnDate.'/'.$emp_detail->user_id));

                    $firstclockin = AttendanceDetails::where('user_id', $emp_detail->user_id)->where('employee_id', $emp_detail->emp_generated_id)->where('punch_state', 'clockin')->whereDate('attendance_on', $attnDate)->first();
                    $lastclockout = AttendanceDetails::where('atte_ref_id', $firstclockin->id)->where('punch_state', 'clockout')->first();
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

                    
                    $tdValue = '<a href="javascript:void(0);" class="popupAttn" data-id="'.$encoded.'"><i class="fa '.$shicon.' '.$shcolor.'"></i></a>';
                }
            }
        }
        return $tdValue;
    }

    function _get_company_name_by_uroles($urole_id,$type=''){

        if($type=='name'):
            $data = UserRoles::where('user_roles.parent_id',$urole_id)->orWhere('user_roles.id',$urole_id)->join('residencies','user_roles.company_id','residencies.id')->select('residencies.name')->get();
            return $data;
        else:
            $data = UserRoles::where('parent_id',$urole_id)->orWhere('id',$urole_id)->selectRaw('GROUP_CONCAT(company_id) as ids')->first();
            $ids = $data->ids ?? '';
            $explode_ids = explode(',',$ids);
            return $explode_ids;
        endif;
        
    }

    function _get_branch_by_uroles($urole_id){
        $data = UserRoles::where('parent_id',$urole_id)->orWhere('id',$urole_id)->selectRaw('GROUP_CONCAT(branch_id) as ids')->first();
        $ids = $data->ids ?? '';
        $explode_ids = explode(',',$ids);
        return $explode_ids;
        
    }
    

    

    
    


    
    
