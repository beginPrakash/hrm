<?php

namespace App\Http\Controllers;
use DB;
use Session;
use App\Models\Overtime;

use App\Http\Controllers\Auth;
// use App\Helper\Helper;

use Illuminate\Http\Request;


class OvertimeController extends Controller
{
    public function __construct()
    {
        $this->title = 'Departments';
    }

    public function index()
    {
        $title = $this->title;
        $overtime = Overtime::get()->first();
        return view('policies.overtime', compact('overtime', 'title'));
    }

    public function update(Request $request)
    {
        $updateArray    = array(
            'working_days' =>  $request->working_days,
            'working_hours' =>  $request->working_hours,
            'off_day' =>  $request->off_day,
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        Overtime::where('id', 1)->update($updateArray);
        return redirect('/overtime')->with('success','Overtime updated successfully!');
    }
}
