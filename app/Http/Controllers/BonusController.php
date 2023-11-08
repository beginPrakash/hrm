<?php

namespace App\Http\Controllers;
use DB;
use Session;
use App\Models\Employee;
use App\Models\Leaves;
use App\Models\Leavetype;
use App\Models\LeaveStatus;
use App\Models\LeaveHierarchy;
use App\Models\LeaveApprovalLogs;
use App\Http\Controllers\Auth;
use App\Models\Scheduling;
use Illuminate\Http\Request;
use App\Models\EmployeeBonus;

class BonusController extends Controller
{
    public function index()
    {
        $userdetails = Employee::with('employee_designation')->where('status', 'active')->get();
        $sql_que = EmployeeBonus::orderBy('id','desc');
        //for search
        $where = [];

        if(isset($_POST['search']))
        {
            if(isset($_POST['employee']) && $_POST['employee']!='')
            {
                $where['employee_id'] = $_POST['employee'];
            }
        }

        if(count($where) > 0)
        {
            $sql_que->where($where);

        }
        $bonus_data = $sql_que->get();
        return view('lts.bonus', compact('bonus_data','userdetails','where'));
    }  

    public function store(Request $request)
    {
        //check same date bonus exist
        $bonus_date = date('Y-m-d',strtotime($request->bonus_date));
        if(empty($request->id)):
            $bonus_exist = EmployeeBonus::where('employee_id',$request->employee_id)->where('bonus_date', $bonus_date)->first();
        endif;
        if(!empty($bonus_exist)):
            return redirect()->back()->with('error','Bonus is already created.Please select another date.');
        else:
            $insertArray = array(
                'employee_id'        =>  $request->employee_id,
                'bonus_date'     =>  $bonus_date,
                'bonus_amount'     =>  $request->bonus_amount,
                'title'=>  $request->title,
            );
            if(!empty($request->id)):
                $bonus_data = EmployeeBonus::where('id', $request->id)->update($insertArray);
            else:
                $bonus_data = EmployeeBonus::create($insertArray);
            endif;                    
            return redirect('/bonus')->with('success','Bonus saved successfully!');
        endif;
    }


    public function details(Request $request)
    {
        $userdetails = Employee::with('employee_designation')->where('status', 'active')->get();
        $bonusData = EmployeeBonus::find($request->id);
        $pass_array=array(
			'userdetails' => $userdetails,
            'bonusData' => $bonusData
        );
        $html =  view('lts.bonus_modal', $pass_array )->render();
		$arr = [
			'success' => 'true',
			'html' => $html
		];
		return response()->json($arr);

    }

    public function delete_bonus(Request $request)
    {
        $bonusData = EmployeeBonus::where('id',$request->bonus_id)->delete();
		return redirect('/bonus')->with('success','Bonus deleted successfully!');

    }

    
}
