<?php

namespace App\Http\Controllers;
use DB, Carbon\Carbon;
use Session;
use App\Models\Scheduling;
use Illuminate\Http\Request;
use App\Models\Leaves;

class Dashboard extends Controller
{
    public function index()
    {
        $current_date = Carbon::today();
        $after_date = Carbon::today()->addDay(7);
        $user_id  = Session::get('user_id');
        $sched_data = Scheduling::whereDate('shift_on', '>=', date($current_date))->whereDate('shift_on', '<', date($after_date))->where('employee',$user_id)->get();
        $balance_annual_leave_total = getAnnualLeaveDetails($user_id);
        $annual_leave_list = Leaves::where('user_id',$user_id)->where('leave_type','1')->where('leave_status','approved')->orderBy('id','desc')->limit(4)->get();
        $balance_sick_leave_total = getSickLeaveDetails($user_id);
        $sick_leave_list = Leaves::where('user_id',$user_id)->where('leave_type','2')->where('leave_status','approved')->orderBy('id','desc')->limit(4)->get();
        return view('dashboard',compact('sched_data','user_id','balance_annual_leave_total','annual_leave_list','balance_sick_leave_total','sick_leave_list'));
    }  
    
}
