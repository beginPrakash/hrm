<?php

namespace App\Http\Controllers;
use DB;
use Session;
use App\Models\Scheduling;
use Illuminate\Http\Request;

class Dashboard extends Controller
{
    public function index()
    {
        $month = date('m');
        $year = date('y');

        $user_id  = Session::get('user_id');
        $sched_data = Scheduling::whereMonth('shift_on', '=', date('m'))->whereYear('shift_on', '=', date('Y'))->where('employee',$user_id)->get();
        return view('dashboard',compact('sched_data','user_id'));
    }  
    
}
