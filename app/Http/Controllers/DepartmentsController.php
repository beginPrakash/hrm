<?php

namespace App\Http\Controllers;
use App\Models\Departments;


use Illuminate\Http\Request;
use Session;

class DepartmentsController extends Controller
{
    public function __construct()
    {
        $this->title = 'Departments';
    }

    public function index()
    { 
        $title = $this->title;
        if (request()->ajax())
        {
            return datatables()->of(Departments::where('status','active')->get())
                ->setRowId(function ($department)
                {
                    return $department->id;
                })
                ->addColumn('department', function ($department)
                {
                    return ucfirst($department->name) ?? '';
                })
                ->addColumn('action', function ($department)
                {
                    $encodedData = base64_encode(json_encode($department));
                    $button = '<div class="dropdown dropdown-action pull-right">
                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                    <div class="dropdown-menu dropdown-menu-right">';
                    // if (auth()->user()->can('edit-department'))
                    // {
                        $button .= '<a class="dropdown-item editButton"  href="#" data-bs-toggle="modal" data-bs-target="#edit_department" data-data="'.$encodedData.'"><i class="fa fa-pencil m-r-5"></i> Edit</a>';
                    // }
                    // if (auth()->user()->can('delete-department'))
                    // {
                        $button .= '<a class="dropdown-item deleteButton" href="#" data-bs-toggle="modal" data-bs-target="#delete_department" data-data="'.$encodedData.'"><i class="fa fa-trash-o m-r-5" ></i> Delete</a>';
                    // }
                    $button .= '</div></div>';
                    return $button;

                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('edbr.department', compact('title'));
    }

    public function store()
    { 
        $company_id  = Session::get('company_id');
        $insertArray = array(
            'name'          =>  $_POST['department'],
            'company_id'    =>  $company_id,
            'created_at'    =>  date('Y-m-d h:i:s')
        );

        Departments::insert($insertArray);
        return redirect('/department')->with('success','Department created successfully!');
    }
    public function update()
    {
        $updateArray    = array(
            'name'        => $_POST['department_name'],
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        Departments::where('id', $_POST['department_id'])->update($updateArray);
        return redirect('/department')->with('success','Department updated successfully!');
    }


    public function delete(Request $request)
    {
        $deleteArray = array(
            'status' => 'inactive',
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        Departments::where('id', $request['department_delete_id'])->update($deleteArray);
        return redirect('/department')->with('success','Department deleted successfully!');

    }

    public function isDepartmentExists(Request $request)
    { 
        $status = 'active';
        $where = ['status'=>$status];
        if(isset($request['id']))
        {
            $where['name'] = $request['department_name'];
            $count = Departments::where($where)->where('id', '!=', $request['id'])->get();
        }
        else
        {
            $where['name'] = $request['department'];
            $count = Departments::where($where)->get();
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
