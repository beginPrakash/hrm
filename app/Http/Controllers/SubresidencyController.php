<?php

namespace App\Http\Controllers;
use App\Models\Subresidency;
use App\Models\Residency;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Session;

class SubresidencyController extends Controller
{
    public function __construct()
    {
        $this->title = 'Licence';
    }

    public function index()
    { 
        $title = $this->title;
        if (request()->ajax())
        {
            return datatables()->of(Subresidency::with('Residency')->where('status','active')->get())
                ->setRowId(function ($subresidency)
                {
                    return $subresidency->id;
                })
                ->addColumn('subresidency', function ($subresidency)
                {
                    return ucfirst($subresidency->name) ?? '';
                })
                ->addColumn('residency', function ($row)
                { 
                    return ucfirst($row->Residency->name)?? '';
                })
                ->addColumn('action', function ($subresidency)
                {
                    $encodedData = base64_encode(json_encode($subresidency));
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
        return view('edbr.subresidency', compact('title','allCompanies'));
    }
    
    public function store()
    {
        $company_id  = Session::get('company_id');
        $insertArray = array(
            'name' => $_POST['subresidency'],
            'residency' => $_POST['residency'],
            'company_id'    =>  $company_id
        );

        Subresidency::insert($insertArray);
        return redirect('/subresidency')->with('success', 'Sub Residency added successfully!');

    } 

    public function update()
    {
        $updateArray = array(
            'name' => $_POST['subresidency_name'],
            'residency'  =>  $_POST['residency'],
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        Subresidency::where('id', $_POST['subresidency_id'])->update($updateArray);
        return redirect('/subresidency')->with('success','Sub Residency updated successfully!');

    }

    public function delete()
    {
        $deleteArray = array(
            'status' => 'inactive',
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        Subresidency::where('id', $_POST['subresidency_id'])->update($deleteArray);
        return redirect('/subresidency')->with('success','Sub Residency deleted successfully!');

    }

    public function isSubresidencyExists(Request $request)
    { 
        $status = 'active';
        $where = ['status'=>$status];
        if(isset($request['id']))
        {
            $where['name'] = $request['subresidency_name'];
            $count = Subresidency::where($where)->where('id', '!=', $request['id'])->where('residency', $request['residency'])->get();
        }
        else
        {
            $where['name'] = $request['subresidency'];
            $count = Subresidency::where($where)->where('residency', $request['residency'])->get();
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
