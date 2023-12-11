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
        
        $leavesQuery = Leaves::where('user_id', $this->user_id)->with('status', 'leaves_leavetype', 'leave_user');
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
        //check same date leave exist
        $from_date = date('Y-m-d',strtotime($request->from_date));
        $to_date = date('Y-m-d',strtotime($request->to_date));
        $leave_exist = [];
        if(empty($request->id)):
            $leave_exist = Leaves::where('user_id',$this->user_id)->whereBetween('leave_from', [$from_date, $to_date])
        ->orWhereBetween('leave_to', [$from_date, $to_date])->where('user_id',$this->user_id)->first();
        endif;
        if(!empty($leave_exist)):
            return redirect()->back()->with('error','Leave is already applied.Please select another date.');
        else:
            if($request->ph_checkbox == "on"):
                $ph = intval($request->public_holidays) ?? 0;
                $ph_rem = $employee->public_holidays_balance - $ph;
            else:
                $ph_rem = $employee->public_holidays_balance;
            endif;
            if($request->an_checkbox == "on"):
                $an = intval($request->annual_leave_days) ?? 0;
                $an_rem = $employee->opening_leave_days - $an;
            else:
                $an = $request->days;
                $an_rem = $employee->opening_leave_days - $an;
            endif;

            //get leave hierarchy
            $leave_hierarchy = LeaveHierarchy::where(['main_desig_id'=>$designation_id,'leave_type'=>$request->leave_type])->orWhere('main_dept_id',Null)->where('main_dept_id',$department_id)->first();
            $insertArray = array(
                'user_id'        =>  $this->user_id,
                'leave_type'     =>  $request->leave_type,
                'leave_from'     =>  date('Y-m-d',strtotime($request->from_date)),
                'leave_to'       =>  date('Y-m-d',strtotime($request->to_date)),
                'leave_days'     =>  $request->days,
                'remaining_leave'=>  $request->remaining_leaves ?? 0,
                'leave_reason'   =>  $request->leave_reason,
                'leave_status'   =>  'pending',
                'leave_hierarchy'=> $leave_hierarchy->leave_hierarchy ?? '',
                'claimed_annual_days' => $an ?? 0,
                'claimed_public_days' => $ph ?? 0,
                'claimed_annual_days_rem' => $an_rem ?? 0,
                'claimed_public_days_rem' => $ph_rem ?? 0,
            );
            if(!empty($request->id)):
                $leave_data = Leaves::where('id', $request->id)->first();
            else:
                $leave_data = Leaves::create($insertArray);
            endif;                    
            
            //dd($leave_data);
            $leave_id = $leave_data->id ?? '';
            $leave_datas = Leaves::where('id', $request->id)->first();
            //create approveal logs
            if(!empty($leave_hierarchy->leave_hierarchy)):
                //save employee request leave
                if(!empty($employee)):
                    if($request->leave_type == 1):
                        $total_request_leave = $employee->request_leave_days ?? 0;
                        if(!empty($leave_datas) && $total_request_leave > 0):
                            $total_request_leave = $total_request_leave - $leave_datas->leave_days;
                        endif;            
                        $employee->request_leave_days = $total_request_leave + $request->days;
                        $employee->save();
                    endif;
                    if($request->leave_type == 2):
                        $sick_leave_request_days = $employee->sick_leave_request_days ?? 0;
                        if(!empty($leave_datas) && $sick_leave_request_days > 0):
                            $sick_leave_request_days = $sick_leave_request_days - $leave_datas->leave_days;
                        endif;
                        $employee->sick_leave_request_days = $sick_leave_request_days + $request->days;
                        $employee->save();
                    endif;
                endif;
                if(!empty($request->id)):
                    $leave_data = Leaves::where('id', $request->id)->update($insertArray);
                endif;
                $hierarchy_data = json_decode($leave_hierarchy->leave_hierarchy);
                if(!empty($hierarchy_data)):
                    $i = 0;
                    foreach($hierarchy_data as $key => $val):
                        $save_data = new LeaveApprovalLogs();
                        $save_data->employee_id = $this->user_id;
                        $save_data->leave_id = $leave_id;
                        $save_data->department_id = $val->dept;
                        $save_data->designation_id = $val->desig;
                        if($i == 0):
                            $save_data->status = 'pending';
                        endif;
                        $save_data->save();
                        $i++;
                    endforeach;
                endif;
            else:

                //save employee request leave
                if(!empty($employee)):
                    if($request->leave_type == 1):
                        $opening_leave_days = $employee->opening_leave_days ?? 0;
                        if(!empty($leave_datas)):
                            $opening_leave_days = $opening_leave_days - $leave_datas->leave_days;
                        endif;
                        $employee->opening_leave_days = $opening_leave_days + $request->days;
                        $employee->save();
                    endif;
                    if($request->leave_type == 2):
                        $sick_leave_days = $employee->sick_leave_days ?? 0;
                        if(!empty($leave_datas)):
                            $sick_leave_days = $sick_leave_days - $leave_datas->leave_days;
                        endif;
                        //dd($sick_leave_days);
                        $employee->sick_leave_days = $sick_leave_days + $request->days;
                        $employee->save();
                    endif;
                endif;

                if(!empty($request->id)):
                    $leave_data = Leaves::where('id', $request->id)->update($insertArray);
                endif;

                $save_data = Leaves::find($leave_id);
                $save_data->leave_status ='approved';
                $save_data->save(); 
                $company_id  = Session::get('company_id');
                if($request->leave_type == 1):
                    $shift_id = 7;
                elseif($request->leave_type == 2):
                    $shift_id = 8;
                elseif($request->leave_type == 6):
                    $shift_id = 9;
                elseif($request->leave_type == 7):
                    $shift_id = 2;
                endif;

                $startingDate = strtotime(date('Y-m-d',strtotime($request->from_date)));
                $endingDate = strtotime(date('Y-m-d',strtotime($request->to_date)));
                    
                for ($currentDate = $startingDate; $currentDate <= $endingDate; $currentDate += (86400)) {
                    $date = date('Y-m-d', $currentDate);
                    //save scheduling
                    $sched_data = Scheduling::where('employee',$this->user_id)->where('shift_on',$date)->first();
                    $create_sched = new Scheduling();
                    $create_sched->company_id = $company_id;
                    $create_sched->department = $department_id;
                    $create_sched->employee = $this->user_id;
                    $create_sched->shift_on = $date;
                    $create_sched->shift = $shift_id ?? 0;
                    $create_sched->save();
                }           
            endif;
            
            return redirect('/leaves')->with('success','Leave applied successfully!');
        endif;
    }

    public function leaveApprove(Request $request)
    {
    //    / dd($request->all());
        //get leave details by leave id
        $leave_d = Leaves::find($request->leave_id);
        $userdetails = Employee::where('user_id', $leave_d->user_id ?? '')->where('status','!=','deleted')->first();
        $department = intval($userdetails->department) ?? '';
        $designation = intval($userdetails->designation) ?? '';
        $leave_approvaldata = LeaveApprovalLogs::where('leave_id',$request->leave_id)->where('status','pending')->first();
        if(!empty($leave_approvaldata)):
            $leave_approvaldata->status = 'approved';
            $leave_approvaldata->save();
            //update pending tatus for next role
            $leave_pending_status = LeaveApprovalLogs::where('leave_id',$request->leave_id)->whereNull('status')->first();
            if(!empty($leave_pending_status)):
                $leave_pending_status->status = 'pending';
                $leave_pending_status->save();
                //dd($leave_pending_status);
            endif;
        endif;
        $is_last_approval = LeaveApprovalLogs::where('leave_id',$request->leave_id)->where('status','!=','approved')->count();
        if($is_last_approval == 0):
            if($leave_d->leave_type == 1):
                $opening_leave_days = $userdetails->opening_leave_days;
                $userdetails->opening_leave_days = $opening_leave_days + $leave_d->leave_days;
                $request_leave_days = $userdetails->request_leave_days;
                $userdetails->request_leave_days = $request_leave_days - $leave_d->leave_days;
                $userdetails->save();
            elseif($leave_d->leave_type == 2):
                $sick_leave_days = $userdetails->sick_leave_days;
                $userdetails->sick_leave_days = $sick_leave_days + $leave_d->leave_days;  
                $sick_leave_request_days = $userdetails->sick_leave_request_days;
                $userdetails->sick_leave_request_days = $sick_leave_request_days - $leave_d->leave_days;
                $userdetails->save();
            endif;
            
            $company_id  = Session::get('company_id');
            if($leave_d->leave_type == 1):
                $shift_id = 7;
            elseif($leave_d->leave_type == 2):
                $shift_id = 8;
            elseif($leave_d->leave_type == 6):
                $shift_id = 9;
            elseif($leave_d->leave_type == 7):
                $shift_id = 2;
            endif;

            $startingDate = strtotime($leave_d->leave_from);
            $endingDate = strtotime($leave_d->leave_to);
                
            for ($currentDate = $startingDate; $currentDate <= $endingDate; $currentDate += (86400)) {
                $date = date('Y-m-d', $currentDate);
                //save scheduling
                $sched_data = Scheduling::where('employee',$userdetails->user_id ?? 0)->where('shift_on',$date)->first();
                $create_sched = new Scheduling();
                $create_sched->company_id = $userdetails->company_id ?? 0;
                $create_sched->department = $department;
                $create_sched->employee = $userdetails->user_id ?? 0;
                $create_sched->shift_on = $date;
                $create_sched->shift = $shift_id ?? 0;
                $create_sched->save();
            } 

            $updateArray    = array(
                'updated_at'  =>  date('Y-m-d h:i:s')
            );
            $updateArray['leave_status'] = 'approved';
            
            Leaves::where('id', $request->leave_id)->update($updateArray);

        endif;
        
        return redirect('/leave_request')->with('success','Leave approved successfully!');
    }

    public function leaveReject(Request $request)
    {

        $leave_d = Leaves::find($request->leave_id);
        $userdetails = Employee::where('user_id', $leave_d->user_id ?? '')->where('status','!=','deleted')->first();
        $leave_approvaldata = LeaveApprovalLogs::where('leave_id',$request->leave_id)->where('status','pending')->first();
        if(!empty($leave_approvaldata)):
            $leave_approvaldata->status = 'approved';
            $leave_approvaldata->save();
            //update pending tatus for next role
            // $leave_pending_status = LeaveApprovalLogs::where('leave_id',$request->leave_id)->first();
            // if(!empty($leave_pending_status)):
            //     $leave_pending_status->status = 'pending';
            //     $leave_pending_status->save();
            // endif;
        endif;
        if(!empty($userdetails)):
            $is_last_approval = LeaveApprovalLogs::where('leave_id',$request->leave_id)->count();
            //if($is_last_approval == 0):
                if($leave_d->leave_type == 1):
                    $request_leave_days = $userdetails->request_leave_days;
                    $userdetails->request_leave_days = $request_leave_days - $leave_d->leave_days;
                    $userdetails->save();
                elseif($leave_d->leave_type == 2): 
                    $sick_leave_request_days = $userdetails->sick_leave_request_days;
                    $userdetails->sick_leave_request_days = $sick_leave_request_days - $leave_d->leave_days;
                    $userdetails->save();
                endif;

                $updateArray    = array(
                    'reject_reason' =>  $request->reject_reason,
                    'updated_at'  =>  date('Y-m-d h:i:s')
                );
                $updateArray['leave_status'] = 'rejected';
                
                Leaves::where('id', $request->leave_id)->update($updateArray);

            //endif;
        endif;
        
        return redirect('/leave_request')->with('success','Leave rejected successfully!');
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
        //dd($department.'---'.$designation);
        $leave_approvaldata = LeaveApprovalLogs::where('designation_id',$designation)->where('employee_id','!=',$userId)->where('status','pending')->where('designation_id',$designation)->where('employee_id','!=',$userId)->orWhereNull('department_id')->where('department_id',$department)->orderBy('id','asc')->groupBy('leave_id')->get();
        //$leave_approvaldata = LeaveApprovalLogs::where('department_id',$department)->where('designation_id',$designation)->orderBy('id','asc')->groupBy('leave_id')->get();
        return view('lts.leave_request',compact('leave_approvaldata'));

    }

    public function getLeaveDetailsById(Request $request)
    {
        $this->user_id  = Session::get('user_id');
        $userdetails = Employee::with('employee_designation')->where('user_id', $this->user_id)->get();
        $leavetype = Leavetype::where('status','active')->get();
        $leaveData = Leaves::find($request->id);
        $leave_details = getAnnualLeaveDetails($this->user_id);
        $sick_leave_details = getSickLeaveDetails($this->user_id);
        $pass_array=array(
			'leave_details' => $leave_details,
            'leavetype' => $leavetype,
            'sick_leave_details' => $sick_leave_details,
            'leaveData' => $leaveData,
            'userdetails'=>$userdetails,
        );
        $html =  view('lts.leave_modal', $pass_array )->render();
		$arr = [
			'success' => 'true',
			'html' => $html
		];
		return response()->json($arr);

    }
}
