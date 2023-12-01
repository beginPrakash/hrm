<?php

namespace App\Http\Controllers;
use DB;
use Session;
use App\Models\Employee;
use App\Http\Controllers\Auth;
use App\Models\Scheduling;
use Illuminate\Http\Request;
use App\Models\EmployeeDeduction;

class DeductionController extends Controller
{
    public function index()
    {
        $userdetails = Employee::with('employee_designation')->where('status', 'active')->get();
        $sql_que = EmployeeDeduction::orderBy('id','desc');
        //for search
        $where = [];
        $serach_text = $_POST['search_text'] ?? '';
        if(isset($_POST['search']))
        {
            
            //dd($_POST['search_text']);
            if(isset($_POST['search_text']) && $_POST['search_text']!='')
            {
                $userdata = Employee::where(DB::raw("CONCAT(first_name, ' ', last_name)") , 'like', '%'.$_POST['search_text'].'%')
                                ->orWhere('emp_generated_id',$serach_text)->select(DB::raw("GROUP_CONCAT(id SEPARATOR ',') as `ids`"))->where('status', 'active')->first();                    
                $user_ids = $userdata->ids ?? '';
                $sql_que = $sql_que->whereIn('employee_id',explode(',',$user_ids));
            }
        }
        
        
        $deduction_data = $sql_que->get();
        return view('payroll.deduction', compact('deduction_data','where','serach_text','userdetails'));
    }  

    public function store(Request $request)
    {
        //check same date deduction exist
        $deduction_date = date('Y-m-d',strtotime($request->deduction_date));
        if(empty($request->id)):
            $deduction_exist = EmployeeDeduction::where('employee_id',$request->employee_id)->where('deduction_date', $deduction_date)->first();
        else:
            $deduction_exist = EmployeeDeduction::where('employee_id',$request->employee_id)->where('id','!=',$request->id)->where('deduction_date', $deduction_date)->first();
        endif;
        if(!empty($deduction_exist)):
            return redirect()->back()->with('error','Deduction is already created.Please select another date.');
        else:
            $insertArray = array(
                'employee_id'        =>  $request->employee_id,
                'deduction_date'     =>  $deduction_date,
                'deduction_amount'     =>  $request->deduction_amount,
                'title'=>  $request->title,
            );
            if(!empty($request->id)):
                $deduction_data = EmployeeDeduction::where('id', $request->id)->update($insertArray);
            else:
                $deduction_data = EmployeeDeduction::create($insertArray);
            endif;                    
            return redirect('/deduction')->with('success','Deduction saved successfully!');
        endif;
    }


    public function details(Request $request)
    {
        $userdetails = Employee::with('employee_designation')->where('status', 'active')->get();
        $deductionData = EmployeeDeduction::find($request->id);
        $pass_array=array(
			'userdetails' => $userdetails,
            'deductionData' => $deductionData
        );
        $html =  view('payroll.deduction_modal', $pass_array )->render();
		$arr = [
			'success' => 'true',
			'html' => $html
		];
		return response()->json($arr);

    }

    public function delete_deduction(Request $request)
    {
        $deductionData = EmployeeDeduction::where('id',$request->deduction_id)->delete();
		return redirect('/deduction')->with('success','Deduction deleted successfully!');

    }

    
}
