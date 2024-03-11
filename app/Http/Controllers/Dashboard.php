<?php

namespace App\Http\Controllers;
use DB, Carbon\Carbon;
use Session, PDF;
use App\Models\Scheduling;
use Illuminate\Http\Request;
use App\Models\Leaves;
use App\Models\EmployeeMonthlySalary;
use App\Models\EmployeeSalaryHistory;
use App\Models\Employee;
use App\Models\Indemnity;
use App\Models\EmployeeIndemnity;
use App\Models\AttendanceDetails;
use App\Models\Departments;
use App\Models\Shifting;
use App\Models\UpSellingPeriod;
use App\Models\DailySalesTargetUpselling;

class Dashboard extends Controller
{
    public function index(Request $request)
    {
        $lastclockout = [];
        $search = [];
        
        $current_date = Carbon::today();
        $search['search_date'] = $request->search_date ?? date('d-m-Y'); 
        $after_date = Carbon::today()->addDay(7);
        $user_id  = Session::get('user_id');
        //$sched_data = Scheduling::whereDate('shift_on', '>=', date($current_date))->whereDate('shift_on', '<', date($after_date))->where('employee',$user_id)->get();
        $sched_data   = Employee::with('schedules')->where('user_id',$user_id)->get();
        $balance_annual_leave_total = getAnnualLeaveDetails($user_id);
        $annual_leave_list = Leaves::where('user_id',$user_id)->where('leave_type','1')->where('leave_status','approved')->orderBy('id','desc')->limit(4)->get();
        $balance_sick_leave_total = getSickLeaveDetails($user_id);
        $sick_leave_list = Leaves::where('user_id',$user_id)->where('leave_type','2')->where('leave_status','approved')->orderBy('id','desc')->limit(4)->get();
        $user = Employee::with([
            "employee_accounts", "employee_contacts", "employee_details","employee_company","employee_designation",
            "employee_education", "employee_education", "employee_experiences", "employee_loan", "employee_salary","employee_document", "employee_leaves", "employee_branch"
        ])->where("user_id", $user_id)->first();
        $salaryDetails = EmployeeMonthlySalary::where('emp_id', $user_id)->where(array('es_month'=>date('m'), 'es_year' => date('Y')))->first();
        $additions = 0;$deductions = 0;
        $totsalary = $salaryDetails->total_salary ?? 0;
        if(!empty($salaryDetails))
        {
            $additions = EmployeeSalaryHistory::where('entry_type', 'addition')->where('ems_id', $salaryDetails->id)->where('status','active')->sum('entry_value');
            $deductions = EmployeeSalaryHistory::where('entry_type', 'deduction')->where('ems_id', $salaryDetails->id)->where('status','active')->sum('entry_value');
        }
        $annualleavedetails = getAnnualLeaveDetails($user_id);
        $public_holidays_amount = $user->public_holidays_amount ?? 0;
        $totIndemnity = $this->calculateIndemnity($user_id);
        $indemnityDetails = EmployeeIndemnity::where('user_id', $user_id)->first();
        $total_overtime_salary = (isset($salaryDetails->total_overtime_salary) && $salaryDetails->total_overtime_salary >0)?$salaryDetails->total_overtime_salary:0;
        $totpayable = ($totsalary + $additions + $total_overtime_salary + $totIndemnity + $annualleavedetails['leaveAmount'] + $public_holidays_amount) - $deductions;
        //for attendance details
        $attnDate = date('Y-m-d');
        $get_last_row = AttendanceDetails::where('user_id', $user_id)->orderBy('id','desc')->value('punch_state');
        if($get_last_row == 'clockout'):
            $firstclockin = AttendanceDetails::where('user_id', $user_id)->where('punch_state', 'clockin')->whereDate('attendance_on', $attnDate 
                            )->first();
        else:
            $firstclockin = AttendanceDetails::where('user_id', $user_id)->orderBy('id','desc')->first();
        endif;

        if(!empty($firstclockin)):
            $lastclockout = AttendanceDetails::where('atte_ref_id', $firstclockin->atte_ref_id)->where('punch_state', 'clockout')->first();
        endif;
        $holidayWork = AttendanceDetails::select('a.user_id', 'a.attendance_on','h.holiday_day', 'h.holiday_date','h.title', 'sh.shift')
                        ->from('attendance_details as a')
                        ->join('holidays as h', 'a.attendance_on', '=', 'h.holiday_date')
                        ->leftJoin('scheduling as sh', function ($join) use ($user_id) {
                            $join->on('sh.employee', '=', 'a.employee_id')
                                 ->on('a.attendance_on', '=', 'sh.shift_on');
                        })
                        ->where('a.user_id', $user_id)
                        ->where('a.day_type', 'work')
                        ->groupBy('a.user_id', 'a.attendance_on','h.holiday_day', 'h.holiday_date','h.title', 'sh.shift')
                        ->get();
        $depwhere = array('status' => 'active');
        $department   = Departments::where($depwhere)->get();
        $shifts       = Shifting::where('status', 'active')->get();
        //sales data
        $branch_id  = $user->branch ?? '';
        $company_id  = $user->company ?? '';
        $uid  = $user->id ?? '';
        $sell_id_default = UpSellingPeriod::where('company_id',$company_id)->where('is_show','1')->where('branch_id',$branch_id)->orderBy('id','asc')->pluck('id')->join(',');
        $sell_id_default = explode(',',$sell_id_default);
        $daily_target = _updailytarget_total_cal_by_sell($company_id,$branch_id,$sell_id_default,$search['search_date'],$uid);
        $mtd_target = _updailytarget_total_cal_by_sell($company_id,$branch_id,$sell_id_default,$search['search_date'],$uid,'mtd');
        $daily_sale = _updailysale_total_cal_by_sell($company_id,$branch_id,$sell_id_default,$search['search_date'],$uid);
        $mtd_sale = _updailysale_total_cal_by_sell($company_id,$branch_id,$sell_id_default,$search['search_date'],$uid,'mtd');
        $daily_cc = _updailycc_count_cal_by_sell($company_id,$branch_id,$sell_id_default,$search['search_date'],$uid);
        $mtd_cc = _updailycc_count_cal_by_sell($company_id,$branch_id,$sell_id_default,$search['search_date'],$uid,'mtd');
        $daily_score = _updailyscore_avg($company_id,$branch_id,$sell_id_default,$search['search_date'],$uid);
        $mtd_score = _updailyscore_avg($company_id,$branch_id,$sell_id_default,$search['search_date'],$uid,'mtd');
        //search sdaily sale data
        $serch_date = date('Y-m-d',strtotime($search['search_date']));
        $from_date = date('Y-m-d', strtotime($serch_date . " -3 days"));
        $date_list = _displayDates($from_date,$serch_date);
        return view('dashboard',compact('company_id','uid','branch_id','date_list','sched_data','search','user_id','balance_annual_leave_total','annual_leave_list','balance_sick_leave_total','sick_leave_list','totpayable','firstclockin','lastclockout','holidayWork','user','indemnityDetails','department','shifts','daily_target','mtd_target','daily_sale','mtd_sale','daily_cc','mtd_cc','daily_score','mtd_score','sell_id_default'));
    }  

    public function search_sales(Request $request){
        $search_date = $request->date_val ?? date('d-m-Y');
        $user_id  = Session::get('user_id');
        $user = Employee::where('user_id',$user_id)->first();
        $branch_id  = $user->branch ?? '';
        $company_id  = $user->company ?? '';
        $uid  = $user->id ?? '';
        $sell_id_default = unserialize($request->sell_id_default);
         //search sdaily sale data
         $serch_date = date('Y-m-d',strtotime($search_date));
         $from_date = date('Y-m-d', strtotime($serch_date . " -3 days"));
         $date_list = _displayDates($from_date,$serch_date);
         $search['search_date'] = $search_date;
         $pass_array=array(
			'date_list' => $date_list,
            'search' => $search,
            'company_id'=>$company_id,
            'branch_id'=>$branch_id,
            'uid'=>$uid,
            'sell_id_default' => $sell_id_default,
        );
         $html =  view('up_selling_management.search_modal', $pass_array )->render();

         $arr = [
             'success' => 'true',
             'html' => $html
         ];
         return response()->json($arr);
        }

    public function calculateIndemnity($user_id)
    {
        $employee = Employee::with('employee_salary', 'employee_salary_details')->where('user_id', $user_id)->get();

        $perDaySalary = (isset($employee[0]) && isset($employee[0]->employee_salary->basic_salary))? ($employee[0]->employee_salary->basic_salary)/26 :0;
        // echo '--------basic_salary:perDaySalary-----------<br>';
        // echo $employee[0]->employee_salary->basic_salary.':'.$perDaySalary.'<br>';
        $today = date('Y-m-d');
        $joinedOn = (isset($employee[0]->joining_date))?date('Y-m-d',strtotime(str_replace('/','-',$employee[0]->joining_date))):$today;
        // echo '--------Dates-----------<br>';
        // echo 'joinedOn-'.$joinedOn.'<br>';echo 'today-'.$today.'<br>';
        //diff b/w jod and today
        $diff = getDateDiff($joinedOn, $today, 1);
        $totalDays = $diff;
        $totalYears = round($totalDays/365,2);
        $totalMonths = round(($totalDays / 365) * 365/(365/12),1);
        // echo 'total days-'.$totalDays.'<br>';
        // echo 'total years-'.$totalYears.'<br>';
        // echo 'total Months-'.$totalMonths.'<br>';
        // echo 'full diff - '.$diff.'<br>';exit;
        $diffExplode = explode('.', $totalYears);
        // echo "$diffExplode[0] years $diffExplode[1] months".'<br>';

        $indemnityDetails = Indemnity::get();
        // echo '<pre>';print_r($indemnityDetails);
        // echo '--------Values test-----------<br>';
        if(isset($indemnityDetails))
        { 
                // echo '......indemnity exists......<br>';
            if($diffExplode[0] >= $indemnityDetails[0]->min_year)
            {
                // echo '--------Eligible for Indemnity-----------<br>';
                // echo 'Earned years - '.$totalYears.'- first min year - '.$indemnityDetails[0]->min_year.'<br>';
                $finalIndemnity = 0;
                foreach($indemnityDetails as $key => $id)
                {
                    // echo '<br>';
                    // echo '................here Starts.....'.$key.'...............<br>';
                    if($diffExplode[0] >= $id->min_year)
                    {
                        // echo '<br>';
                        // echo 'Eligible for Indemnity '.$id->min_year.'-'.$id->max_year.'<br>';
                        // echo 'Earned years - '.$diffExplode[0].'<br>';
                        $monthsEarned = $totalMonths;//($diffExplode[0] * 12 ) + $diffExplode[1];
                        // echo 'Earned months - '.$monthsEarned.'<br>';
                        $minMonthsEligible = ($id->min_year > 0)?12 * $id->min_year:$monthsEarned;
                        // echo 'Min months Eligible - '.$minMonthsEligible.'<br>';
                        $maxMonthsEligible = ($id->max_year > 0)?12 * $id->max_year:$monthsEarned;
                        // echo 'Max months Eligible - '.$maxMonthsEligible.'<br>';

                        if($key==0)//3-5
                        {
                            $monthsEligible = ($monthsEarned > $maxMonthsEligible)? $minMonthsEligible + ($maxMonthsEligible - $minMonthsEligible) : $monthsEarned;
                        }
                        else
                        {
                            $monthsEligible = ($monthsEarned > $maxMonthsEligible)? $maxMonthsEligible - $minMonthsEligible : $monthsEarned - $minMonthsEligible;
                        }
                        // echo 'Months Eligible - '.$monthsEligible.'<br>';
                        // $monthsEligible = $monthsEarned - $maxMonthsEligible;
                        // echo '--------Months details-----<br>';
                        // echo $maxMonthsEligible.':'.$monthsEarned.':'.$monthsEligible.'<br>';

        //                 // //months between
        //                 // $eligibleMonths_min = ($id->min_year > 0)?12 * $id->min_year:0;
        //                 // $eligibleMonths_max = ($id->max_year > 0)?12 * $id->max_year:$monthsEarned;
        //                 // $monthsTotake = $eligibleMonths_max - $eligibleMonths_min;
        //                 // echo 'eligibleMonths_min-'.$eligibleMonths_min.':eligibleMonths_max-'.$eligibleMonths_max.':monthsTotake-'.$monthsTotake.'<br>';
        //                 // if($monthsEligible >= 0)
        //                 // {
                            $indemnityEarned = $monthsEligible * $perDaySalary * $id->indemnity_amount;
        //                 //     $monthsTaken = $maxMonthsEligible;
        //                 // }
        //                 // else
        //                 // {
        //                 //     $monthsTaken = ($monthsEligible <0)?$monthsEligible *-1:$monthsEligible;
        //                 //     $indemnityEarned = $monthsTaken * $perDaySalary * $id->indemnity_amount;
        //                 //     // $monthsTaken = $monthsEligible;
        //                 // }
        //                 // echo '$monthsTaken-'.$monthsTaken.'<br>';
                        // echo '--------Earnings details-----<br>';
                        // echo $indemnityEarned.':'.$perDaySalary.':'.$id->indemnity_amount.'<br>';

                        // echo '--------Earnings details : percentage-----<br>';
                        if($monthsEligible < $monthsEarned)
                        { 
                            $per = 100;
                            $finalIndemnity += round($indemnityEarned,6);
                            // echo $finalIndemnity;
                            if($monthsEarned < $maxMonthsEligible)
                            {
                                $per = $id->percentage_ia;
                                $finalIndemnity = ($finalIndemnity/100) * $id->percentage_ia;
                            }
                        }
                        else
                        { 
                            $per = $id->percentage_ia;
                            $finalIndemnity += (round($indemnityEarned,6)/100) * $id->percentage_ia;
                        }


                        // echo $finalIndemnity;
                        //check if indemnity already generated
                        $checkIndemnityarray = array(
                            'user_id'           =>  $user_id,
                            'status'            =>  'active'
                        );
                        $checkIndemnityCount = EmployeeIndemnity::where($checkIndemnityarray)->get();
                        $IndemnityId = 0;
                        if($checkIndemnityCount->count() > 0)
                        {
                            $IndemnityId = $checkIndemnityCount[$checkIndemnityCount->count()-1]->id;
                        }
                        $insertArray = array(
                            'user_id'           =>  $user_id,
                            'joined_on'         =>  $joinedOn,
                            'today_date'        =>  $today,
                            'years_diff'        =>  $diff,
                            'months_earned'     =>  $monthsEarned,
                            'max_months_eligible'   =>  $maxMonthsEligible,
                            'months_taken'      =>  $monthsEligible,
                            'indemnity_amount'  =>  $id->indemnity_amount,
                            'indemnity_perc'    =>  $per,//$id->percentage_ia,
                            'current_salary'    =>  (isset($employee[0]) && isset($employee[0]->employee_salary->basic_salary))?$employee[0]->employee_salary->basic_salary:0,
                            'month_days'        =>  26,
                            'perday_salary'     =>  $perDaySalary,
                            'total_months'      =>  $monthsEarned,
                            'total_amount'      =>  $finalIndemnity,
                            'created_at'        =>  date('Y-m-d h:i:s'),
                            'status'            =>  'active');
                        if($IndemnityId > 0)
                        {
                            EmployeeIndemnity::where('id', $IndemnityId)->update($insertArray);
                        }
                        else
                        {
                            EmployeeIndemnity::insertGetId($insertArray);
                        }
                        
                        // echo '<br>';
                    }
                }
            }
            else
            {
                $finalIndemnity = 0;
            }
            return $finalIndemnity;
        }
        
        return null;
    }

    public function download_fnf_pdf(Request $request){
        $this->calculateIndemnity($request->u_id);
        $additions = '';$deductions = '';
        $salaryDetails = EmployeeMonthlySalary::where('emp_id', $request->u_id)->where(array('es_month'=>date('m'), 'es_year' => date('Y')))->first();
        if(!empty($salaryDetails))
        {
            $additions = EmployeeSalaryHistory::where('entry_type', 'addition')->where('ems_id', $salaryDetails->id)->where('status','active')->get();
            $deductions = EmployeeSalaryHistory::where('entry_type', 'deduction')->where('ems_id', $salaryDetails->id)->where('status','active')->get();
        }
        $indemnityDetails = EmployeeIndemnity::where('user_id', $request->u_id)->get();
        $annualleavedetails = getAnnualLeaveDetails($request->u_id);
        $employee = Employee::with([
            "employee_accounts", "employee_contacts", "employee_details","employee_company","employee_designation",
            "employee_education", "employee_education", "employee_experiences", "employee_loan", "employee_salary","employee_document", "employee_leaves", "employee_branch"
        ])->where("user_id", $request->u_id)->first();
        $user_img = ($employee->profile!=null)? url('uploads/profile/'.$employee->profile): url('assets/img/profiles/avatar.png');

        $pass_array = array(
            "additions" => $additions,
            "deductions"=>$deductions,
            "indemnityDetails"=>$indemnityDetails,
            "salaryDetails"=>$salaryDetails,
            "annualleavedetails"=>$annualleavedetails,
            "user"=>$employee,
            "user_img"=>$user_img,
        );
        $cdate = date('Y-m-d');
        $rname = $cdate.'_fnf.pdf';
        $pdf = PDF::loadView('edbr.fnf_pdf', $pass_array)->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->download($rname);
    }
    
}
