<?php

namespace App\Http\Controllers;
use DB;
use Session;
use App\Models\Leaves;
use App\Models\Leavetype;
use App\Models\Employee;

// use App\Helper\Helper;

use Illuminate\Http\Request;


class LeavesController extends Controller
{
    public function index()
    {
       $this->user_id  = Session::get('user_id');
       $pending_request = Leaves::where('user_id',$this->user_id)->whereIn('leave_status',array('pending', 'new'))->get();
       $total_pending_request = count($pending_request);
       $leave_counts = Leaves::withCount('employee_leaves')->get();
       $leavetype = Leavetype::get();

        $this->user_id  = Session::get('user_id');
        $userdetails = Employee::with('employee_designation')->where('user_id', $this->user_id)->get();
        // $information = Helper::getInformation();
        // echo '<pre>';print_r($userdetails);
        // echo '<pre>';print_r($userdetails[0]->employee_designation->priority_level);
        // exit;
        $where = array();
        if((isset($userdetails[0]->employee_designation)) && $userdetails[0]->employee_designation->priority_level == 0)
        {
            $where = array(
                'user_id'   =>  $this->user_id);
        }

        if(isset($_POST['search']))
        {
            if(isset($_POST['leave_type']) && $_POST['leave_type']!='')
            {
                $where['leave_type'] = $_POST['leave_type'];
                // $leave_type = $_POST['leave_type'];
            }
            if(isset($_POST['leave_status']) && $_POST['leave_status']!='')
            {
                $where['leave_status'] = $_POST['leave_status'];
                // $leave_status = $_POST['leave_status'];
            }
            if(isset($_POST['leave_from']) && $_POST['leave_from']!='')
            {
                $where['leave_from'] = ">= ".date('d/m/Y', strtotime($_POST['leave_from']));
                // $leave_from = $_POST['leave_from'];
            }
            if(isset($_POST['leave_to']) && $_POST['leave_to']!='')
            {
                $where['leave_to'] = "<= ".date('d/m/Y', strtotime($_POST['leave_to']));
                // $leave_to = $_POST['leave_to'];
            }
        }
       $leaves = Leaves::with("leaves_leavetype")->where($where)->get();
       // if (request()->ajax())
       //  {
       //      return datatables()->of(Leaves::with("leaves_leavetype")->where('user_id',$this->user_id)->get())
       //          ->setRowId(function ($leaves)
       //          {
       //              return $leaves->id;
       //          })
       //          ->addColumn('leave type', function ($leaves)
       //          {
       //              return ucfirst($leaves->leaves_leavetype->name) ?? '';
       //          })
       //          ->addColumn('from', function ($leaves)
       //          {
       //              return ucfirst($leaves->leave_from) ?? '';
       //          })
       //          ->addColumn('to', function ($leaves)
       //          {
       //              return ucfirst($leaves->leave_to) ?? '';
       //          })
       //          ->addColumn('no of days', function ($leaves)
       //          {
       //              return ucfirst($leaves->leave_days) ?? '';
       //          })

       //          ->addColumn('reason', function ($leaves)
       //          {
       //              return ucfirst($leaves->leave_reason) ?? '';
       //          })
       //          ->addColumn('status', function ($leaves)
       //          {
       //              return ucfirst($leaves->leave_status) ?? '';
       //          })
       //          ->addColumn('action', function ($leaves)
       //          {
       //              $encodedData = base64_encode(json_encode($leaves));
       //              if($leaves->leave_status == 'new' ||  $leaves->leave_status == 'pending' ){
       //                  $button = '<div class="dropdown dropdown-action pull-right">
       //                              <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
       //                              <div class="dropdown-menu dropdown-menu-right">';
                                
       //              $button .= '<a class="dropdown-item deleteButton" href="#" data-bs-toggle="modal" data-bs-target="#delete_department" data-data="'.$encodedData.'"><i class="fa fa-trash-o m-r-5" ></i> Delete</a>';
                
       //              $button .= '</div></div>';
       //              return $button;
       //          }

       //          })
       //          ->rawColumns(['action'])
       //          ->make(true);
       //  }
        return view('lts.leaves',['leavetype' => $leavetype,'total_pending_request'=>$total_pending_request, 'leave_counts' => $leave_counts, 'leaves' => $leaves, 'where' => $where, 'userdetails' => $userdetails]);
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
            'leave_status'   =>  'new'
        );
        Leaves::insert($insertArray);
        return redirect('/leaves')->with('success','Leave applied successfully!');
    }
}
