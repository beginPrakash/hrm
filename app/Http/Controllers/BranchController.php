<?php

namespace App\Http\Controllers;
use App\Models\Branch;
use App\Models\Residency;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Session;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->title = 'Branch';
    }

    public function index()
    {
        $title = $this->title;
        if (request()->ajax())
        {
            return datatables()->of(Branch::with('Residency')->where('status','active')->get())
                ->setRowId(function ($branch)
                {
                    return $branch->id;
                })
                ->addColumn('branch', function ($branch)
                {
                    return ucfirst($branch->name) ?? '';
                })
                ->addColumn('residency', function ($row)
                { 
                    return ucfirst($row->Residency->name)?? '';
                })
                ->addColumn('action', function ($branch)
                {
                    // print_r();
                    $encodedData = base64_encode(json_encode($branch));
                    $button = '<div class="dropdown dropdown-action pull-right">
                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                    <div class="dropdown-menu dropdown-menu-right">';
                    // if (auth()->user()->can('edit-department'))
                    // {
                        $button .= '<a class="dropdown-item editButton"  href="#" data-bs-toggle="modal" data-bs-target="#edit_form" data-data="'.$encodedData.'"><i class="fa fa-pencil m-r-5"></i> Edit</a>';
                    // }
                    // if (auth()->user()->can('delete-department'))
                    // {
                        $button .= '<a class="dropdown-item deleteButton" href="#" data-bs-toggle="modal" data-bs-target="#delete_form" data-data="'.$encodedData.'"><i class="fa fa-trash-o m-r-5" ></i> Delete</a>';
                    // }
                    $button .= '</div></div>';
                    return $button;

                })
                ->rawColumns(['action'])
                ->make(true);

        }
        $allCompanies = Residency::where('status','active')->get();
        return view('edbr.branch', compact('title','allCompanies'));
    }
    //  public function index()
    // {
    //     $allBranch = Branch::where('status','active')->get();
    //     return view('edbr.Branch', compact('allBranch'));
    // } 
    public function store()
    {
        $company_id  = Session::get('company_id');
        $insertArray = array(
            'name'        =>  $_POST['branch'],
            'residency'   => $_POST['residency'],
            'company_id'  =>  $company_id,
            'created_at'  =>  date('Y-m-d h:i:s')
        );

        Branch::insert($insertArray);
        return redirect('/branch')->with('success','Branch added successfully!');
    }


    public function update()
    {
        $updateArray = array(
            'name' => $_POST['branch_name'],
            'residency'   => $_POST['residency'],
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        Branch::where('id', $_POST['branch_id'])->update($updateArray);
        return redirect('/branch')->with('success','Branch Updated successfully!');

    }

    public function delete()
    {
        $deleteArray = array(
            'status' => 'inactive',
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        Branch::where('id', $_POST['branch_id'])->update($deleteArray);
        return redirect('/branch')->with('success','Branch deleted successfully!');

    }

    public function isBranchExists(Request $request)
    { 
        $status = 'active';
        $where = ['status'=>$status];
        if(isset($request['id']))
        {
            $where['name'] = $request['branch_name'];
            $count = Branch::where($where)->where('id', '!=', $request['id'])->where('residency', $request['residency'])->get();
        }
        else
        {
            $where['name'] = $request['branch'];
            $count = Branch::where($where)->where('residency', $request['residency'])->get();
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
