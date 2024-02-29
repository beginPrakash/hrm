<?php

namespace App\Http\Controllers;
use App\Models\Designations;
use App\Models\Departments;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Session;

class DesignationController extends Controller
{    
    public function __construct()
    {
        $this->title = 'Job title';
    }

    public function index()
    { 
        $title = $this->title;
        if (request()->ajax())
        {
            return datatables()->of(Designations::where('status','active')->get())
                ->setRowId(function ($designation)
                {
                    return $designation->id;
                })
                ->addColumn('designation', function ($designation)
                {
                    return ucfirst($designation->name) ?? '';
                })
                ->addColumn('multi_user', function ($designation)
                {
                    $mu = 'Multiple';
                    if($designation->multi_user==0)
                    {
                        $mu = 'Single';
                    }
                    return ucfirst($mu) ?? '';
                })
                // ->addColumn('department', function ($row)
                // {
                //     return $row->Department->name?? '';
                // })
                ->addColumn('action', function ($designation)
                {
                    $encodedData = base64_encode(json_encode($designation));
                    $button = '';
                    if($designation->editable === 1 )
                    {
                        $button = '<div class="dropdown dropdown-action pull-right">
                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                    <div class="dropdown-menu dropdown-menu-right">';
                        // if (auth()->user()->can('edit-department'))
                        // {
                            $button .= '<a class="dropdown-item editButton"  href="#" data-bs-toggle="modal" data-bs-target="#edit_designation" data-data="'.$encodedData.'"><i class="fa fa-pencil m-r-5"></i> Edit</a>';
                        // }
                        // if (auth()->user()->can('delete-department'))
                        // {
                            $button .= '<a class="dropdown-item deleteButton" href="#" data-bs-toggle="modal" data-bs-target="#delete_designation" data-data="'.$encodedData.'"><i class="fa fa-trash-o m-r-5" ></i> Delete</a>';
                        // }
                        $button .= '</div></div>';
                    }
                    return $button;

                })
                ->rawColumns(['action'])
                ->make(true);

        }
        $allDepartments = Departments::where('status','active')->get();
        return view('edbr.designation', compact('allDepartments', 'title'));
    }

    public function store()
    {  //echo '<pre>';print_r($_POST);exit;
        $company_id  = Session::get('company_id');
        $insertArray = array(
            'name'  =>  $_POST['designation'],
            'multi_user'  =>  (isset($_POST['multi_user']))?$_POST['multi_user']:0,
            'is_sales'  =>  (isset($_POST['is_sales']))?$_POST['is_sales']:0,
            // 'department'  =>  $_POST['department'],
            'company_id'    =>  $company_id,
            'created_at'  =>  date('Y-m-d h:i:s')
        );

        Designations::insert($insertArray);
        return redirect('/designation')->with('success','Designation created successfully!');
    }

    public function update()
    {
        $updateArray = array(
            'name' => $_POST['designations_name'],
            'multi_user'  =>  (isset($_POST['multi_user']))?$_POST['multi_user']:0,
            'is_sales'  =>  (isset($_POST['is_sales']))?$_POST['is_sales']:0,
            // 'department'  =>  $_POST['department'],
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        Designations::where('id', $_POST['designations_id'])->update($updateArray);
        return redirect('/designation')->with('success','Designation updated successfully!');

    }


    public function delete()
    {
        $deleteArray = array(
            'status' => 'inactive',
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        Designations::where('id', $_POST['designations_id'])->update($deleteArray);
        return redirect('/designation')->with('success','Designation deleted successfully!');

    }

    public function isDesignationExists(Request $request)
    { 
        $status = 'active';
        $where = ['status'=>$status];
        if(isset($request['id']))
        {
            $where['name'] = $request['designations_name'];
            // $where['department'] = $request['department'];
            $count = Designations::where($where)->where('id', '!=', $request['id'])->get();
        }
        else
        {
            $where['name'] = $request['designation'];
            // $where['department'] = $request['department'];
            $count = Designations::where($where)->get();
        }
        
        if(count($count) > 0)
        {
            echo "false";
        }
        else
        {
            echo "true";
        }
    }
}
