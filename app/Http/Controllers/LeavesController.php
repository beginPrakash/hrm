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
        $leavetype = Leavetype::where('status','active')->get();
        $leave_details = getAnnualLeaveDetails($this->user_id);
        $sick_leave_details = getSickLeaveDetails($this->user_id);
        //dd($sick_leave_details);
        // $emp_details = $user->employee_details; 
        // echo '<pre>';print_r($leaveApplications);exit;
        return view('lts.leaves', compact('leaveApplications', 'defaultStatus', 'user', 'userdetails', 'leavetype', 'userdepartment', 'where','leave_details','sick_leave_details'));
    }  

    public function store(Request $request)
    {
        //dd($request->all());
        $this->user_id  = Session::get('user_id');
        $employee = Employee::where('user_id', $this->user_id)->where('status','!=','deleted')->first();
        $department_id = $employee->department ?? '';
        $designation_id = $employee->designation ?? '';
        //get leave hierarchy
        $leave_hierarchy = LeaveHierarchy::where(['main_dept_id'=>$department_id,'main_desig_id'=>$designation_id,'leave_type'=>$request->leave_type])->first();
        
        $insertArray = array(
            'user_id'        =>  $this->user_id,
            'leave_type'     =>  $request->leave_type,
            'leave_from'     =>  date('Y-m-d',strtotime($request->from_date)),
            'leave_to'       =>  date('Y-m-d',strtotime($request->to_date)),
            'leave_days'     =>  $request->days,
            'remaining_leave'=>  $request->remaining_leaves,
            'leave_reason'   =>  $request->leave_reason,
            'leave_status'   =>  'pending',
            'leave_hierarchy'=> $leave_hierarchy->leave_hierarchy ?? '',
        );
                                  
        $leave_data = Leaves::create($insertArray);
        $leave_id = $leave_data->id ?? '';

        //save employee request leave
        if(!empty($employee)):
            $total_request_leave = $employee->request_leave_days ?? 0;
            $employee->request_leave_days = $total_request_leave + $request->days;
            $employee->save();
        endif;
        //create approveal logs
        if(!empty($leave_hierarchy->leave_hierarchy)):
            $hierarchy_data = json_decode($leave_hierarchy->leave_hierarchy);
            if(!empty($hierarchy_data) && count($hierarchy_data) > 0):
                foreach($hierarchy_data as $key => $val):
                    $save_data = new LeaveApprovalLogs();
                    $save_data->employee_id = $this->user_id;
                    $save_data->leave_id = $leave_id;
                    $save_data->department_id = $val->dept;
                    $save_data->designation_id = $val->desig;
                    $save_data->save();
                endforeach;
            endif;
            
        endif;

        return redirect('/leaves')->with('success','Leave applied successfully!');
    }

    public function leaveApprove(Request $request)
    {
    //    / dd($request->all());
        //get leave details by leave id
        $leave_d = Leaves::find($request->leave_id);
        $userdetails = Employee::where('user_id', $leave_d->user_id ?? '')->where('status','!=','deleted')->first();
        $department = intval($userdetails->department) ?? '';
        $designation = intval($userdetails->designation) ?? '';
        $leave_approvaldata = LeaveApprovalLogs::where('department_id',$department)->where('designation_id',$designation)->where('leave_id',$request->leave_id)->first();
        if(!empty($leave_approvaldata)):
            $leave_approvaldata->delete();
        endif;
        $is_last_approval = LeaveApprovalLogs::where('leave_id',$request->leave_id)->count();
        if($is_last_approval == 0):
            if($leave_d->leave_type == 1):
                $opening_leave_days = $userdetails->opening_leave_days;
                $userdetails->opening_leave_days = $opening_leave_days + $leave_d->leave_days;
            elseif($leave_d->leave_type == 2):
                $sick_leave_days = $userdetails->sick_leave_days;
                $userdetails->sick_leave_days = $sick_leave_days + $leave_d->leave_days;  
            endif;
            $request_leave_days = $userdetails->request_leave_days;
            $userdetails->request_leave_days = $request_leave_days - $leave_d->leave_days;
            $userdetails->save();
            $updateArray    = array(
                'updated_at'  =>  date('Y-m-d h:i:s')
            );
            $updateArray['leave_status'] = 'approved';
            
            Leaves::where('id', $request->leave_id)->update($updateArray);

        endif;
        
        return redirect('/leaves')->with('success','Leave approved successfully!');
    }

    public function leaveReject(Request $request)
    {

        $leave_d = Leaves::find($request->leave_id);
        $userdetails = Employee::where('user_id', $leave_d->user_id ?? '')->where('status','!=','deleted')->first();
        if(!empty($userdetails)):
            $request_leave_days = $userdetails->request_leave_days;
            $userdetails->request_leave_days = $request_leave_days - $leave_d->leave_days;
            $userdetails->save();
        endif;
        $updateArray    = array(
            'reject_reason' =>  $request->reject_reason,
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        $updateArray['leave_status'] = 'rejected';
        
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
        $leaveData = Leaves::where('leave_type', $request->leave_type)->where('id', $this->user_id)->whereIn('leave_status', ['pending'])->sum('leave_days');
        echo $leaveData;
    }

    public function leave_request(Request $request){
        $userId = 0;
        if(auth()->user()):
            $userId = auth()->user()->id;
        endif;

        $userdetails = Employee::where('user_id', $userId)->where('status','!=','deleted')->first();
        $department = intval($userdetails->department) ?? '';
        $designation = intval($userdetails->designation) ?? '';
        $leave_approvaldata = LeaveApprovalLogs::where('department_id',$department)->where('designation_id',$designation)->where('employee_id','!=',$userId)->orderBy('id','asc')->groupBy('department_id','designation_id')->get();
        //$leave_approvaldata = LeaveApprovalLogs::where('department_id',$department)->where('designation_id',$designation)->orderBy('id','asc')->groupBy('leave_id')->get();
        return view('lts.leave_request',compact('leave_approvaldata'));

    }
}
