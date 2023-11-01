<?php

namespace App\Http\Controllers;
use DB;
use Session;
use App\Models\Employee;
use App\Models\Leaves;
use App\Models\Leavetype;
use App\Models\LeaveStatus;
use App\Models\Departments;
use App\Models\Designations;
use App\Http\Controllers\Auth;
use App\Models\LeaveHierarchy;
// use App\Helper\Helper;

use Illuminate\Http\Request;


class AdminLeaveController extends Controller
{
    public function index()
    {
        $leaveApplications = LeaveHierarchy::orderBy('id','desc');
        //for search
        $where = [];

        if(isset($_POST['search']))
        {
            if(isset($_POST['leavetype']) && $_POST['leavetype']!='')
            {
                $where['leave_type'] = $_POST['leavetype'];
            }
        }

        if(count($where) > 0)
        {
            $leavesQuery->where($where);

        }
        $leaveApplications = $leaveApplications->get();
        $defaultStatus    = LeaveStatus::find(1);
        $leavetype = Leavetype::where('status','active')->get();
        $departments = Departments::where('status','active')->whereNotNull('name')->get();
        $designations = Designations::where('status','active')->get();
        // $emp_details = $user->employee_details; 
        // echo '<pre>';print_r($leaveApplications);exit;
        return view('policies.admin_leaves', compact('leaveApplications', 'defaultStatus', 'leavetype', 'where','departments','designations'));
    }  

    public function store(Request $request)
    {
        $hir_arr = [];
        
        if(isset($request->sub_title) && count($request->sub_title) > 0):
            foreach($request->sub_title as $key => $val):
                if(!empty($val)):
                    $hir_arr[$key]['dept'] = $request->sub_department[$key];
                    $hir_arr[$key]['desig'] = $val;
                endif;
            endforeach;
        endif;
        //dd($hir_arr);
        $insertArray = array(
            'leave_type'     =>  $request->leave_type,
            'main_dept_id'     =>  $request->main_department,
            'main_desig_id'       =>  $request->main_title,
            'leave_hierarchy'     =>  (!empty($hir_arr)) ? json_encode($hir_arr) : Null,
        );
        if(isset($request->id) && !empty($request->id)):
            LeaveHierarchy::where('id', $request->id)->update($insertArray);
        else:
            LeaveHierarchy::insert($insertArray);
        endif;                                       
        return redirect('/admin_leaves')->with('success','Leave Hierarchy saved successfully!');
    }

    public function leaveApprove(Request $request)
    {
        $updateArray    = array(
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        $updateArray['leave_status'] = 'pending';
        if($request->approval_by == 1)
        {
            $updateArray['gm_approval'] = 4;
            $updateArray['leave_status'] = 'approved';
        }
        else if($request->approval_by == 2)
        {
            $updateArray['hr_approval'] = 4;
        }
        else if($request->approval_by == 3)
        {
            $updateArray['dm_approval'] = 4;
        }
        
        Leaves::where('id', $request->leave_id)->update($updateArray);
        return redirect('/leaves')->with('success','Leave approved successfully!');
    }

    public function leaveReject(Request $request)
    {
        $updateArray    = array(
            'reject_reason' =>  $request->reject_reason,
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        $updateArray['leave_status'] = 'rejected';
        if($request->reject_by == 1)
        {
            $updateArray['gm_approval'] = 6;
            // $updateArray['leave_status'] = 'approved';
        }
        else if($request->reject_by == 2)
        {
            $updateArray['hr_approval'] = 6;
        }
        else if($request->reject_by == 3)
        {
            $updateArray['dm_approval'] = 6;
        }
        
        Leaves::where('id', $request->leave_id)->update($updateArray);
        return redirect('/leaves')->with('success','Leave rejected successfully!');
    }

    public function leaveCancel(Request $request)
    {
        $updateArray    = array(
            'leave_status' =>  'cancelled',
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        Leaves::where('id', $request->leave_id)->update($updateArray);
        return redirect('/leaves')->with('success','Leave cancelled successfully!');
    }

    public function getLeaveDetailsById(Request $request)
    {
        $leaveData = LeaveHierarchy::find($request->id);
        $leavetype = Leavetype::where('status','active')->get();
        $departments = Departments::where('status','active')->whereNotNull('name')->get();
        $designations = Designations::where('status','active')->get();
        $pass_array=array(
			'leaveData' => $leaveData,
            'leavetype' => $leavetype,
            'departments' => $departments,
            'designations' => $designations
        );
        $html =  view('policies.admin_leave_modal', $pass_array )->render();
		$arr = [
			'success' => 'true',
			'html' => $html
		];
		return response()->json($arr);

    }
}
