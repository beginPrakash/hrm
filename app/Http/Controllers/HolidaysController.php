<?php

namespace App\Http\Controllers;
use App\Models\Holidays;


use Illuminate\Http\Request;
use Session;

class HolidaysController extends Controller
{
    public function __construct()
    {
        $this->title = 'Holidays';
    }

    public function index(Request $request)
    { 
        $title = $this->title;
        if (request()->ajax())
        {
            $data = Holidays::where('status','active');
            return datatables()->of($data)
                ->setRowId(function ($holidays)
                {
                    return $holidays->id;
                })
                ->addColumn('title', function ($holidays)
                {
                    return ucfirst($holidays->title) ?? '';
                })
                ->addColumn('holiday_date', function ($holidays)
                {
                    return date('d-m-Y', strtotime($holidays->holiday_date)) ?? '';
                })
                ->addColumn('holiday_day', function ($holidays)
                {
                    return ucfirst($holidays->holiday_day) ?? '';
                })
                ->addColumn('action', function ($holidays)
                {
                    $button = '';
                    if(date('Y-m-d') < $holidays->holiday_date)
                    {
                        $encodedData = base64_encode(json_encode($holidays));
                        $button = '<div class="dropdown dropdown-action pull-right">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">';
                        // if (auth()->user()->can('edit-department'))
                        // {
                            $button .= '<a class="dropdown-item editButton"  href="#" data-bs-toggle="modal" data-bs-target="#edit_holiday" data-data="'.$encodedData.'"><i class="fa fa-pencil m-r-5"></i> Edit</a>';
                        // }
                        // if (auth()->user()->can('delete-holiday'))
                        // {
                            $button .= '<a class="dropdown-item deleteButton" href="#" data-bs-toggle="modal" data-bs-target="#delete_holiday" data-data="'.$encodedData.'"><i class="fa fa-trash-o m-r-5" ></i> Delete</a>';
                        // }
                        $button .= '</div></div>';
                    }
                    
                    return $button;

                })
                ->filter(function ($instance) use ($request) {
                    $instance->whereYear('holiday_date', $request->get('hyear'))->orderBy('holiday_date', 'ASC');                       
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('policies.holidays', compact('title'));
    }

    public function store()
    { 
        $company_id  = Session::get('company_id');
        $insertArray = array(
            'title'          =>  $_POST['title'],
            'holiday_date'   =>  date('Y-m-d', strtotime($_POST['holiday_date'])),
            'holiday_day'    =>  date('l', strtotime($_POST['holiday_date'])),
            'company_id'     =>  $company_id,
            'created_at'     =>  date('Y-m-d h:i:s')
        );

        Holidays::insert($insertArray);
        return redirect('/holidays')->with('success','Holiday created successfully!');
    }
    public function update()
    {
        $company_id  = Session::get('company_id');
        $updateArray    = array(
            'title'          =>  $_POST['title'],
            'holiday_date'   =>  date('Y-m-d', strtotime($_POST['holiday_date'])),
            'holiday_day'    =>  date('l', strtotime($_POST['holiday_date'])),
            'company_id'     =>  $company_id,
            'updated_at'     =>  date('Y-m-d h:i:s')
        );
        Holidays::where('id', $_POST['holiday_id'])->update($updateArray);
        return redirect('/holidays')->with('success','Holiday updated successfully!');
    }


    public function delete(Request $request)
    {
        $deleteArray = array(
            'status' => 'inactive',
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        Holidays::where('id', $request['holiday_delete_id'])->update($deleteArray);
        return redirect('/holidays')->with('success','Holiday deleted successfully!');

    }

}
