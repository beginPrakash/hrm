<?php

namespace App\Http\Controllers;
use DB;
use Session;
use DateTime;
use App\Models\Employee;
use App\Models\PayrollSettings;
use App\Models\EmployeeMonthlySalary;
use App\Models\EmployeeSalaryHistory;
use App\Models\EmployeeIndemnity;
use App\Models\FinancialYear;
use App\Models\Overtime;
use App\Models\Holidays;
use App\Models\Indemnity;
use App\Models\EmployeeLoan;
use App\Models\AttendanceDetails;
use App\Http\Controllers\Auth;
use App\Http\Controllers\EmployeeController;
// use App\Helper\Helper;

use Illuminate\Http\Request;


class PayrollController extends Controller
{
    public function __construct()
    {
        $this->title = 'Payroll';
    }

    public function employee_salary()
    {
        
        $title = $this->title;

        $year = '';
        $month = '';
        $empname = '';

        $where = array();
        $employees = Employee::with('employee_salary');
        if(isset($_POST['search']))
        {
            if(isset($_POST['year']) && $_POST['year']!='')
            {
                $year = $_POST['year'];
            }
            if(isset($_POST['month']) && $_POST['month']!='')
            {
                $month = $_POST['month'];
            }
            if(isset($_POST['employee']) && $_POST['employee']!='')
            {
                $empname = $_POST['employee'];
                $employees = $employees->where('status', 'active')->where('first_name', 'like', '%'.$_POST['employee'].'%')
                            ->orWhere('last_name', 'like', '%'.$_POST['employee'].'%')
                            ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)") , 'like', '%'.$_POST['employee'].'%');
            }
        }

        $breadButton = 0;
        // $month = 07;//date('m');
        $salaryDetails = EmployeeMonthlySalary::with('employees', 'employee_designation', 'employee_residency')->where('salary_type','salary')->where('es_month',$month)->where('es_year',$year)->where('status','active')->get();
        // $employees = Employee::with(["employee_designation", "employee_salary_details", "employee_residency"])->where('status','active')->get();
        $additions = PayrollSettings::where('settings_type', 'addition')->where('status','active')->get();
        $deductions = PayrollSettings::where('settings_type', 'deductions')->where('status','active')->get();
        // echo '<pre>';print_r($salaryDetails);exit;
        $salaryCount = EmployeeMonthlySalary::where('salary_type','salary')->where('es_month',$month)->where('status','active')->get()->count();
        $employees = $employees->where('status', 'active')->get();
        return view('payroll.employee_salary', compact('title', 'salaryDetails', 'additions', 'deductions', 'breadButton', 'salaryCount', 'year', 'month','employees','empname'));
    }

    public function employee_salary_details(Request $request)
    {
        $title = $this->title;
        $employees = EmployeeMonthlySalary::where('emp_id',$request->id)->where('status','active')->get();
        return view('payroll.employee_salary_details', compact('title', 'employees'));
    }

    public function items()
    {
        $title = $this->title.' Items';
        $employees = Employee::where('status','active')->get();
        $additions = PayrollSettings::where('settings_type', 'addition')->where('status','active')->get();
        $overtime = PayrollSettings::where('settings_type', 'overtime')->where('status','active')->get();
        $deductions = PayrollSettings::where('settings_type', 'deductions')->where('status','active')->get();
        return view('payroll.payroll_items', compact('title', 'employees', 'additions', 'overtime', 'deductions'));
    }

    public function additionInsert(Request $request)
    {
        $insertArray = array(
            'settings_type'    =>  $request->settings_type,
            'name'             =>  $request->name,
            'category'         =>  $request->category,
            'is_unit'          =>  (isset($request->is_unit))??'0',
            'unit_amount'      =>  $request->unit_amount,
            'assignee_type'    =>  $request->deduction_assignee,
            'assignee'         =>  (isset($request->employees))?implode(',',$request->employees):'',
            'created_at'       =>  date('Y-m-d h:i:s'),
            'status'           =>  'active'
        );

        PayrollSettings::insert($insertArray);
        return redirect('/payroll-items')->with('success','Addition added successfully!');
    }

    public function additionUpdate(Request $request)
    {
        $updateArray = array(
            'settings_type'    =>  $request->settings_type,
            'name'             =>  $request->name,
            'category'         =>  $request->category,
            'is_unit'          =>  (isset($request->is_unit))??'0',
            'unit_amount'      =>  $request->unit_amount,
            'assignee_type'    =>  $request->edit_addition_assignee,
            'assignee'         =>  (isset($request->employees))?implode(',',$request->employees):'',
            'updated_at'       =>  date('Y-m-d h:i:s'),
            'status'           =>  'active'
        );

        PayrollSettings::where('id', $request->adi_id)->update($updateArray);
        return redirect('/payroll-items')->with('success','Addition updated successfully!');
    }

    public function additionDelete(Request $request)
    {
        $deleteArray = array(
            'status' => 'inactive',
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        PayrollSettings::where('id', $request['adi_delete_id'])->update($deleteArray);
        return redirect('/payroll-items')->with('success','Addition deleted successfully!');
    }

    public function overtimeSettingsInsert(Request $request)
    {
        $insertArray = array(
            'settings_type'    =>  $request->settings_type,
            'name'             =>  $request->name,
            'rate_type'        =>  $request->rate_type,
            'rate'             =>  (isset($request->rate))?$request->rate:'',
            'created_at'       =>  date('Y-m-d h:i:s'),
            'status'           =>  'active'
        );

        PayrollSettings::insert($insertArray);
        return redirect('/payroll-items')->with('success','Overtime added successfully!');
    }

    public function overtimeSettingsUpdate(Request $request)
    {
        $updateArray = array(
            'settings_type'    =>  $request->settings_type,
            'name'             =>  $request->name,
            'rate_type'        =>  $request->rate_type,
            'rate'             =>  (isset($request->rate))?$request->rate:'',
            'updated_at'       =>  date('Y-m-d h:i:s'),
            'status'           =>  'active'
        );

        PayrollSettings::where('id', $request->ovt_id)->update($updateArray);
        return redirect('/payroll-items')->with('success','Overtime updated successfully!');
    }

    public function overtimeSettingDelete(Request $request)
    {
        $deleteArray = array(
            'status' => 'inactive',
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        PayrollSettings::where('id', $request['ovt_delete_id'])->update($deleteArray);
        return redirect('/payroll-items')->with('success','Overtime deleted successfully!');
    }

    public function deductionInsert(Request $request)
    {
        $insertArray = array(
            'settings_type'    =>  $request->settings_type,
            'name'             =>  $request->name,
            'is_unit'          =>  (isset($request->is_unit))??'0',
            'unit_amount'      =>  $request->unit_amount,
            'assignee_type'    =>  $request->deduction_assignee,
            'assignee'         =>  (isset($request->employees))?implode(',',$request->employees):'',
            'created_at'       =>  date('Y-m-d h:i:s'),
            'status'           =>  'active'
        );

        PayrollSettings::insert($insertArray);
        return redirect('/payroll-items')->with('success','Deduction added successfully!');
    }

    public function deductionUpdate(Request $request)
    {
        $updateArray = array(
            'settings_type'    =>  $request->settings_type,
            'name'             =>  $request->name,
            'is_unit'          =>  (isset($request->is_unit))??'0',
            'unit_amount'      =>  $request->unit_amount,
            'assignee_type'    =>  $request->deduction_assignee,
            'assignee'         =>  (isset($request->employees))?implode(',',$request->employees):'',
            'updated_at'       =>  date('Y-m-d h:i:s'),
            'status'           =>  'active'
        );

        PayrollSettings::where('id', $request->ded_id)->update($updateArray);
        return redirect('/payroll-items')->with('success','Deduction added successfully!');
    }

    public function deductionDelete(Request $request)
    {
        $deleteArray = array(
            'status' => 'inactive',
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        PayrollSettings::where('id', $request['ded_delete_id'])->update($deleteArray);
        return redirect('/payroll-items')->with('success','Deduction deleted successfully!');
    }

    public function salary_add_ded(Request $request)
    {
        $id = $request->salary_id;
        $details = EmployeeMonthlySalary::where('id', $id)->first();

        $updateArray = array(
            'updated_at'       =>   date('Y-m-d h:i:s')
        );

        $change_amount = $request->change_amount;
        if($request->addition_deduction==1)
        {
            $addDedId = $request->addition_drop;
            $updateArray['additions'] = $change_amount;
            $total_salary = $details->total_salary + $change_amount;
        }
        else
        {
            $addDedId = $request->deduction_drop;
            $updateArray['deductions'] = $change_amount;
            $total_salary = $details->total_salary - $change_amount;
        }
        $updateArray = array(
            'total_salary'     =>   round($total_salary,2)
        );

        EmployeeMonthlySalary::where("id", $id)->update($updateArray);

        $refDetails = PayrollSettings::where('id', $addDedId)->first();
        $refTitle = ucwords($refDetails->name);
        $addDed = ($request->addition_deduction==1)?'addition':'deduction';
        //insert into salary history table
        $insertESHArray = array(
            'ems_id'        =>  $id,
            'user_id'       =>  $details->emp_id,
            'entry_type'    =>  $addDed,
            'entry_value'   =>  $change_amount,
            'entry_type_title' => ($request->addition_deduction==1)?'add':'ded',
            'remarks'       =>  ucfirst($addDed).' on '.$refTitle.' Reference No: '.$addDedId,
            'created_at'    =>  date('Y-m-d h:i:s'),
            'status'        =>  'active');
        EmployeeSalaryHistory::insert($insertESHArray);
        return redirect('/employee-salary')->with('success','Deduction deleted successfully!');
    }

    public function generate_salary_slip(Request $request)
    {
        $title = $this->title;
        $salaryDetails = EmployeeMonthlySalary::where('id', $request->id)->first();
        $employees = Employee::with(["employee_designation", "employee_salary_details", "employee_residency"])->where('user_id', $salaryDetails->emp_id)->where('status','active')->get();
        // echo '<pre>';print_r($employees);exit;
        return view('payroll.employee_salary_slip', compact('title', 'salaryDetails', 'employees'));
    }

    public function generateFnf(Request $request)
    {
        $user_id = $request->id;
        $status = isset($request->status)?$request->status:0;
        if($status==0){
        //set employee as inactive
            $empArray = array(
                'status' => 'resigned',
                'resigned_date' => date('Y-m-d')
            );
             Employee::where('user_id', $user_id)->update($empArray);
        }
       

        //generate salary and overtime till date
        //get current financial year
        $currentFY = FinancialYear::where('status', 'active')->first();
        $currentDay = date('d');
        $currentMonth = date('m');
        $currentYear = date('Y');
        $totalMonthDays = date('t');

        //get working hours
        $commonWorkingHoursDetails = Overtime::get();
        $commonWorkingHours = (isset($commonWorkingHoursDetails[0]) && isset($commonWorkingHoursDetails[0]->working_hours))?$commonWorkingHoursDetails[0]->working_hours:9;
        //get no of holidays of current month
        $holidaysDetails = Holidays::whereYear('holiday_date', '=', $currentYear)
              ->whereMonth('holiday_date', '=', $currentMonth)
              ->get();
        $noOfHolidays = (isset($holidaysDetails))?count($holidaysDetails):0;

        //get no of fridays of current month
        $noOfFridays = noOfFridays($currentMonth, $currentYear);
        
        $month_w_days = $totalMonthDays - ($noOfHolidays + $noOfFridays);

        $employees = Employee::with('employee_salary')->where('user_id', $user_id)->get();
        if(isset($employees))
        {
            foreach($employees as $emp)
            {
                $currentMonthSalary = (isset($emp->employee_salary))?$emp->employee_salary->basic_salary:0;
                $daySalary = ($currentMonthSalary>0)?($currentMonthSalary/$month_w_days):0;
                $hourlySalary = ($daySalary>0)?($daySalary/8):0;
                /******Deductions******/
                //step - 1: loan
                $loanDeduction = 0;
                $loanDetails = EmployeeLoan::where('emp_id', $emp->user_id)->where('status', 'active')->first();
                $loanDeductionRNo = 0;
                if(!empty($loanDetails) && $loanDetails->install_pending > 0)
                {
                    $loanDeduction = $loanDetails->install_amount * $loanDetails->install_pending;
                    $loanDeductionRNo = $loanDetails->id;
                }

                //step - 2:salary
                $timeDiff = array();
                $timeDiffWork = array();
                $timeDiffOvertime = array();
                for($i=1; $i<=$currentDay; $i++)
                {
                    $date = $currentYear."-".$currentMonth."-".$i;
                    $firstclockin = AttendanceDetails::where('user_id', $emp->user_id)->where('punch_state', 'clockin')->whereDate('attendance_on', $date)->first();
                    $lastclockout = AttendanceDetails::where('user_id', $emp->user_id)->where('punch_state', 'clockout')->whereDate('attendance_on', $date)->limit(1)->orderBy('id', 'desc')->first();

                    // date_default_timezone_set("Asia/Kuwait");
                    if($firstclockin!=null && $lastclockout!=null)
                    {
                        $totaltime = getTimeDiff($firstclockin->attendance_time, $lastclockout->attendance_time);
                        $timeDiff[$i] = $totaltime;
                        $timeDiffWork[$i] = $totaltime;
                        $timeDiffOvertime[$i] = 0;
                        if(strtotime($totaltime) > strtotime($commonWorkingHours.':00:00'))
                        { 
                            $diff = date('H:i:s', strtotime($totaltime. ' -'.$commonWorkingHours.' hours')); 
                            $timeDiffWork[$i] = $commonWorkingHours.":00";
                            $timeDiffOvertime[$i] = $diff;
                        }
                    }
                }//echo '<pre>';print_r($timeDiff);echo '<pre>';print_r($timeDiffWork);echo '<pre>';print_r($timeDiffOvertime);
                //sum timediff to get total hours
                $totalWorkinghours = addTimeDiff($timeDiffWork);
                $totalOvertimehours = addTimeDiff($timeDiffOvertime);
                //calculate hourly salary
                list($h, $m) = explode(':',$totalWorkinghours);  //Split up string into hours/minutes
                $decimal = $m/60;  //get minutes as decimal
                $hoursAsDecimal = $h+$decimal;
                $totalSalary=$hoursAsDecimal*$hourlySalary;

                //calculate hourly salary for overtime
                list($oh, $om) = explode(':',$totalOvertimehours);  //Split up string into hours/minutes
                $odecimal = $om/60;  //get minutes as decimal
                $ohoursAsDecimal = $oh+$odecimal;
                $totalOvertimeSalary=$ohoursAsDecimal*$hourlySalary;

                //salary after deduction
                // $finalSalary = $totalSalary - $loanDeduction;
                // echo '<pre>';print_r($deductionDetails);
                //check if already generated
                $checkSalaryarray = array(
                    'emp_id'           =>   $emp->user_id,
                    'es_month'         =>   $currentMonth,
                    'es_year'          =>   $currentYear,
                    'salary_type'      =>   'salary',
                    'status'           =>   'active'
                );
                $checkSalaryCount = EmployeeMonthlySalary::where($checkSalaryarray)->get();
                $salaryId = 0;
                if($checkSalaryCount->count() > 0)
                {
                    $salaryId = $checkSalaryCount[$checkSalaryCount->count()-1]->id;
                }
                $insertArray = array(
                    'residency_id'     =>   $emp->company,
                    'branch_id'        =>   $emp->branch,
                    'financial_year'   =>   $currentFY->id,
                    'emp_id'           =>   $emp->user_id,
                    'es_month'         =>   $currentMonth,
                    'es_year'          =>   $currentYear,
                    'month_w_days'     =>   $month_w_days,
                    'month_holidays'   =>   $noOfHolidays,
                    'month_fridays'    =>   $noOfFridays,
                    'month_salary'     =>   $currentMonthSalary,
                    'day_salary'       =>   $daySalary,
                    'hourly_salary'    =>   $hourlySalary,
                    'salary_type'      =>   'salary',
                    'salary'           =>   $totalSalary,
                    'deductions'       =>   $loanDeduction,
                    'additions'        =>   0,
                    'overtime'         =>   0,
                    'total_salary'     =>   round($totalSalary,2),
                    'total_work_hours' =>   $totalWorkinghours,
                    'total_overtime_salary'     =>   round($totalOvertimeSalary,2),
                    'total_work_overtime'       =>   $totalOvertimehours,
                    'created_at'       =>   date('Y-m-d h:i:s'),
                    'status'           =>   'active'
                );//echo '<pre>';print_r($insertArray);exit;
                if($salaryId > 0)
                {
                    EmployeeMonthlySalary::where('id', $salaryId)->update($insertArray);
                    $emsId = $salaryId;
                }
                else
                {
                    $emsId = EmployeeMonthlySalary::insertGetId($insertArray);
                }
                if($emsId && $loanDeduction > 0)
                {
                    //check if history already generated
                    $checkSalaryHistoryarray = array(
                        'ems_id'            =>  $emsId,
                        'user_id'           =>  $emp->user_id,
                        'entry_type'        =>  'deduction',
                        'status'            =>  'active'
                    );
                    $checkSalaryHistoryCount = EmployeeSalaryHistory::where($checkSalaryHistoryarray)->get();
                    $salaryHistoryId = 0;
                    if($checkSalaryHistoryCount->count() > 0)
                    {
                        $salaryHistoryId = $checkSalaryHistoryCount[$checkSalaryHistoryCount->count()-1]->id;
                    }
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
                    if($salaryHistoryId > 0)
                    {
                        EmployeeSalaryHistory::where('id', $salaryHistoryId)->update($insertESHArray);
                    }
                    else
                    {
                        EmployeeSalaryHistory::insert($insertESHArray);
                    }
                    
                }
                //exit;
            }
        }

        //calculate Indemnity
        $this->calculateIndemnity($user_id);

        echo json_encode('done');
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
                echo 'Not eligible';
            }
            return true;
        }
        
        return null;
    }

    public function employee_overtime()
    {
        $title = $this->title;

        $year = date('Y');
        $month = date('m');
        $emp = '';

        $where = array();

        if(isset($_POST['search']))
        {
            if(isset($_POST['year']) && $_POST['year']!='')
            {
                $year = $_POST['year'];
            }
            if(isset($_POST['month']) && $_POST['month']!='')
            {
                $month = $_POST['month'];
            }
            // if(isset($_POST['employee']) && $_POST['employee']!='')
            // {
            //     $where['user_id'] = $_POST['employee'];
            //     $emp = $_POST['employee'];
            // }
        }

        $breadButton = 0;//echo $month;
        // $month = date('m');
        $overtimeDetails = EmployeeMonthlySalary::with('employees', 'employee_designation', 'employee_residency')->where('salary_type','overtime')->where('es_month',$month)->where('es_year',$year)->where('status','active')->get();
        // $employees = Employee::with(["employee_designation", "employee_salary_details", "employee_residency"])->where('status','active')->get();
        $additions = PayrollSettings::where('settings_type', 'addition')->where('status','active')->get();
        $deductions = PayrollSettings::where('settings_type', 'deductions')->where('status','active')->get();
        // echo '<pre>';print_r($overtimeDetails);exit;
        $overtimeCount = EmployeeMonthlySalary::where('salary_type','overtime')->where('es_month',$month)->where('status','active')->get()->count();
        return view('payroll.employee_overtime', compact('title', 'overtimeDetails', 'additions', 'deductions', 'breadButton', 'overtimeCount', 'month', 'year'));
    }

    public function generate_overtime_slip(Request $request)
    {
        $title = $this->title;
        $salaryDetails = EmployeeMonthlySalary::where('id', $request->id)->first();
        $employees = Employee::with(["employee_designation", "employee_salary_details", "employee_residency"])->where('user_id', $salaryDetails->emp_id)->where('status','active')->get();
        // echo '<pre>';print_r($employees);exit;
        return view('payroll.employee_overtime_slip', compact('title', 'salaryDetails', 'employees'));
    }
}
