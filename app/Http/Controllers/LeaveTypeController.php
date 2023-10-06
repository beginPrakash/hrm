<?php

namespace App\Http\Controllers;
use DB;
use Session;
use App\Models\Leavetype;

use App\Http\Controllers\Auth;

use Illuminate\Http\Request;


class LeaveTypeController extends Controller
{
    public function index()
    {
    	$leaveSettings    = Leavetype::where('status', 'active')->get();
    	return view('policies.leave', compact('leaveSettings'));
    }

    public function updateLeaveDetails(Request $request)
    {
    	$updArray = array(
            'created_at'  =>  date('Y-m-d h:i:s')
        );

    	$id = $request->lid;
        if($request->leave_days !='')
        {
        	$updArray['days'] = $request->leave_days;
        }
        if($request->carry_fwd !='')
        {
        	$updArray['carry_forward'] = $request->carry_fwd;
        }
        if($request->max_carry_days !='')
        {
        	$updArray['carry_forward_max'] = $request->max_carry_days;
        }
        if($request->earned_leaves !='')
        {
        	$updArray['earned_leave'] = $request->earned_leaves;
        }
        if($request->status != '')
        {
        	$updArray['status'] = $request->status;
        }

        Leavetype::where('id', $id)->update($updArray);
        echo json_encode('done');
    }
}