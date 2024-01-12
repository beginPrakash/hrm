<?php

namespace App\Http\Controllers;
use App\Models\Departments;
use App\Models\Shifting;
use App\Models\Employee;
use App\Models\Addschedule;
use Carbon\Carbon;
use Session;


use Illuminate\Http\Request;

class ShiftingController extends Controller
{
    public function __construct()
    {
        $this->title = 'Shifts';
    }
    public function index()
    {
        $title = $this->title;
        if (request()->ajax())
        {
            return datatables()->of(Shifting::where('status','active')->where('parent_shift',0)->get())
                ->setRowId(function ($shiftsArray)
                {
                    return $shiftsArray->id;
                })
                ->addColumn('shift_name', function ($shiftsArray)
                {
                    $cod_shift = _get_cod_shift_name($shiftsArray->id);
                    if(!empty($cod_shift)):
                        $shift = $shiftsArray->suid.' , '.$cod_shift;
                    else:
                        $shift = $shiftsArray->suid;
                    endif;
                    return ($shiftsArray->shift_name)?ucfirst($shiftsArray->shift_name).' ('.$shift.')' : '';
                })
                ->addColumn('min_start_time', function ($shiftsArray)
                {
                    return ucfirst($shiftsArray->min_start_time) ?? '';
                })
                ->addColumn('start_time', function ($shiftsArray)
                {
                    return ucfirst($shiftsArray->start_time) ?? '';
                })
                ->addColumn('max_start_time', function ($shiftsArray)
                {
                    return ucfirst($shiftsArray->max_start_time) ?? '';
                })
                ->addColumn('min_end_time', function ($shiftsArray)
                {
                    return ucfirst($shiftsArray->min_end_time) ?? '';
                })
                ->addColumn('end_time', function ($shiftsArray)
                {
                    return ucfirst($shiftsArray->end_time) ?? '';
                })
                ->addColumn('max_end_time', function ($shiftsArray)
                {
                    return ucfirst($shiftsArray->max_end_time) ?? '';
                })
                ->addColumn('break_time', function ($shiftsArray)
                {
                    return ($shiftsArray->break_time > 0)?(ucfirst($shiftsArray->break_time) ?? '').'mins':'';
                })
                ->addColumn('status', function ($shiftsArray)
                {
                    return ucfirst($shiftsArray->status) ?? '';
                })
                ->addColumn('action', function ($shiftsArray)
                {
                    if($shiftsArray->id > 3 && $shiftsArray->id < 7 || $shiftsArray->id > 9)
                    {
                        $encodedData = base64_encode(json_encode($shiftsArray));
                        $button = '<div class="dropdown dropdown-action pull-right">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">';
                        // if (auth()->user()->can('edit-department'))
                        // {
                            $button .= '<a class="dropdown-item editButton"  href="#" data-bs-toggle="modal" data-bs-target="#edit_shift" data-data="'.$encodedData.'"><i class="fa fa-pencil m-r-5"></i> Edit</a>';
                        // }
                        // if (auth()->user()->can('delete-shift'))
                        // {
                            $button .= '<a class="dropdown-item deleteButton" href="#" data-bs-toggle="modal" data-bs-target="#delete_shift" data-data="'.$encodedData.'"><i class="fa fa-trash-o m-r-5" ></i> Delete</a>';
                        // }
                        $button .= '</div></div>';
                        return $button;
                    }

                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $department   = Departments::get();
        $employees    = Employee::get();
        $shifts       = Shifting::get();
        $scheduleData = Shifting::orderBy('id', 'desc')->get();
        //  return view('lts.shifting',['title' => $title, 'department' => $department,'employees' => $employees,'shifts'=>$shifts,'scheduleData'=>$scheduleData]);
        return view('lts.shifting', compact('title', 'scheduleData', 'department', 'employees', 'shifts'));
    }
    public function store(Request $request)
    {
        $company_id  = Session::get('company_id');
        $lastid = $this->getLastIdShift();
        $insertArray = array(
            'suid'              =>  'SH'.(100+$lastid+1),
            'company_id'        =>  $company_id,
            'shift_name'        =>  $request->shift_name,
            'min_start_time'    =>  date('h:i:s a', strtotime($request->min_start_time)),
            'start_time'        =>  date('h:i:s a', strtotime($request->start_time)),
            'max_start_time'    =>  date('h:i:s a', strtotime($request->max_start_time)),
            'min_end_time'      =>  date('h:i:s a', strtotime($request->min_end_time)),
            'end_time'          =>  date('h:i:s a', strtotime($request->end_time)),
            'max_end_time'      =>  date('h:i:s a', strtotime($request->max_end_time)),
            'break_time'        =>  $request->break_time,
            'recurring_shift'   =>  $request->recurring_shift,
            'repeat_every'      =>  $request->repeat_every,
            'week_day'          =>  (isset($request->week_day) && !empty($request->week_day)) ? implode(',',$request->week_day) : '',
            'end_on'            =>  (isset($request->end_on) && !empty($request->end_on)) ? date('Y-m-d', strtotime(str_replace('/','-',$request->end_on))):NULL,
            'indefinite'        =>  $request->indefinite,
            'tag'               =>  $request->tag,
            'note'              =>  $request->note,
            'is_cod'            =>  '0',
            'is_twoday_shift'   => $request->is_twoday_shift ?? '0',
            'created_at'        =>  date('Y-m-d h:i:s')
        );
        // echo '<pre>';print_r($insertArray);exit;
        $shiftid = Shifting::create($insertArray);
        if(!empty($shiftid)):
            $lastid = $this->getLastIdShift();
            $insertArray = array(
                'suid'              =>  'SH'.(100+$lastid+1),
                'company_id'        =>  $company_id,
                'shift_name'        =>  'COD-'.$request->shift_name,
                'min_start_time'    =>  date('h:i:s a', strtotime($request->min_start_time)),
                'start_time'        =>  date('h:i:s a', strtotime($request->start_time)),
                'max_start_time'    =>  date('h:i:s a', strtotime($request->max_start_time)),
                'min_end_time'      =>  date('h:i:s a', strtotime($request->min_end_time)),
                'end_time'          =>  date('h:i:s a', strtotime($request->end_time)),
                'max_end_time'      =>  date('h:i:s a', strtotime($request->max_end_time)),
                'break_time'        =>  $request->break_time,
                'recurring_shift'   =>  $request->recurring_shift,
                'repeat_every'      =>  $request->repeat_every,
                'week_day'          =>  (isset($request->week_day) && !empty($request->week_day)) ? implode(',',$request->week_day) : '',
                'end_on'            =>  (isset($request->end_on) && !empty($request->end_on)) ? date('Y-m-d', strtotime(str_replace('/','-',$request->end_on))):NULL,
                'indefinite'        =>  $request->indefinite,
                'tag'               =>  $request->tag,
                'note'              =>  $request->note,
                'is_cod'            =>  '1',
                'is_twoday_shift'   => $request->is_twoday_shift ?? '0',
                'parent_shift' => $shiftid->id,
                'created_at'        =>  date('Y-m-d h:i:s')
            );
            Shifting::create($insertArray);
        endif;
        return redirect('/shifting')->with('success', 'Shifting created successfully!');
    }

    public function update(Request $request)
    { 
        
        $company_id  = Session::get('company_id');//echo $company_id;exit;
        $updateArray = array(
            'company_id'        =>  $company_id,
            'min_start_time'    =>  date('h:i:s a', strtotime($request->min_start_time)),
            'start_time'        =>  date('h:i:s a', strtotime($request->start_time)),
            'max_start_time'    =>  date('h:i:s a', strtotime($request->max_start_time)),
            'min_end_time'      =>  date('h:i:s a', strtotime($request->min_end_time)),
            'end_time'          =>  date('h:i:s a', strtotime($request->end_time)),
            'max_end_time'      =>  date('h:i:s a', strtotime($request->max_end_time)),
            'break_time'        =>  $request->break_time,
            'recurring_shift'   =>  $request->recurring_shift,
            'repeat_every'      =>  $request->repeat_every,
            'week_day'          =>  (isset($request->week_day) && !empty($request->week_day)) ? implode(',',$request->week_day) : '',
            'end_on'            =>  (isset($request->end_on) && !empty($request->end_on)) ? date('Y-m-d', strtotime(str_replace('/','-',$request->end_on))):NULL,
            'indefinite'        =>  $request->indefinite,
            'tag'               =>  $request->tag,
            'note'              =>  $request->note,
            'is_twoday_shift'   => $request->is_twoday_shift ?? '0',
            'created_at'        =>  date('Y-m-d h:i:s')
        );

        $check_shift = Shifting::where('id', $_POST['id'])->orWhere('parent_shift', $_POST['id'])->get();
        if(!empty($check_shift)):
            $shift_name = $request->shift_name;
            $updateArray = array(
                'shift_name'        =>  $shift_name ?? $request->shift_name,
            );
            Shifting::where('id', $check_shift[0]->id)->update($updateArray);

            $shift_name = 'COD-'.$request->shift_name;
            $updateArray = array(
                'shift_name'        =>  $shift_name ?? $request->shift_name,
            );
            Shifting::where('parent_shift', $check_shift[0]->id)->update($updateArray);
        endif;
        
        return redirect('/shifting')->with('success','Shift updated successfully!');
    }

    public function addschedule(Request $request)
    {
        $create = $request->all();
        $create['date'] = Carbon::parse($request->date)->format('Y-m-d h:i:s');
        unset($create["_token"]);
        $shiftid = Addschedule::create($create);
    }
    public function getShiftbyId($id)
    {
       $getSchedule = Addschedule::select("*")->with('addschedule_shifting')->where('addschedule.id', '=', $id)->get();
       echo json_encode($getSchedule);
    }

    public function delete(Request $request)
    {
        $deleteArray = array(
            'status' => 'inactive',
            'updated_at'  =>  date('Y-m-d h:i:s')
        );
        Shifting::where('id', $request['shift_delete_id'])->orWhere('parent_shift', $request['shift_delete_id'])->update($deleteArray);
        return redirect('/shifting')->with('success','Shift deleted successfully!');

    }

    public function import(Request $request)
    { 
        //validate file
        $validate = $this->validateCSV($request->file('shift_file'));
        if($validate['status'] == 1)
        {
            //call excel/csv function
            $import = $this->importCSV($request->file('shift_file'));
            return redirect()->back()->with("success", 'Shifts imported successfully.');
        }
        else
        {
            return redirect()->back()->with("error", $validate['message']);
        }
    }

    private function validateCSV($file)
    {
        // File Details 
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();

        // Valid File Extensions
        $valid_extension = array("csv");

        // 5MB in Bytes
        $maxFileSize = 5097152; 

        // Check file extension
        if(in_array(strtolower($extension),$valid_extension))
        {
            // Check file size
            if($fileSize <= $maxFileSize)
            {
                $return['message'] = 'Import Successful.';
                $return['status'] = 1;
            }
            else
            {
                $return['message'] = 'File too large. File must be less than 2MB.';
                $return['status'] = 2;
            }
        }
        else
        {
            $return['message'] = 'Invalid File Extension.';
            $return['status'] = 2;
        }
        return $return;
    }

    private function importCSV($file)
    {
        // File Details 
        $filename = $file->getClientOriginalName();
        
        // File upload location
        $location = 'uploads/shifts';
        // Upload file
        $file->move(public_path($location),$filename);

        // Import CSV to Database
        $filepath = public_path($location."/".$filename);

        // Reading file
        $file = fopen($filepath,"r");

        $importData_arr = array();
        $i = 0;

        while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) 
        {
            $num = count($filedata );

            // Skip first row
            if($i == 0)
            {
                $i++;
                continue; 
            }

            for ($c=0; $c < $num; $c++)
            {
                $importData_arr[$i][] = $filedata [$c];
            }
            $i++;
        }
        fclose($file);

        $company_id  = Session::get('company_id');
        
        // Insert to MySQL database
        foreach($importData_arr as $importData)
        {
            $lastid = $this->getLastIdShift();
            $insertArray = array(
                'suid'              =>  'SH'.(100+$lastid+1),
                'company_id'        =>  $company_id,
                'shift_name'        =>  $importData[1],
                'min_start_time'    =>  date('h:i:s a', strtotime($importData[2])),
                'start_time'        =>  date('h:i:s a', strtotime($importData[3])),
                'max_start_time'    =>  date('h:i:s a', strtotime($importData[4])),
                'min_end_time'      =>  date('h:i:s a', strtotime($importData[5])),
                'end_time'          =>  date('h:i:s a', strtotime($importData[6])),
                'max_end_time'      =>  date('h:i:s a', strtotime($importData[7])),
                'break_time'        =>  $importData[8],
                // 'recurring_shift'   =>  $request->recurring_shift,
                // 'repeat_every'      =>  $request->repeat_every,
                // 'week_day'          =>  implode(',',$request->week_day),
                'end_on'            =>  date('Y-m-d', strtotime($importData[9])),
                'is_cod' => '0',
                // 'indefinite'        =>  $request->indefinite,
                // 'tag'               =>  $request->tag,
                // 'note'              =>  $request->note,
                'created_at'        =>  date('Y-m-d h:i:s')
            );
           
            $shiftid = Shifting::create($insertArray);
            if(!empty($shiftid)):
                $lastid = $this->getLastIdShift();
                $insertArray = array(
                    'suid'              =>  'SH'.(100+$lastid+1),
                    'company_id'        =>  $company_id,
                    'shift_name'        =>  $importData[1],
                    'min_start_time'    =>  date('h:i:s a', strtotime($importData[2])),
                    'start_time'        =>  date('h:i:s a', strtotime($importData[3])),
                    'max_start_time'    =>  date('h:i:s a', strtotime($importData[4])),
                    'min_end_time'      =>  date('h:i:s a', strtotime($importData[5])),
                    'end_time'          =>  date('h:i:s a', strtotime($importData[6])),
                    'max_end_time'      =>  date('h:i:s a', strtotime($importData[7])),
                    'break_time'        =>  $importData[8],
                    // 'recurring_shift'   =>  $request->recurring_shift,
                    // 'repeat_every'      =>  $request->repeat_every,
                    // 'week_day'          =>  implode(',',$request->week_day),
                    'end_on'            =>  date('Y-m-d', strtotime($importData[9])),
                    'is_cod' => '1',
                    'parent_shift' => $shiftid->id,
                    'created_at'        =>  date('Y-m-d h:i:s')
                );
                //dd($insertArray);
                Shifting::create($insertArray);
            endif;
        }
        $return['message'] = 'Import Successful.';
        $return['status'] = 1;
        return $return;
    }

    public function getLastIdShift()
    {
        $last_inserted_id = Shifting::orderBy('id', 'desc')->first();
        if(!empty($last_inserted_id))
        {
            return $last_inserted_id->id;
        }
        return 1;
    }
}
