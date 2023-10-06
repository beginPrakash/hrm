<?php

namespace App\Http\Controllers;
use App\Models\Residency;

use Illuminate\Http\Request;
use Session;

class ResidencyController extends Controller
{
    public function __construct()
    {
        $this->title = 'Company';
    }

    public function index()
    { 
        $title = $this->title;
        if (request()->ajax())
        {
            return datatables()->of(Residency::where('status','active')->get())
                ->setRowId(function ($residency)
                {
                    return $residency->id;
                })
                ->addColumn('residency', function ($residency)
                {
                    return $residency->name ?? '';
                })
                ->addColumn('action', function ($residency)
                {
                    $encodedData = base64_encode(json_encode($residency));
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

        return view('edbr.residency', compact('title'));
    }
    //
    public function store()
    {
        $company_id  = Session::get('company_id');
        $insertArray = array(
            'name'  =>  $_POST['residency'],
            'company_id'    =>  $company_id,
        );

        Residency::insert($insertArray);
        return redirect('/residency')->with('success','Company added successfully!');
    }

    public function update()
    {
        $updateArray = array(
            'name' => $_POST['residency_name'],
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        Residency::where('id', $_POST['residency_id'])->update($updateArray);
        return redirect('/residency')->with('success','Company updated successfully!');

    }

    public function delete()
    {
        $deleteArray = array(
            'status' => 'inactive',
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        Residency::where('id', $_POST['residency_id'])->update($deleteArray);
        return redirect('/residency')->with('success','Company deleted successfully!');

    }

    public function isCompanyExists(Request $request)
    { 
        $status = 'active';
        $where = ['status'=>$status];
        if(isset($request['id']))
        {
            $where['name'] = $request['residency_name'];
            $count = Residency::where($where)->where('id', '!=', $request['id'])->get();
        }
        else
        {
            $where['name'] = $request['residency'];
            $count = Residency::where($where)->get();
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
