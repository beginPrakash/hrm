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

function calculateSalaryByFilter($user_id,$empid,$mid,$year)
{
    $total_calculate_salary = 0;
    if(!empty($mid)):
        $commonWorkingHoursDetails = Overtime::first();
        $commonWorkingHours = $commonWorkingHoursDetails->working_hours - 1;
        $commonWorkingDays = $commonWorkingHoursDetails->working_days;
        $total_schedule_hours = AttendanceDetails::whereRaw('MONTH(attendance_on) = '.$mid)->whereRaw('YEAR(attendance_on) = '.$year)->where('employee_id',$empid)->sum('schedule_hours');
        //dd($total_schedule_hours);
        //find employee salary details
        $employee = Employee::where('user_id',$user_id)->where('emp_generated_id',$empid)->first();
        //calculate salary now
        if(!empty($employee)):
        $currentMonthSalary = (isset($employee->employee_salary))?$employee->employee_salary->basic_salary:0;
        $daySalary = ($currentMonthSalary>0)?($currentMonthSalary/$commonWorkingDays):0;
        $hourlySalary = ($daySalary>0)?($daySalary/$commonWorkingHours):0;
        $total_calculate_salary = $total_schedule_hours * $hourlySalary;
    endif;
    return $total_calculate_salary;
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

    function get_total_hours($start_time,$end_time){
        $start  = new \Carbon\Carbon($start_time);
        $end    = new \Carbon\Carbon($end_time);
        $time = $start->diff($end)->format('%H.%I');
        return $time;
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
        $encoded = base64_encode(json_encode($attnDate.'/'.$attnUserId));

        $firstclockin = AttendanceDetails::where('user_id', $attnUserId)->where('punch_state', 'clockin')->whereDate('attendance_on', $attnDate 
        )->first();
        $lastclockout = AttendanceDetails::where('user_id', $attnUserId)->where('punch_state', 'clockout')->whereDate('attendance_on', $attnDate 
        )->limit(1)->orderBy('id', 'desc')->first();
        // get shift details
        $shiftDetails = Scheduling::where('employee',$attnUserId)->where('shift_on', date('Y-m-d',strtotime($attnDate)))->where('status','active')->first();
        $shcolor = '';
        $shicon = '';
        $flag = 0;

        $minStartTime_24 = (isset($shiftDetails->min_start_time))?date('H:i', strtotime($shiftDetails->min_start_time)):'0';
        $maxStartTime_24 = (isset($shiftDetails->max_start_time))?date('H:i', strtotime($shiftDetails->max_start_time)):'0';
        if(isset($firstclockin->attendance_time) && checkDateTimeInBetween($firstclockin->attendance_time, $minStartTime_24, $maxStartTime_24)==2)
        {
            $flag = 1;
        }

        if((empty($firstclockin) || empty($lastclockout)) || ($firstclockin->attendance_time ==='0' || $lastclockout->attendance_time ==='0'))
        {
            $flag = 1;
        }

        if(empty($shiftDetails))
        {
            $flag = 1;
        }
        return $flag;
    }

    function save_schedule_overtime_hours($user_id,$att_date,$start_time,$end_time){
        $start_time = _convert_time_to_12hour_format($start_time);
        $end_time = _convert_time_to_12hour_format($end_time);
        $shiftDetails = Scheduling::where('employee', $user_id)->where('shift_on', $att_date)->where('status', 'active')->first();
        $commonWorkingHoursDetails = Overtime::first();
        $commonWorkingHours = $commonWorkingHoursDetails->working_hours;
        if(!empty($shiftDetails)):
            $schedule_start_time = _convert_time_to_12hour_format($shiftDetails->start_time);
            $schedule_end_time = _convert_time_to_12hour_format($shiftDetails->end_time);
            $total_schedule_hours = get_total_hours($schedule_start_time,$schedule_end_time);
            $total_attendanace_hours = get_total_hours($start_time,$end_time);
            $final_diff = 0;
            $total_attendanace_hours = floatval($total_attendanace_hours);
            $commonWorkingHours = (int)$commonWorkingHours;
           // dd($total_attendanace_hours);
             if($total_attendanace_hours > $commonWorkingHours):
            //     dd('sds');
                $diff = $total_attendanace_hours - $commonWorkingHours;
                $final_diff =  $diff < 0 ? (-1) * $diff : $diff;
            endif;
            //save schedule hours and overtime data in attanedance detail yable
            $save_data = AttendanceDetails::where('user_id',$user_id)->where('attendance_on',$att_date)->where('punch_state','clockin')->first();
            $save_data->schedule_hours = $commonWorkingHours-1;
            $save_data->overtime_hours = $final_diff;
            $save_data->save();
            return true;
        endif;
    }
