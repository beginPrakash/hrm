<?php

namespace App\Http\Controllers;
use DB;
use Session;
use App\Models\Employee;
use App\Models\Leaves;
use App\Models\Leavetype;
use App\Models\LeaveStatus;

use App\Http\Controllers\Auth;
// use App\Helper\Helper;

use Illuminate\Http\Request;


class LeavesController extends Controller
{
    public function index()
    {

        $this->user_id  = Session::get('user_id');
        $user             = auth()->user();
        $userdetails = Employee::with('employee_designation')->where('user_id', $this->user_id)->get();

        //if its department manager
        $userdepartment = '';
        if(isset($userdetails[0]->employee_designation) && $userdetails[0]->employee_designation->priority_level === 3)
        {
            $userdepartment = $userdetails[0]->department;
        }
        
        $leavesQuery = Leaves::with('status', 'leaves_leavetype', 'leave_user');
        if(isset($userdetails[0]->employee_designation) && $userdetails[0]->employee_designation->priority_level > 0)
        {
            $leavesQuery->WhereIn('leave_status', ['new', 'pending', 'approved', 'rejected']);
        }

        //for search
        $where = [];

        if(isset($_POST['search']))
        {
            if(isset($_POST['employee']) && $_POST['employee']!='')
            {
                $where['user_id'] = $_POST['employee'];
            }
            if(isset($_POST['leavetype']) && $_POST['leavetype']!='')
            {
                $where['leave_type'] = $_POST['leavetype'];
            }
            if(isset($_POST['leavestatus']) && $_POST['leavestatus']!='')
            {
                $where['leave_status'] = $_POST['leavestatus'];
            }
            if(isset($_POST['from']) && $_POST['from']!='')
            {
                $where['leave_from >= '] = $_POST['from'];
            }
            if(isset($_POST['to']) && $_POST['to']!='')
            {
                $where['leave_to <= '] = $_POST['to'];
            }
        }

        if(count($where) > 0)
        {
            $leavesQuery->where($where);

        }
        $leaveApplications = $leavesQuery->get();
        $defaultStatus    = LeaveStatus::find(1);
        $leavetype = Leavetype::get();
        // $emp_details = $user->employee_details; 
        // echo '<pre>';print_r($leaveApplications);exit;
        return view('lts.leaves', compact('leaveApplications', 'defaultStatus', 'user', 'userdetails', 'leavetype', 'userdepartment', 'where'));
    }  

    public function store(Request $request)
    {
        $this->user_id  = Session::get('user_id');
        $insertArray = array(
            'user_id'        =>  $this->user_id,
            'leave_type'     =>  $request->leave_type,
            'leave_from'     =>  date('Y-m-d',strtotime($request->from_date)),
            'leave_to'       =>  date('Y-m-d',strtotime($request->to_date)),
            'leave_days'     =>  $request->days,
            'remaining_leave'=>  $request->remaining_leaves,
            'leave_reason'   =>  $request->leave_reason,
            'leave_status'   =>  1
        );

//         //check user priority level and get approval list
//         $this->user_id  = Session::get('user_id');
//         $userdetails = Employee::with('employee_designation')->where('user_id', $this->user_id)->get();
//         $plevel = $userdetails[0]->employee_designation->priority_level;
//         if($plevel==0)
//         {
//             $plevelLimit = env('HIGHEST_PRIORITY');
//         }
//         else
//         {
//             $plevelLimit = $plevel-1;
//         }
// echo $plevelLimit;
//         for($i = $plevel; $i > $plevelLimit; $i--)
//         {
//             echo $i;
//         }
// exit;

//         $plevel = 0;
//         $plevelLimit = env('HIGHEST_PRIORITY');
//         if(isset($userdetails[0]->employee_designation))
//         {
//             $plevel = $userdetails[0]->employee_designation->priority_level;
//         }
//         $aprArray = array('gm_approval', 'hr_approval', 'dm_approval', 'am_approval', 'bm_approval');
//         for($pl = $plevel; $pl > $plevelLimit; $pl--)
//         {
//             echo '<th>Approval By - '.$aprArray[$pl].'</th>';
//         } 
                                                
        Leaves::insert($insertArray);
        return redirect('/leaves')->with('success','Leave applied successfully!');
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

    public function getLeaveDetails(Request $request)
    {
        $this->user_id  = Session::get('user_id');
        $leaveData = Leaves::where('leave_type', $request->leave_type)->where('id', $this->user_id)->whereIn('leave_status', ['new', 'pending', 'approved'])->sum('leave_days');
        echo $leaveData;
    }
}
