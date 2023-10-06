<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Session;

use App\Models\Residency;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->title = 'Company Settings';
    }

    public function companySettings()
    { 
        $title = $this->title;
        if (request()->ajax())
        {
            return datatables()->of(Residency::where('status','active')->get())
                ->setRowId(function ($residency)
                {
                    return $residency->id;
                })
                ->addColumn('name', function ($row)
                { 
                    $name = '';
                    if($row->logo != NULL)
                    {
                        $name = '<img src="uploads/logo/'.$row->logo.'">';
                    }
                    $name .= ucfirst($row->name)?? '';
                    return $name;
                })
                ->addColumn('city', function ($row)
                { 
                    return ucfirst($row->city)?? '';
                })
                ->addColumn('email', function ($row)
                { 
                    return ucfirst($row->email)?? '';
                })
                ->addColumn('phone', function ($row)
                { 
                    return ucfirst($row->phone_number)?? '';
                })
                ->addColumn('website', function ($row)
                { 
                    return ucfirst($row->website)?? '';
                })
                ->addColumn('action', function ($residency)
                {
                    $encodedData = base64_encode(json_encode($residency));
                    $button = '<div class="dropdown dropdown-action pull-right">
                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                    <div class="dropdown-menu dropdown-menu-right">';
                    // if (auth()->user()->can('edit-department'))
                    // {
                        $button .= '<a class="dropdown-item editButton" href="company-settings-edit/'.$residency->id.'"><i class="fa fa-pencil m-r-5"></i> Edit</a>';
                    // }
                    // if (auth()->user()->can('delete-department'))
                    // {
                        // $button .= '<a class="dropdown-item deleteButton" href="#" data-bs-toggle="modal" data-bs-target="#delete_form" data-data="'.$encodedData.'"><i class="fa fa-trash-o m-r-5" ></i> Delete</a>';
                    // }
                    $button .= '</div></div>';
                    return $button;

                })
                ->rawColumns(['action', 'name'])
                ->make(true);

        }
        return view('settings.residencysettings', compact('title'));
    }

    public function companySettingsEdit(Request $request)
    {
        $title = $this->title.' Update';
        $residency = Residency::where('id',$request->id)->first();
        return view('settings.residencysettingsUpdate', compact('title', 'residency'));
    }

    public function companySettingsUpdate(Request $request)
    {
        $updateArray = array(
            'name'      => $request->name,
            'address'   =>  $request->address,
            'country'   =>  $request->country,
            'city'      =>  $request->city,
            'state'     =>  $request->state,
            'postal_code'  =>  $request->postal_code,
            'email'     =>  $request->email,
            'phone_number'  =>  $request->phone_number,
            'fax'       =>  $request->fax,
            'website'   =>  $request->website,
            'created_at'    =>  date('Y-m-d h:i:s')
        );

        if($request->has('image1')) 
        {
            $image = $request->file('image1');
            $filename = $image->getClientOriginalName();
            $image->move(public_path('uploads/logo'), $filename);
            $updateArray['logo'] = $filename;
        } 

        Residency::where('id', $request->id)->update($updateArray);
        return redirect('/company-settings')->with('success','Settings updated successfully!');
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
