<?php

namespace App\Http\Controllers;
use DB, PDF;
use Session;

// use App\Company as AppCompany;
use App\Models\Employee;
use App\Models\Departments;
use App\Models\Designations;
use App\Models\Company;
use App\Models\Branch;
use App\Models\EmployeeLoan;
use App\Models\EmployeeDetails;
use App\Models\EmployeeContacts;
use App\Models\EmployeeEducation;
use App\Models\EmployeeExperiences;
use App\Models\EmployeeAccounts;
use App\Models\EmployeeDocuments;
use App\Models\EmployeeSalary;
use App\Models\Residency;
use App\Models\Subresidency;
use App\Models\User;
use App\Models\Leaves;
use App\Models\FinancialYear;
use App\Models\EmployeeMonthlySalary;
use App\Models\EmployeeSalaryHistory;
use App\Models\EmployeeIndemnity;
use App\Models\AttendanceDetails;
use Illuminate\Http\Request;
// use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Overtime;
use App\Models\LeaveApprovalLogs;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->title = 'Employee';
        $this->current_datetime = date('Y-m-d H:i:s');
    }

    public function index(Request $request)
    {
        $title        = $this->title;

        $query = Employee::with(["employee_designation", "employee_branch"])->where('status', '!=', 'deleted');
        $search = [];
        if(isset($request->employee_id))
        {
            $flag = 1;
            $search['employee_id'] = $request->employee_id;
            $query->where('emp_generated_id','like',"%$request->employee_id%");
        }
        if(isset($request->employee))
        {
            $flag = 1;
            $search['employee'] = $request->employee;

            $query->where('first_name','like',"%$request->employee%");
        }
        if(isset($request->designation))
        {
            $flag = 1;
            $search['designation'] = $request->designation;
            $query->where('designation','=',$request->designation);
        }
        if(isset($request->branch))
        {
            $flag = 1;
            $search['branch'] = $request->branch;
            $query->where('branch','=',$request->branch);
        }
        $result = $query->get(); 
        $employees = $result;

        $designations = Designations::where('status','active')->get();     
        $designationsWEmpCount = Designations::withCount('employees')->where('status','active')->get();     
        $departments  = Departments::where('status','active')->get();     
        $companies    = Residency::where('status','active')->get();
        $branches    = Branch::where('status','active')->get();
        // $employees    = Employee::with(["employee_designation"])->where('status','active')->orderBy('id', 'DESC')->get();
        $auto_id = $this->getLastId();
        return view('edbr.employee', ['employees' => $employees,'designations' => $designations,
        'departments' => $departments,'companies' => $companies, 'title' => $title , 
        'auto_id' => $auto_id, 'designationsWEmpCount' => $designationsWEmpCount, 'search' => $search, 'branches' => $branches]);
    }
   
    public function store(Request $request)
    { 
        $this->company_id  = Session::get('company_id');
        $userArray = array(
            'company_id'    =>  $this->company_id,
            'username'      =>  $request->user_name,
            'name'          =>  $request->first_name.' '.$request->last_name,
            'email'         =>  $request->email,
            'password'      =>  Hash::make($request->password),
            'created_at'    =>  $this->current_datetime
        );
        $userId = User::create($userArray)->id;

        if($userId)
        {
            $insertArray = array(
                'user_id'       =>  $userId,
                'company_id'    =>  $this->company_id,
                'first_name'    =>  $request->first_name,
                'last_name'     =>  $request->last_name,
                'email'         =>  $request->email,
                'emp_generated_id'  =>  $request->emp_generated_id,
                'joining_date'  =>  date('Y-m-d', strtotime($request->joining_date)),
                'passport_no'   =>  $request->passport_no,
                'passport_expiry'   =>  $request->pass_expiry,
                'local_address' =>  $request->local_address,
                'phone'         =>  $request->phone,
                'visa_no'       =>  $request->visa_no,
                'company'       =>  ($request->com_hid==1)?0:$request->company,
                'department'    =>  ($request->dep_hid==1)?0:$request->department,
                'designation'   =>  $request->designation
            );
            $empId = Employee::create($insertArray);
        }
        return redirect('/employee')->with('success', 'Employee created successfully!');
    }

 
    public function update(Request $request)
    {
        $title = 'Profile';
        $employee = Employee::with([
            "employee_accounts", "employee_contacts", "employee_details","employee_company","employee_designation",
            "employee_education", "employee_education", "employee_experiences", "employee_loan", "employee_salary","employee_document", "employee_leaves", "employee_branch"
        ])->where("id", $request->id)->first();

        $department_dropdown  = Departments::where('status','active')->get();
        $designation_dropdown = Designations::where('status','active')->get();
        $company_dropdown     = Residency::where('status','active')->get();

        $branch_dropdown      = Branch::where('status','active')->get();
        $subcompany_dropdown  = Subresidency::where('status','active')->get();//dd($subcompany_dropdown);
        $loanDeductions = EmployeeSalaryHistory::where(array('entry_type_title' => 'loan', 'user_id' => $employee->user_id))->get();//echo '<pre>';print_r($loanDeductions);exit;

        $salaryDetails = EmployeeMonthlySalary::where('emp_id', $employee->user_id)->where(array('es_month'=>date('m'), 'es_year' => date('Y')))->first();

        $additions = '';$deductions = '';
        if(!empty($salaryDetails))
        {
            $additions = EmployeeSalaryHistory::where('entry_type', 'addition')->where('ems_id', $salaryDetails->id)->where('status','active')->get();
            $deductions = EmployeeSalaryHistory::where('entry_type', 'deduction')->where('ems_id', $salaryDetails->id)->where('status','active')->get();
        }
        // echo $employee->user_id;exit;
        // $attendanceAndHolidays = AttendanceDetails::join('holidays as h', 'attendance_details.attendance_on', '=', 'h.holiday_date')
        // ->select('attendance_details.attendance_on', 'h.holiday_day', 'h.title')
        // ->where('attendance_details.user_id', $employee->user_id)
        // ->get();
        $user_id = $employee->user_id;
        $attendanceAndHolidays = AttendanceDetails::select('a.user_id', 'a.attendance_on','h.holiday_day', 'h.holiday_date','h.title', 'sh.shift')
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
        $indemnityDetails = EmployeeIndemnity::where('user_id', $employee->user_id)->get();
        $annualleavedetails = getAnnualLeaveDetails($employee->user_id);
        $sickleavedetails = getSickLeaveDetails($employee->user_id);
        $financalYear = FinancialYear::get();
        $sal = (isset($employee->employee_salary->total_salary))?$employee->employee_salary->total_salary:0;
        $perday = $sal / 26;
        $emp_leaves = Leaves::where('user_id',$user_id)->where('leave_status','approved')->where('leave_type',1)->whereNull('is_post_transaction')->get();
        $an_emp_leaves = Leaves::where('user_id',$user_id)->where('leave_status','approved')->where('leave_type',1)->whereNotNull('is_post_transaction')->orderBy('updated_at','desc')->get();
        $emp_leaves_history = Leaves::where('user_id',$user_id)->where('leave_status','approved')->where('leave_type',1)->whereNotNull('is_post_transaction')->get();
        //dd($attendanceAndHolidays);
        // echo '<pre>';print_r($employee);exit;
        return view('edbr.profile', [
            'an_emp_leaves'        => $an_emp_leaves,
            'user'                 => $employee,
            'department_dropdown'  => $department_dropdown, 
            'designation_dropdown' => $designation_dropdown,
            'company_dropdown'     => $company_dropdown,
            'branch_dropdown'      => $branch_dropdown,
            'subcompany_dropdown'  => $subcompany_dropdown, 
            'title'                => $title,
            'holidayWork'          =>   $attendanceAndHolidays,
            'breadButton'          => 0,
            'loanDeductions'       => $loanDeductions,
            'salaryDetails'        => $salaryDetails,
            'indemnityDetails'     => $indemnityDetails,
            'additions'            => $additions,
            'deductions'           => $deductions,
            'financial_year'        => $financalYear,
            'annualleavedetails'   => $annualleavedetails,
            'sickleavedetails' => $sickleavedetails,
            'perday'=>$perday,
            'emp_leaves'=>$emp_leaves,
            'emp_leaves_history'=>$emp_leaves_history
        ]);
    }

    public function employee_details(Request $request, $id)
    {

        $empArray = array(
            'passport_no'       =>  $request->passport_no,
            'passport_expiry'   =>  $request->pass_expiry
        );
        Employee::where("id", $request->id)->update($empArray);

        $dataArray = array(
            'religion'              =>  $request->religion,
            'blood_group'           =>  $request->blood_group,
            'pi_address'            =>  $request->pi_address,
            'marital_status'        =>  $request->marital_status,
            'spouse_employment'     =>  $request->spouse,
            'child'                 =>  $request->children
        );
        $employee_details =  EmployeeDetails::where("emp_id", $request->id)->first();
        if ($employee_details  ===  null) {
            $dataArray['emp_id'] = $id;
            EmployeeDetails::create($dataArray);
           
        return redirect()->back()->with("success", "Employee details saved successfully!");
        } else {
            EmployeeDetails::where("emp_id", $request->id)->update($dataArray);
        }
        
        return redirect()->back()->with("success", "Employee details saved successfully!");
    }

    public function employee_contacts(Request $request, $id)
    {
        $employee_contacts = EmployeeContacts::where("emp_id", $request->id)->first();
        if ($employee_contacts  ==  null) {
            $insertArray = array(
                'emp_id'           =>  $id,
                'pri_con_name'     =>  $request->pri_con_name,
                'pri_con_relation' =>  $request->pri_con_relation,
                'pri_con_phone'    =>  $request->pri_con_phone,
                'pri_con_phone2'   =>  $request->pri_con_phone2,
                'sec_con_name'     =>  $request->sec_con_name,
                'sec_con_relation' =>  $request->sec_con_relation,
                'sec_con_phone'    =>  $request->sec_con_phone,
                'sec_con_phone2'   =>  $request->sec_con_phone2
            );
            EmployeeContacts::create($insertArray);
          return redirect()->back()->with("success", "Employee Details created successfully!");
        } else {
            $updateArray = array(
                'emp_id'           =>  $id,
                'pri_con_name'     =>  $request->pri_con_name,
                'pri_con_relation' =>  $request->pri_con_relation,
                'pri_con_phone'    =>  $request->pri_con_phone,
                'pri_con_phone2'   =>  $request->pri_con_phone2,
                'sec_con_name'     =>  $request->sec_con_name,
                'sec_con_relation' =>  $request->sec_con_relation,
                'sec_con_phone'    =>  $request->sec_con_phone,
                'sec_con_phone2'   =>  $request->sec_con_phone2
            );
        }
        EmployeeContacts::where("emp_id", $request->id)->update($updateArray);
        return redirect()->back()->with("success", "Details saved successfully!");
    }
    public function employee_education(Request $request, $id)
    {  
        if(isset($request->institute))
        {
            foreach($request->institute as $instkey => $inst)
            {
                $dataArray = array(
                    'emp_id'      =>  $id,
                    'institution' =>  $inst,
                    'subject'     =>  $request->subject[$instkey],
                    'start'       =>  $request->started[$instkey],
                    'end'         =>  $request->completed[$instkey],
                    'degree'      =>  $request->degree[$instkey],
                    'grade'       =>  $request->grade[$instkey],
                    'status'      =>  'active'
                );

                if(isset($request->edu_info_id) && isset($request->edu_info_id[$instkey]))
                { 
                    EmployeeEducation::where("id", $request->edu_info_id[$instkey])->update($dataArray);
                }
                else
                {
                    EmployeeEducation::create($dataArray);
                }
            }
            return redirect()->back()->with("success", "Details saved successfully!");
        }
        return redirect()->back()->with("error", "Something went wrong!");
    }

    public function employee_education_delete()
    {
        $deleteArray = array(
            'status' => 'inactive',
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        EmployeeEducation::where('id', $_POST['education_id'])->update($deleteArray);
        return redirect()->back()->with('success','Education removed successfully!');
    }

    public function employee_experience(Request $request, $id)
    { 
        if(isset($request->company))
        {
            foreach($request->company as $companykey => $company)
            {
                $dataArray = array(
                    'emp_id'        =>  $id,
                    'company'       =>  $company,
                    'location'      =>  $request->location[$companykey],
                    'job_position'  =>  $request->job[$companykey],
                    'period_from'   =>  $request->from[$companykey],
                    'period_to'     =>  $request->to[$companykey],
                    'status'        =>  'active'
                );

                if(isset($request->exp_info_id) && isset($request->exp_info_id[$companykey]))
                { 
                    EmployeeExperiences::where("id", $request->exp_info_id[$companykey])->update($dataArray);
                }
                else
                {
                    EmployeeExperiences::create($dataArray);
                }
            }
            return redirect()->back()->with("success", "Details saved successfully!");
        }
        return redirect()->back()->with("error", "Something went wrong!");
    }
    
    public function employee_experience_delete()
    {
        $deleteArray = array(
            'status' => 'inactive',
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        EmployeeExperiences::where('id', $_POST['experience_id'])->update($deleteArray);
        return redirect()->back()->with('success','Experience removed successfully!');
    }

    public function employee_accounts(Request $request, $id)
    { 
        $employee_accounts = EmployeeAccounts::where("emp_id", $request->id)->first();
        $dataArray = array(
            'bank_name'      =>  $request->bank_name,
            'account_number' =>  $request->account_number,
            'branch_code'    =>  $request->branch_code,
            'ifsc_number'    =>  $request->ifsc_number,
            'swift_code'     =>  $request->swift_code,
            'branch_name'    =>  $request->branch_name
        );
        if ($employee_accounts  ==  null) {
            $dataArray = array_merge(array('emp_id'=>$id),$dataArray);
            EmployeeAccounts::create($dataArray);
            return redirect()->back()->with("success", "Bank Account details saved successfully!");
        } else {
            EmployeeAccounts::where("emp_id", $employee_accounts->emp_id)->update($dataArray);
            return redirect()->back()->with("success", "Bank Account details updated successfully!");
        }
    }

    public function employee_documents($id, Request $request)
    {
        if(isset($request->document))
        {
            foreach($request->document as $dockey => $doc)
            {
                $dataArray = array(
                    'emp_id'            =>  $id,
                    'document_title'    =>  $request->title[$dockey],
                    'status'            =>  'active'
                );
                $employee_docs = EmployeeDocuments::where($dataArray)->first();

                if($doc) 
                {
                    $document = $doc;
                    $filename = $document->getClientOriginalName();
                    $document->move(public_path('uploads/document'), $filename);
                    $dataArray['document_file'] = $filename;
                }
                if ($employee_docs  ==  null) {
                    EmployeeDocuments::create($dataArray);
                } else {
                    EmployeeDocuments::where('id', $employee_docs->id)->update($dataArray);
                }
            }
            return redirect()->back()->with("success", "Documents saved successfully!");
        }
        return redirect()->back()->with("error", "Please fill all values!");
    }

    public function employee_document_delete()
    {
        $deleteArray = array(
            'status' => 'inactive',
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        EmployeeDocuments::where('id', $_POST['document_id'])->update($deleteArray);
        return redirect()->back()->with('success','Document deleted successfully!');
    }

    public function employee_salary(Request $request, $id)
    {  
        $employee = Employee::where("id", $request->id)->first();
        $employee_salary = EmployeeSalary::where("emp_id", $request->id)->first();
        $dataArray = array(
            'financal_year'         =>  $request->financial_year,
            'basic_salary'          =>  $request->basic_salary,
            'travel_allowance'      =>  $request->travel_allowance,
            'food_allowance'        =>  $request->food,
            'house_allowance'       =>  $request->house,
            'position_allowance'    =>  $request->position,
            'phone_allowance'       =>  $request->phone,
            'other_allowance'       =>  $request->other,
            'total_salary'          =>  $request->total_salary,
            'status'                =>  'active'
        );//echo '<pre>';print_r($dataArray);//exit;
        if ($employee_salary  ==  null) {
            $dataArray['emp_id'] = $id;
            EmployeeSalary::create($dataArray);//exit;
            return redirect()->back()->with("success", "Salary details saved successfully!");
        } else {
            EmployeeSalary::where("emp_id", $employee_salary->emp_id)->update($dataArray);
            return redirect()->back()->with("success", "Salary details updated successfully!");
        }
    }
   
    public function employee_info_update($id, Request $request)
    { 
        //update employee table first
        $emparray = array(
            'first_name'    =>  $request->first_name,
            'last_name'     =>  $request->last_name,
            'email'         =>  $request->email,
            'joining_date'  =>  date('Y-m-d', strtotime($request->joining_date)),
            'local_address' =>  $request->local_address,
            'phone'         =>  $request->phone,
            'company'       =>  $request->company,
            'branch'        =>  $request->branch,
            'department'    =>  (isset($request->department))?$request->department:0,
            'designation'   =>  $request->designation,
            'subcompany'    =>  (isset($request->subcompany))?$request->subcompany:0);
        

        if($request->has('profile')) 
        {
            $image = $request->file('profile');
            $filename = $image->getClientOriginalName();
            $image->move(public_path('uploads/profile'), $filename);
            $emparray['profile'] = $filename;
        } 
        Employee::where("id", $request->id)->update($emparray); 
//dd($request->all());
        $dataArray = array(
            'address'         =>  $request->local_address,
            'state'           =>  $request->state,
            'country'         =>  $request->country,
            'pin_code'        =>  $request->pin_code,
            'c_id'            =>  $request->c_id,
            'expi_c_id'       =>  date('Y-m-d', strtotime($request->expi_c_id)),
            'b_id'            =>  $request->b_id,
            'expi_b_id'       =>  date('Y-m-d', strtotime($request->expi_b_id)),
            'birthday'        =>  date('Y-m-d', strtotime($request->birthday)),
            'gender'          =>  $request->gender,
            'license'         =>  $request->license,
            'license_exp'     =>  date('Y-m-d', strtotime($request->license_exp)),
        );
        $employee_information = EmployeeDetails::where("emp_id", $request->id)->first();
        if ($employee_information  ===  null) {
            $dataArray['emp_id'] = $id;
            EmployeeDetails::create($dataArray);
            return redirect()->back()->with("success", "Employee details created successfully!");
        } else {
            EmployeeDetails::where("emp_id", $id)->update($dataArray);
            return redirect()->back()->with("success", "Employee details updated successfully!");
        }
       
    }
    public function employee_loan(Request $request, $id)
    {
        $employee_loan = EmployeeLoan::where("emp_id", $request->id)->first();
        $dataArray = array(
            'loan_amount'      =>  $request->loan_amount,
            'loan_date'        =>  $request->loan_date,
            'installment'      =>  $request->installment,
            'total_paid'       =>  $request->total_amount_paid,
            'install_pending'  =>  $request->install_pending,
            'install_amount'   =>  ($request->installment > 0)?($request->loan_amount/$request->installment):0,
            'amount_pending'   =>  $request->amount_pending,
            'out_kwd'          =>  $request->amount_pending,
            'remarks'          =>  $request->remarks,
            'status'           =>  'active'
        );
        // echo '<pre>';print_r($employee_loan);exit;
        if ($employee_loan  ==  null) {
            $dataArray = array_merge(array('emp_id'=>$id),$dataArray);
            EmployeeLoan::create($dataArray);
            return redirect()->back()->with("success", "Employee Loan details saved successfully!");
        } else {
            EmployeeLoan::where("emp_id", $employee_loan->emp_id)->update($dataArray);
            return redirect()->back()->with("success", "Employee Loan details updated successfully!");
        }
       
    }
    
    public function designation_dependent(Request $request, $id){
        $designation = Employee::with(["employee_designation"])->where("department", $request->id)->get();
        return response()->json($designation);
    
    }


    public function search(Request $request)
    { 
        $title        = $this->title;
        $query = Employee::with(["employee_designation"]);
        $flag = 0;
        $search = [];
        if(isset($request->employee_id))
        {
            $flag = 1;
            $search['employee_id'] = $request->employee_id;
            $query->where('emp_generated_id','like',"%$request->employee_id%");
        }
        if(isset($request->employee))
        {
            $flag = 1;
            $search['employee'] = $request->employee;
            // $query->where(function ($query) {
            //    $query->where('first_name','like',"%$request->employee%")->orWhere('last_name','like',"%$request->employee%");
            // });

            $query->where('first_name','like',"%$request->employee%");
            // $query->orWhere('last_name','like',"%$request->employee%");
        }
        if(isset($request->designation))
        {
            $flag = 1;
            $search['designation'] = $request->designation;
            $query->where('designation','=',$request->designation);
        }
        $result = $query->where('status','active')->get(); 
        $designations = Designations::where('status','active')->get();      
        $departments  = Departments::where('status','active')->get();  
        $companies    = Residency::where('status','active')->get();
        $auto_id = $this->getLastId();
        return view('edbr.employee', ['employees' => $result,'designations' => $designations,
        'departments' => $departments,'companies' => $companies,'search' => $search, 'title' => $title, 'auto_id' => $auto_id ]);
    }

    public function delete(Request $request)
    {
        $deleteArray = array(
            'status' => 'deleted',
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        Employee::where('id', $request->employee_id)->delete();
        return redirect('/employee')->with('success','Employee deleted successfully!');

    }

    
    public function company_department($id){
        $data['department'] = Departments::where("company_id", $id)->get();
        $data['branch'] = Branch::where("residency", $id)->get();
        $data['subcompany'] = Subresidency::where("residency", $id)->get(); 
        return response()->json($data);
    
    }
    
    public function department_designation($id){
        $designation = Designations::where("department", $id)->get();
        // echo '<pre>';print_r($designation);exit;
        return response()->json($designation);
    
    } 
    
    public function isUsernameExists(Request $request)
    { 
        $where['username'] = $request['user_name'];
        $count = User::where($where)->get();
        if(count($count) > 0)
        {
            echo "false";
        }
        else
        {
            echo "true";
        }
    } 
    
    public function isEmailExists(Request $request)
    { 
        $where['email'] = $request['email'];
        $count = Employee::where($where)->where('status', 'active')->get();
        if(count($count) > 0)
        {
            $ucount = User::where($where)->get();
            if(count($ucount) > 0)
            {
                echo "false";
            }
            else
            {
                echo "true";
            }
        }
        else
        {
            echo "true";
        }
    } 
    
    public function isEmployeeIdExists(Request $request)
    { 
        $where['emp_generated_id'] = $request['emp_generated_id'];
        $count = Employee::where($where)->get();
        if(count($count) > 0)
        {
            echo "false";
        }
        else
        {
            echo "true";
        }
    } 
    
    //department,branch,subcompany
    public function getByCompany(Request $request, $id){
        $department = Company::with("department")->where("id", $id)->get();//dd($departmentbyCompany);
        $branch     = Branch::with("company")->where("company_id", $id)->get();//dd($branchbycompany);
        $subcompany = Subresidency::with("company")->where("company_id", $id)->get();//dd($branchbycompany);
        return response()->json([$department, $branch, $subcompany]);
    
    } 
    
    public static function getLastId()
    {
        $last_inserted_id = Employee::orderBy('id', 'desc')->first();
        $auto_id = 1000 + 1;
        if($last_inserted_id)
        {
            $auto_id = 1000 + $last_inserted_id->id + 1;
        }
        return $auto_id;
    }

    public function employeeOpeningLeaveUpdate(Request $request)
    {
        $details = getAnnualLeaveDetails($request->id);

        $updateArray = array(
            'opening_leave_days' => $request->leave_balance_days,
            'opening_leave_amount' => $details['leaveAmount'],//(isset($request->leave_balance_amount))?$request->leave_balance_amount:0,
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        Employee::where('id', $request->id)->update($updateArray);
        return redirect()->back()->with('success','Employee Annual Leave updated successfully!');
    }

    public function employeephUpdate(Request $request)
    {
        $details = getAnnualLeaveDetails($request->id);

        $updateArray = array(
            'public_holidays_balance' => (isset($request->ph_balance_days))?$request->ph_balance_days:0,
            'public_holidays_amount' => (isset($request->ph_balance_amount))?$request->ph_balance_amount:0,
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        Employee::where('id', $request->id)->update($updateArray);
        return redirect()->back()->with('success','Employee PH updated successfully!');
    }

    public function employeeLeaveAmountUpdate(Request $request)
    {
        //echo '<pre>';print_r($_POST);exit;
        $up = array(
            'leave_status'  =>   $request->status_type,
            'amount'        =>  $request->lamount,
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        Leaves::where('id', $request->id)->update($up);

        $employee = Employee::where('id', $request->userid)->first();
        //echo $employee->opening_leave_days .'-'. $request->nodays;
        $updateArray = array(
            'opening_leave_days' => $employee->opening_leave_days - $request->nodays,
            'opening_leave_amount' => $employee->opening_leave_amount - $request->lamount,
            'updated_at'  =>  date('Y-m-d h:i:s')
        );//echo '<pre>';print_r($updateArray);exit;
        Employee::where('id', $request->userid)->update($updateArray);
        return redirect()->back()->with('success','Employee leave updated successfully!');
    }

    public function import(Request $request)
    { 
        //validate file
        $validate = $this->validateCSV($request->file('employee_file'));
        if($validate['status'] == 1)
        {
            //call excel/csv function
            $import = $this->importCSV($request->file('employee_file'));
            if($import['error'] == 1):
                return redirect()->back()->with("error", 'Something went wrong.');
            else:
                return redirect()->back()->with("success", 'Employees imported successfully.');
            endif;
        }
        else
        {
            return redirect()->back()->with("error", $validate['message']);
        }
    }

    private function validateCSV($file)
    {
        // File Details 
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();

        // Valid File Extensions
        $valid_extension = array("csv");

        // 5MB in Bytes
        $maxFileSize = 5097152; 

        // Check file extension
        if(in_array(strtolower($extension),$valid_extension))
        {
            // Check file size
            if($fileSize <= $maxFileSize)
            {
                $return['message'] = 'Import Successful.';
                $return['status'] = 1;
            }
            else
            {
                $return['message'] = 'File too large. File must be less than 2MB.';
                $return['status'] = 2;
            }
        }
        else
        {
            $return['message'] = 'Invalid File Extension.';
            $return['status'] = 2;
        }
        return $return;
    }

    private function importCSV($file)
    {
        // File Details 
        $filename = $file->getClientOriginalName();
        
        // File upload location
        $location = 'uploads/employees';
        // Upload file
        $file->move(public_path($location),$filename);

        // Import CSV to Database
        $filepath = public_path($location."/".$filename);

        // Reading file
        $file = fopen($filepath,"r");

        $importData_arr = array();
        $i = 0;

        while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) 
        {
            $num = count($filedata );

            // Skip first row
            if($i == 0)
            {
                $i++;
                continue; 
            }

            for ($c=0; $c < $num; $c++)
            {
                $importData_arr[$i][] = $filedata [$c];
            }
            $i++;
        }
        fclose($file);

        $company_id  = Session::get('company_id');
        $error = 0;
        // Insert to MySQL database
        foreach($importData_arr as $importData)
        {
            if($importData[11]=='')
            {
                continue;
            }
            if(!empty($importData[0]) && !empty($importData[1]) && !empty($importData[4]) && !empty($importData[5]) 
            && !empty($importData[6]) && !empty($importData[11]) && !empty($importData[12]) && !empty($importData[13])):
                $emp = '';
                if($importData[0] !='')
                {
                    //check employee id exists
                    $emp = Employee::where('emp_generated_id', $importData[0])->first();
                }
                $fname = (isset($importData[1]))?$importData[1]:'';
                $mname = (isset($importData[2]))?$importData[2]:'';
                $lname = (isset($importData[3]))?$importData[3]:'';
                $joi_date = (isset($importData[12]))?changeDateSlash($importData[12]):'';
                $c_date = date('Y-m-d');
                $calcualted_total_leave = calculateLeave($joi_date,$c_date);
                $balance_days = (isset($importData[43]))?(int)$importData[43]:0;
                $used_leave = $calcualted_total_leave - $balance_days;
                $employeeArray = array(
                    'company_id'    =>  $company_id,
                    'first_name'    =>  $fname.' '.$mname,
                    'last_name'     =>  $lname,
                    'email'         =>  (isset($importData[11]))?$importData[11]:NULL,
                    'joining_date'  =>  (isset($importData[12]))?changeDateSlash($importData[12]):'',
                    'passport_no'   =>  (isset($importData[24]))?$importData[24]:'',
                    'passport_expiry'   => (isset($importData[25]))?changeDateSlash($importData[25]):'',
                    'local_address' =>  (isset($importData[28]))?$importData[28]:'',
                    'phone'         =>  (isset($importData[10]))?$importData[10]:'',
                    'opening_leave_days'    =>  (isset($importData[43]))?$importData[43]:'',
                    'used_leave'  => (int)$used_leave ?? 0,
                    'opening_leave_amount'  =>  (isset($importData[44]))?$importData[44]:'',
                    'public_holidays_balance'  =>  (isset($importData[45]))?(int)$importData[45]:0,
                    'public_holidays_amount'  =>  (isset($importData[46]))?(float)$importData[46]:0,
                    'visa_no'       =>  ''
                );

                if(!empty($emp))
                {
                    //employee exists
                    $employeeArray['updated_at'] = $this->current_datetime;
                    Employee::where("emp_generated_id", $importData[0])->update($employeeArray);

                }
                else
                {
                    $auto_id = $this->getLastId();
                    //employee not exists
                    $userId = 0;
                    $usernameNew = str_replace(' ', '',$importData[1]).'@'.$auto_id;
                    $userDetails = User::where('email', $importData[11])->first();
                    // echo '<pre>';print_r($userDetails);exit;
                    if(isset($userDetails) && !empty($userDetails))
                    {
                        $userId = $userDetails->id;
                    }
                    else
                    {
                        $password =(isset($importData[0]))?$importData[0].'@mado':"123456";
                        $userArray = array(
                            'company_id'    =>  $company_id,
                            'username'      =>  $usernameNew,
                            'name'          =>  $fname.' '.$mname.' '.$lname,
                            'email'         =>  ((isset($importData[11]))?$importData[11]:''),
                            // 'password'      =>  Hash::make(randomPassword()),
                            'password'      =>  Hash::make($password),
                            'created_at'    =>  $this->current_datetime
                        );
                        $userId = User::create($userArray)->id;
                    }

                    if($userId)
                    {
                        //check company exists, if not create company
                        $resId = 0;
                        if(isset($importData[19]))
                        {
                            $comDetails = Residency::where('name', $importData[19])->first();
                            if(empty($comDetails))
                            {
                                //create employee
                                $comInsertArray = array(
                                    'name'  =>  $importData[19],
                                    'company_id'    =>  $company_id,
                                    'created_at' => $this->current_datetime
                                );
                                $resId = Residency::create($comInsertArray)->id;
                            }
                            else
                            {
                                $resId = $comDetails->id;//by employee id
                            }
                        }

                        //check subcompany exists, if not create subcompany
                        $subresId = 0;
                        if(isset($importData[20]))
                        {
                            $subcomDetails = Subresidency::where('name', $importData[20])->first();
                            if(empty($subcomDetails))
                            {
                                //create employee
                                $subcomInsertArray = array(
                                    'name'  =>  $importData[20],
                                    'company_id'   =>  $company_id,
                                    'residency'    =>  $resId,
                                    'created_at' => $this->current_datetime
                                );
                                $subresId = Subresidency::create($subcomInsertArray)->id;
                            }
                            else
                            {
                                $subresId = $subcomDetails->id;//by employee id
                            }
                        }

                        //check branch exists, if not create branch
                        $branchId = 0;
                        if(isset($importData[21]))
                        {
                            $branchDetails = Branch::where('name', $importData[21])->first();
                            if(empty($branchDetails))
                            {
                                //create employee
                                $branchInsertArray = array(
                                    'name'  =>  $importData[21],
                                    'company_id'   =>  $company_id,
                                    'residency'    =>  $resId,
                                    'created_at' => $this->current_datetime
                                );
                                $branchId = Branch::create($branchInsertArray)->id;
                            }
                            else
                            {
                                $branchId = $branchDetails->id;//by employee id
                            }
                        }

                        //check department exists, if not create department
                        $depId = 0;
                        if(isset($importData[22]))
                        {
                            $depDetails = Departments::where('name', $importData[22])->first();
                            if(empty($depDetails))
                            {
                                //create employee
                                $depInsertArray = array(
                                    'name'  =>  $importData[22],
                                    'company_id'    =>  $company_id,
                                    'created_at' => $this->current_datetime
                                );
                                $depId = Departments::create($depInsertArray)->id;
                            }
                            else
                            {
                                $depId = $depDetails->id;//by employee id
                            }
                        }

                        //check designation exists, if not create designation
                        $desId = 0;
                        if(isset($importData[23]))
                        {
                            $desDetails = Designations::where('name', $importData[23])->first();
                            if(empty($desDetails))
                            {
                                //create employee
                                $desInsertArray = array(
                                    'name'  =>  $importData[23],
                                    'multi_user'  =>  0,
                                    'department'  =>  $depId,
                                    'company_id'  =>  $company_id,
                                    'created_at'  => $this->current_datetime
                                );
                                $desId = Designations::create($desInsertArray)->id;
                            }
                            else
                            {
                                $desId = $desDetails->id;//by employee id
                            }
                        }

                        $employeeArray['user_id'] = $userId;
                        $employeeArray['emp_generated_id'] = $importData[0];
                        $employeeArray['company'] = $resId;
                        $employeeArray['subcompany'] = $subresId;
                        $employeeArray['branch'] = $branchId;
                        $employeeArray['department'] = $depId;
                        $employeeArray['designation'] = $desId;
                        $employeeArray['created_at'] = $this->current_datetime;
                        $empId = Employee::create($employeeArray)->id;
                        // echo '<pre>';print_r($employeeArray);exit;
                        if(isset($empId))
                        {
                            //insert/update other details of employee
                            $employeeDetailsArray = array(
                                'emp_id'               =>  $empId,
                                'birthday'             =>  (isset($importData[4]))?changeDateSlash($importData[4]):NULL,
                                'gender'               =>  (isset($importData[5]))?strtolower($importData[5]):NULL,
                                'country'             =>  (isset($importData[6]))?$importData[6]:NULL,
                                'state'             =>  (isset($importData[7]))?$importData[7]:NULL,
                                'address'               =>  (isset($importData[8]))?$importData[8]:NULL,
                                'pin_code'               =>  (isset($importData[9]))?$importData[9]:NULL,
                                'c_id'               =>  (isset($importData[13]))?$importData[13]:NULL,
                                'expi_c_id'               =>  (isset($importData[14]))?changeDateSlash($importData[14]):NULL,
                                'b_id'               =>  (isset($importData[15]))?$importData[15]:NULL,
                                'expi_b_id'               =>  (isset($importData[16])&& $importData[16]!='NR')?changeDateSlash($importData[16]):NULL,
                                'license'               =>  (isset($importData[17]))?$importData[17]:NULL,
                                'license_exp'               =>  (isset($importData[18]))?changeDateSlash($importData[18]):NULL,
                                'religion'              =>  (isset($importData[26]))?$importData[26]:NULL,
                                'blood_group'           =>  (isset($importData[27]))?$importData[27]:NULL,
                                // 'pi_address'            =>  $request->pi_address,
                                'marital_status'        =>  (isset($importData[29]))?$importData[29]:NULL,
                                // 'spouse_employment'     =>  $request->spouse,
                                // 'child'                 =>  $request->children
                            );
                            // echo '<pre>';print_r( $employeeDetailsArray);exit;
                            $employee_details =  EmployeeDetails::create($employeeDetailsArray);
                            $employeeSalaryArray = array(
                                'emp_id'                =>  $empId,
                                'basic_salary'          =>  (isset($importData[30]))?$importData[30]:0,
                                'financal_year'         =>  0,
                                'travel_allowance'      =>  (isset($importData[31]))?$importData[31]:0,
                                'food_allowance'        =>  (isset($importData[32]))?$importData[32]:0,
                                'house_allowance'       =>  (isset($importData[33]))?$importData[33]:0,
                                'position_allowance'    =>  (isset($importData[34]))?$importData[34]:0,
                                'phone_allowance'       =>  (isset($importData[35]))?$importData[35]:0,
                                'other_allowance'       =>  (isset($importData[36]))?$importData[36]:0,
                                'status'                =>  'active'
                            );
                            $employeeSalaryArray['total_salary'] = (float)$employeeSalaryArray['basic_salary'] + (float)$employeeSalaryArray['travel_allowance'] + (float)$employeeSalaryArray['food_allowance'] + (float)$employeeSalaryArray['house_allowance'] + (float)$employeeSalaryArray['position_allowance'] + (float)$employeeSalaryArray['phone_allowance'] + (float)$employeeSalaryArray['other_allowance'];
                            EmployeeSalary::create($employeeSalaryArray);


                            $employeeLoanArray = array(
                                'emp_id'           =>  $empId,
                                'loan_amount'      =>  (isset($importData[37]))?$importData[37]:0,
                                'loan_date'        =>  (isset($importData[38]))?changeDateSlash($importData[38]):NULL,
                                'installment'      =>  (isset($importData[39]))?$importData[39]:0,
                                'total_paid'       =>  (isset($importData[40]))?$importData[40]:0,
                                'install_pending'  =>  (isset($importData[41]))?$importData[41]:0,
                                'install_amount'   =>  (isset($importData[37]) && $importData[37] > 0 && isset($importData[39]) && $importData[39] >0)?($importData[37]/$importData[39]):0,
                                'amount_pending'   =>  (isset($importData[42]))?$importData[42]:0,
                                'out_kwd'          =>  (isset($importData[42]))?$importData[42]:0,
                                'remarks'          =>  NULL,
                                'status'           =>  'active'
                            );
                            EmployeeLoan::create($employeeLoanArray);


                            $employee_accounts = EmployeeAccounts::where("emp_id", $empId)->first();
                            $eadataArray = array(
                                'bank_name'      =>  (isset($importData[47]))?$importData[47]:'',
                                'account_number' =>  (isset($importData[50]))?$importData[50]:0,
                                'branch_code'    =>  (isset($importData[49]))?$importData[49]:'',
                                'ifsc_number'    =>  '',
                                'swift_code'     =>  '',
                                'branch_name'    =>  (isset($importData[48]))?$importData[48]:''
                            );
                            if ($employee_accounts  ==  null) {
                                $eadataArray = array_merge(array('emp_id'=>$empId),$eadataArray);
                                EmployeeAccounts::create($eadataArray);
                            } else {
                                EmployeeAccounts::where("emp_id", $empId)->update($eadataArray);
                            }


                            $employee_contacts = EmployeeContacts::where("emp_id", $empId)->first();
                            $ecinsertArray = array(
                                'emp_id'           =>  $empId,
                                'pri_con_name'     =>  (isset($importData[51]))?$importData[51]:'',
                                'pri_con_relation' =>  (isset($importData[52]))?$importData[52]:'',
                                'pri_con_phone'    =>  (isset($importData[53]))?$importData[53]:'',
                                'pri_con_phone2'   =>  (isset($importData[54]))?$importData[54]:'',
                                'sec_con_name'     =>  (isset($importData[55]))?$importData[55]:'',
                                'sec_con_relation' =>  (isset($importData[56]))?$importData[56]:'',
                                'sec_con_phone'    =>  (isset($importData[57]))?$importData[57]:'',
                                'sec_con_phone2'   =>  (isset($importData[58]))?$importData[58]:''
                            );
                            if ($employee_contacts  ==  null) {
                                EmployeeContacts::create($ecinsertArray);
                            } else {
                                EmployeeContacts::where("emp_id", $empId)->update($ecinsertArray);
                            }
                        }
                    }
                }
            else:
                $error = 1;
            endif;
              
        }
        $return['message'] = 'Import Successful.';
        $return['status'] = 1;
        $return['error'] = $error;
        return $return;
    }

    public function change_manual_punchin_status(Request $request,$user_id,$status){
        $emp_details = Employee::where('user_id',$user_id)->first();
        if($emp_details->is_manual_punchin == 0):
            $emp_details->is_manual_punchin = 1;
        else:
            $emp_details->is_manual_punchin = 0;
        endif;
        $emp_details->save();
        $return['message'] = 'Data saved Successful.';
        $return['status'] = 1;
        return $return;
    }

    public function change_passport_status(Request $request,$user_id,$status){
        $emp_details = Employee::where('user_id',$user_id)->first();
        if($emp_details->is_passport == 0):
            $emp_details->is_passport = 1;
        else:
            $emp_details->is_passport = 0;
        endif;
        $emp_details->save();
        $return['message'] = 'Data saved Successful.';
        $return['status'] = 1;
        return $return;
    }

    public function getanLeaveDetailsById(Request $request)
    {

        $user_id  = $request->user_id;
        $userdetails = Employee::with('employee_designation')->where('user_id', $user_id)->first();
        $leaveData = Leaves::find($request->id);
        $leave_details = getAnnualLeaveDetails($user_id);
        $pass_array=array(
            'leaveData' => $leaveData,
            'userdetails'=>$userdetails,
            'leave_details'=>$leave_details,
            'type' => $request->type ?? '',
        );

        $html =  view('edbr.an_leave_detail_modal', $pass_array )->render();
		$arr = [
			'success' => 'true',
			'html' => $html
		];
		return response()->json($arr);

    }

    public function post_leave_transaction(Request $request){
        //dd($request->all());
        $leave_data = Leaves::find($request->id);
        $annual_leave_days = intval($request->annual_leave_days ?? 0);
        $public_holidays = intval($request->public_holidays ?? 0);
        if(isset($request->type) && $request->type == 'download'):
            $employee = Employee::where('user_id',$leave_data->user_id)->where('status','active')->first();
            $leave_approve_date = LeaveApprovalLogs::where('leave_id',$leave_data->id)->where('status','approved')->orderBy('id','desc')->value('updated_at');
            $pass_array = array(
                "leave_data" => $leave_data,
                "employee"=>$employee,
                "leave_approve_date"=>$leave_approve_date ?? $leave_data->created_at,
            );
            $cdate = date('Y-m-d');
            $rname = $cdate.'_vacationhistory.pdf';
            $pdf = PDF::loadView('edbr.vacation_history_pdf', $pass_array)->setPaper('a4', 'landscape')->setWarnings(false);
            //print_r($pdf);
            return $pdf->download($rname);
        else:
            if(!empty($leave_data)):
                //deduct leave from employee account
                $employee = Employee::where('user_id',$leave_data->user_id)->where('status','active')->first();
                $leave_details = getAnnualLeaveDetails($leave_data->user_id);
                
                $cal_leave = (isset($leave_details) && $leave_details['totalLeaveDays']>0 )?$leave_details['totalLeaveDays']:0; 
                $used_leave_days = $employee->used_leave ?? 0;
                $opening_leave_days = $cal_leave - $used_leave_days;
                $public_balance = $employee->public_holidays_balance ?? 0;
                $leave_data->basic_salary = (isset($employee->employee_salary))?$employee->employee_salary->basic_salary:0;
                $leave_data->save();
                if(($opening_leave_days >= $annual_leave_days) || ($public_balance >= $public_holidays)):
                    $employee->opening_leave_days = $opening_leave_days - $annual_leave_days;
                    $employee->used_leave = $used_leave_days + $annual_leave_days;
                    $employee->public_holidays_balance = $public_balance - $public_holidays;
                    $employee->save();

                  
                    $rem_days = $opening_leave_days - $annual_leave_days;
                    //update leave status
                    $leave_data->claimed_annual_days = $annual_leave_days;
                    $leave_data->claimed_public_days = $public_holidays;
                    $leave_data->claimed_annual_days_rem = $opening_leave_days - $annual_leave_days;
                    $leave_data->claimed_public_days_rem = $public_balance - $public_holidays;
                    $leave_data->is_post_transaction = 1;
                    $leave_data->save();

                    return redirect()->back()->with('success','Data saved successfully.');
                else:
                    return redirect()->back()->with('error','Leave Balance not available.');
                endif;
                
            endif;
        endif;
    }


    public function updateemployeeleave(Request $request)
    { 
        //validate file
        $validate = $this->validateCSV($request->file('employee_file'));
        if($validate['status'] == 1)
        {
            //call excel/csv function
            $import = $this->importEmpCSV($request->file('employee_file'));
            if($import['error'] == 1):
                return redirect()->back()->with("error", 'Something went wrong.');
            else:
                return redirect()->back()->with("success", 'Employees leave balance updated successfully.');
            endif;
        }
        else
        {
            return redirect()->back()->with("error", $validate['message']);
        }
    }

    private function importEmpCSV($file)
    {
        // File Details 
        $filename = $file->getClientOriginalName();
        
        // File upload location
        $location = 'uploads/employees';
        // Upload file
        $file->move(public_path($location),$filename);

        // Import CSV to Database
        $filepath = public_path($location."/".$filename);

        // Reading file
        $file = fopen($filepath,"r");

        $importData_arr = array();
        $i = 0;

        while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) 
        {
            $num = count($filedata );

            // Skip first row
            if($i == 0)
            {
                $i++;
                continue; 
            }

            for ($c=0; $c < $num; $c++)
            {
                $importData_arr[$i][] = $filedata [$c];
            }
            $i++;
        }
        fclose($file);

        $company_id  = Session::get('company_id');
        $error = 0;
        // Insert to MySQL database
        foreach($importData_arr as $importData)
        {

            if(!empty($importData[0]) && !empty($importData[1])):
                $emp = '';
                if($importData[0] !='')
                {
                    //check employee id exists
                    $emp = Employee::where('emp_generated_id', $importData[0])->first();
                }
                if(!empty($emp)):
                    $joi_date = $emp->joining_date;
                    $c_date = date('Y-m-d');
                    $calcualted_total_leave = calculateLeave($joi_date,$c_date);
                    $balance_days = (isset($importData[2]))?(int)$importData[2]:0;
                    $used_leave = $calcualted_total_leave - $balance_days;
                    $emp->opening_leave_days = $balance_days;
                    $emp->opening_leave_amount = (isset($importData[3]))?(int)$importData[3]:0;
                    $emp->used_leave = (int)$used_leave ?? 0;
                    $emp->save();
                endif;

            else:
                $error = 1;
            endif;
              
        }
        $return['message'] = 'Import Successful.';
        $return['status'] = 1;
        $return['error'] = $error;
        return $return;
    }

    public function save_cost(Request $request){
        $employee_data = EmployeeDetails::where("emp_id", $request->user_id)->first();
        if(!empty($employee_data)):
            $employee_data->civil_cost = $request->civil_cost ?? NULL;
            $employee_data->baladiya_cost = $request->baladiya_cost ?? NULL;
            $employee_data->save();
            return redirect()->back()->with('success','Data saved successfully.');
        endif;
    }
}
