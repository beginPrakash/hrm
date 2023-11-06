<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Departments;
use App\Models\Shifting;
use App\Models\Scheduling;
use App\Models\Overtime;
use App\Models\Holidays;
use APp\Models\AttendanceDetails;

class SchedulingController extends Controller
{
	public function __construct()
    {
        $this->title = 'Scheduling';
    }

    public function index()
    {
    	$title = $this->title;

        if(date('D')!='Mon')
        {    
         //take the last monday
          $startDate = date('Y-m-d',strtotime('last Monday'));    

        }else{
            $startDate = date('Y-m-d');   
        }
        $search['from_date'] = $startDate;

        $search = [];
        $where = array('status' => 'active');
        $empwhere = array('status' => 'active');
        $depwhere = array('status' => 'active');
        if(isset($_POST['search']))
        {
            if(isset($_POST['employee']) && $_POST['employee']!='')
            {
                $where['user_id'] = $_POST['employee'];
                $search['emp'] = $_POST['employee'];
            }
            // if(isset($_POST['department']) && $_POST['department']!='')
            // {
            //     $depwhere['id'] = $_POST['department'];
            //     $search['department'] = $_POST['department'];
            // }
            if(isset($_POST['from_date']) && $_POST['from_date']!='')
            {
                // $where['scheduling.shift_on'] = ">= ".date('Y-m-d', strtotime($_POST['from_date']));
                $search['from_date'] = $_POST['from_date'];
                $startDate = date('Y-m-d', strtotime($_POST['from_date']));
            }
            // if(isset($_POST['to_date']) && $_POST['to_date']!='')
            // {
            //     $where['to_date'] = "<= ".date('d/m/Y', strtotime($_POST['to_date']));
            //     $search['to_date'] = $_POST['to_date'];
            // }
        }

        // $departmentList = Departments::where('status', 'active')->get();
        $enddate = date('Y-m-d', strtotime('+6 days', strtotime($startDate)));
        $overtimeDetails = Overtime::get()->first();
        $phDetails = Holidays::whereBetween('holiday_date', [$startDate, $enddate])->get()->pluck('holiday_date')->toArray();
    	$department   = Departments::where($depwhere)->get();
        $shifts       = Shifting::where('status', 'active')->get();
        // $scheduling   = Scheduling::with('employees')->where($where)->get();
    	$scheduling   = Employee::with('schedules')->where($where)->get();
    	$allEmployees = Employee::with(["employee_designation", 'employee_department'])->where('status','active')->where($empwhere)->get();
        // echo '<pre>';print_r($scheduling);exit;
    	return view('lts.scheduling', compact('title', 'allEmployees', 'department', 'shifts', 'scheduling', 'search', 'overtimeDetails', 'phDetails', 'startDate'));
    }

    public function store(Request $request)
    {
        $company_id  = Session::get('company_id');
        // echo '<pre>';print_r($_POST);//exit;
        if(isset($request->employee_addschedule_id))
        {
            $empArray[] = $request->employee_addschedule_id;
        }
        if(isset($request->employee_addschedule) && count($request->employee_addschedule) > 0 && $request->employee_addschedule[0]!='')
        {
            $empArray = $request->employee_addschedule;
        }
        // echo '<pre>';print_r($empArray);exit;
        foreach($empArray as $emp)
        {
            $insertArray = array(
                'company_id'        =>  $company_id,
                'department'        =>  (isset($request->department_addschedule))?$request->department_addschedule:0,
                'employee'        	=>  $emp,//($request->employee_addschedule!='')?$request->employee_addschedule:$request->employee_addschedule_id,
                'shift_on'        	=>  date('Y-m-d', strtotime(str_replace('/','-',$request->shift_date))),
                'shift'        		=>  $request->shift_addschedule,
                'min_start_time'    =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9 || $request->shift_addschedule > 9)?_convert_time_to_12hour_format(str_replace(' pm','',$request->min_start_time)):NULL,
                'start_time'        =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9 || $request->shift_addschedule > 9)?_convert_time_to_12hour_format(str_replace(' pm','',$request->start_time)):NULL,
                'max_start_time'    =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9 || $request->shift_addschedule > 9)?_convert_time_to_12hour_format(str_replace(' pm','',$request->max_start_time)):NULL,
                'min_end_time'      =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9 || $request->shift_addschedule > 9)?_convert_time_to_12hour_format(str_replace(' pm','',$request->min_end_time)):NULL,
                'end_time'          =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9 || $request->shift_addschedule > 9)?_convert_time_to_12hour_format(str_replace(' pm','',$request->end_time)):NULL,
                'max_end_time'      =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9 || $request->shift_addschedule > 9)?_convert_time_to_12hour_format(str_replace(' pm','',$request->max_end_time)):NULL,
                'break_time'        =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9 || $request->shift_addschedule > 9)?$request->break_time:NULL,
                'extra_hours'   	=>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9 || $request->shift_addschedule > 9)?$request->extra_hours:0,
                'publish'      		=>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9 || $request->shift_addschedule > 9)?$request->publish:0,
                'created_at'        =>  date('Y-m-d h:i:s'),
                'status'			=>	'active'
            );//echo '<pre>';print_r($insertArray);
            $schedule_date = date('Y-m-d', strtotime(str_replace('/','-',$request->shift_date)));
            $is_schedule_exists = Scheduling::where('employee',$emp)->where('shift_on',$schedule_date)->delete();
            $shiftid = Scheduling::create($insertArray);
            //get schedule data
            $shiftDetails = Scheduling::find($shiftid->id ?? '');
            if(!empty($shiftDetails)):
                $att_date = date('Y-m-d', strtotime($shiftDetails->shift_on));
                $flag = _check_green_icon_attendance($att_date,$shiftDetails->employee);
                if($flag === 0):
                    $att_details = AttendanceDetails::where('user_id',$shiftDetails->employee)->where('attendance_on',$att_date)->where('punch_state','clockin')->first();
                    $save_data = save_schedule_overtime_hours($shiftDetails->employee,$att_date,$att_details->start_time,$att_details->end_time);
                else:
                    $save_data = AttendanceDetails::where('user_id',$shiftDetails->employee)->where('attendance_on',$att_date)->where('punch_state','clockin')->first();
                    if(!empty($save_data)):
                        $save_data->schedule_hours = NULL;
                        $save_data->overtime_hours = NULL;
                        $save_data->save();
                    endif;
                endif;  
            endif;
        }
        // exit;
        return redirect('/scheduling')->with('success', 'Schedule created successfully!')->with('sdate' , $request->add_start_from_date);
    }

    public function update(Request $request)
    { 
        $company_id  = Session::get('company_id');
        $insertArray = array(
            'company_id'        =>  $company_id,
            // 'department'        =>  (isset($request->department_addschedule))?$request->department_addschedule:0,
            'employee'          =>  ($request->employee_addschedule!='')?$request->employee_addschedule:$request->employee_addschedule_id,
            'shift_on'          =>  date('Y-m-d', strtotime(str_replace('/','-',$request->shift_date))),
            'shift'             =>  $request->shift_addschedule,
            'min_start_time'    =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9)?_convert_time_to_12hour_format(str_replace(' pm','',$request->min_start_time)):NULL,
            'start_time'        =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9)?_convert_time_to_12hour_format(str_replace(' pm','',$request->start_time)):NULL,
            'max_start_time'    =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9)?_convert_time_to_12hour_format(str_replace(' pm','',$request->max_start_time)):NULL,
            'min_end_time'      =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9)?_convert_time_to_12hour_format(str_replace(' pm','',$request->min_end_time)):NULL,
            'end_time'          =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9)?_convert_time_to_12hour_format(str_replace(' pm','',$request->end_time)):NULL,
            'max_end_time'      =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9)?_convert_time_to_12hour_format(str_replace(' pm','',$request->max_end_time)):NULL,
            'break_time'        =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9)?$request->break_time:NULL,
            'extra_hours'       =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9)?$request->extra_hours:0,
            'publish'           =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9)?$request->publish:0,
            'updated_at'        =>  date('Y-m-d h:i:s'),
            'status'            =>  'active'
        );

        //echo '<pre>';print_r($request->schedule_id);exit;
        $schedule_date = date('Y-m-d', strtotime(str_replace('/','-',$request->shift_date)));
        $shiftid = Scheduling::where('id', $request->schedule_id)->update($insertArray);
        $is_schedule_exists = Scheduling::where('employee',$request->employee_addschedule_id ?? '')->where('shift_on',$schedule_date)->where('shift','!=',$request->shift_addschedule)->delete();
        //get schedule data
        $shiftDetails = Scheduling::find($request->schedule_id);
        if(!empty($shiftDetails)):
            $att_date = date('Y-m-d', strtotime($shiftDetails->shift_on));
            $flag = _check_green_icon_attendance($att_date,$shiftDetails->employee);
            if($flag === 0):
                $att_details = AttendanceDetails::where('user_id',$shiftDetails->employee)->where('attendance_on',$att_date)->where('punch_state','clockin')->first();
                $save_data = save_schedule_overtime_hours($shiftDetails->employee,$att_date,$att_details->start_time,$att_details->end_time);
            // elseif($flag == 2):
            //     $save_data = AttendanceDetails::where('user_id',$shiftDetails->employee)->where('attendance_on',$att_date)->where('punch_state','clockin')->first();
            //     if(!empty($save_data)):
            //         $save_data->schedule_hours = 8;
            //         $save_data->overtime_hours = 0;
            //         $save_data->save();
            //     endif;
            else:
                $save_data = AttendanceDetails::where('user_id',$shiftDetails->employee)->where('attendance_on',$att_date)->where('punch_state','clockin')->first();
                if(!empty($save_data)):
                    $save_data->schedule_hours = NULL;
                    $save_data->overtime_hours = NULL;
                    $save_data->save();
                endif;
            endif;  
        endif;
        return redirect('/scheduling')->with('success', 'Schedule updated successfully!')->with('sdate' , $request->add_start_from_date);
    }

    public function employeeByDepartment(Request $request)
    {
        $employees = Employee::where("department", $request->id)->where('status','active')->get();
        return response()->json($employees);
    }

    public function shiftDetails(Request $request)
    {
        $shift = Shifting::where("id", $request->id)->get();
        return response()->json($shift);
    }

    public function import(Request $request)
    { 
        //validate file
        $validate = $this->validateCSV($request->file('schedule_file'));
        if($validate['status'] == 1)
        {
            //call excel/csv function
            $import = $this->importCSV($request->file('schedule_file'));
            return redirect()->back()->with("success", 'Schedule imported successfully.');
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
        $location = 'uploads/schedules';
        // Upload file
        $file->move(public_path($location),$filename);

        // Import CSV to Database
        $filepath = public_path($location."/".$filename);

        // Reading file
        $file = fopen($filepath,"r");

        $importHeaderData_arr = array();
        $importData_arr = array();
        $i = 0;

        while (($filedata = fgetcsv($file)) !== FALSE) 
        {
            $num = count($filedata );

            for ($c=0; $c < $num; $c++)
            {
                if($i == 0)
                {
                    $importHeaderData_arr[] = $filedata [$c];
                }
                else
                {
                    $importData_arr[$i][] = $filedata [$c];
                }
            }
            $i++;
        }
        fclose($file);
//dd($importData_arr);
        //count details
        $totalColumns = count($importHeaderData_arr);

        if($totalColumns <= 4)
        {
            return null;
        }

        // $totaldates = $totalColumns - 4;

// echo '<pre>';print_r($totalColumns);echo '<br>';
// echo '<pre>';print_r($importHeaderData_arr);exit;
        $company_id  = Session::get('company_id');
        //selvan
        $freeShiftDepartments = array(15,10);
        // Insert to MySQL database
        foreach($importData_arr as $key => $importData)
        {
            //check empno exists, if not continue
            $userDetails = $this->getUserDetailsByEmployeeId($importData[1]);
            if(empty($userDetails))
            {
                continue;
            }
            $userId = $userDetails->user_id;//by employee id
            $departmentId = $userDetails->department;

            // loop through date_sub
            for($k=4; $k<$totalColumns; $k++)
            {
                // echo '<pre>';print_r(date('Y-m-d', strtotime(str_replace('/','-',$importHeaderData_arr[$k]))));exit;
                //check shift exists, if not create shift
                $explode_shift = explode('/', $importData[$k]);
                $shiftDetails = $this->getShiftDetailsById($explode_shift[1]);//echo $importData[$k];
                if(!empty($shiftDetails))
                {
                    $schedule_date = date('Y-m-d', strtotime(str_replace('/','-',$importHeaderData_arr[$k])));
                    //check schedule is exist or not for specific date
                    $is_schedule_exists = Scheduling::where('employee',$userId)->where('shift_on',$schedule_date)->delete();
                    
                    $shiftid = $shiftDetails->id;
                    $scheduleInsertArray = array(
                        'company_id'        =>  $company_id,
                        'department'        =>  $departmentId,
                        'employee'          =>  $userId,
                        'shift_on'          =>  date('Y-m-d', strtotime(str_replace('/','-',$importHeaderData_arr[$k]))),
                        'shift'             =>  $shiftid,
                        'min_start_time'    =>  _convert_time_to_12hour_format($shiftDetails->min_start_time),//date('h:i:s a', strtotime($importData[4])),
                        'start_time'        =>  _convert_time_to_12hour_format($shiftDetails->start_time),//date('h:i:s a', strtotime($importData[5])),
                        'max_start_time'    =>  _convert_time_to_12hour_format($shiftDetails->max_start_time),//date('h:i:s a', strtotime($importData[6])),
                        'min_end_time'      =>  _convert_time_to_12hour_format($shiftDetails->min_end_time),//date('h:i:s a', strtotime($importData[7])),
                        'end_time'          =>  _convert_time_to_12hour_format($shiftDetails->end_time),//date('h:i:s a', strtotime($importData[8])),
                        'max_end_time'      =>  _convert_time_to_12hour_format($shiftDetails->max_end_time),//date('h:i:s a', strtotime($importData[9])),
                        'break_time'        =>  $shiftDetails->break_time,//$importData[10],
                        // 'extra_hours'       =>  $request->extra_hours,
                        // 'publish'           =>  $request->publish,
                        'created_at'        =>  date('Y-m-d h:i:s'),
                        'status'            =>  'active'
                    );
                    //selvan
                    // if( $explode_shift[1] !="SH03"){
                    //     $shiftDetails = $this->getShiftDetailsById("SH03");
                    //     $shiftid = $shiftDetails->id;
                    //     $scheduleInsertArray = array(
                    //         'company_id'        =>  $company_id,
                    //         'department'        =>  $departmentId,
                    //         'employee'          =>  $userId,
                    //         'shift_on'          =>  date('Y-m-d', strtotime($importHeaderData_arr[$k])),
                    //         'shift'             =>  $shiftid,
                    //         // 'min_start_time'    =>  $shiftDetails->min_start_time,
                    //         // 'start_time'        =>  $shiftDetails->start_time,
                    //         // 'max_start_time'    =>  $shiftDetails->max_start_time,
                    //         // 'min_end_time'      =>  $shiftDetails->min_end_time,
                    //         // 'end_time'          =>  $shiftDetails->end_time,
                    //         // 'max_end_time'      =>  $shiftDetails->max_end_time,
                    //         // 'break_time'        =>  $shiftDetails->break_time,
                    //         'min_start_time'    =>  "0:00",
                    //         'start_time'        =>  "0:00",
                    //         'max_start_time'    =>  "0:00",
                    //         'min_end_time'      =>  "0:00",
                    //         'end_time'          =>  "0:00",
                    //         'max_end_time'      =>  "0:00",
                    //         'break_time'        =>  "0:00",
                    //         'created_at'        =>  date('Y-m-d h:i:s'),
                    //         'status'            =>  'active'
                    //     );

                    // }
                    // echo '<pre>';print_r($scheduleInsertArray);exit;
                    $shiftid = Scheduling::create($scheduleInsertArray);
                    //get schedule data
                    $shiftDetails = Scheduling::find($shiftid->id ?? '');
                    if(!empty($shiftDetails)):
                        $att_date = date('Y-m-d', strtotime($shiftDetails->shift_on));
                        $flag = _check_green_icon_attendance($att_date,$shiftDetails->employee);
                        if($flag === 0):
                            $att_details = AttendanceDetails::where('user_id',$shiftDetails->employee)->where('attendance_on',$att_date)->where('punch_state','clockin')->first();
                            $save_data = save_schedule_overtime_hours($shiftDetails->employee,$att_date,$att_details->start_time,$att_details->end_time);
                        else:
                            $save_data = AttendanceDetails::where('user_id',$shiftDetails->employee)->where('attendance_on',$att_date)->where('punch_state','clockin')->first();
                            if(!empty($save_data)):
                                $save_data->schedule_hours = NULL;
                                $save_data->overtime_hours = NULL;
                                $save_data->save();
                            endif;
                        endif;  
                    endif;
                }
            }
            
            // $departmentId = 0;
            // if(empty($shiftDetails))
            // {
            //     //create shift
            //     $insertArray = array(
            //         'company_id'        =>  $company_id,
            //         'shift_name'        =>  $importData[2],
            //         'min_start_time'    =>  date('h:i:s a', strtotime($importData[4])),
            //         'start_time'        =>  date('h:i:s a', strtotime($importData[5])),
            //         'max_start_time'    =>  date('h:i:s a', strtotime($importData[6])),
            //         'min_end_time'      =>  date('h:i:s a', strtotime($importData[7])),
            //         'end_time'          =>  date('h:i:s a', strtotime($importData[8])),
            //         'max_end_time'      =>  date('h:i:s a', strtotime($importData[9])),
            //         'break_time'        =>  $importData[10],
            //         // 'recurring_shift'   =>  $request->recurring_shift,
            //         // 'repeat_every'      =>  $request->repeat_every,
            //         // 'week_day'          =>  implode(',',$request->week_day),
            //         // 'end_on'            =>  date('Y-m-d', strtotime(str_replace('/','-',$request->end_on))),
            //         // 'indefinite'        =>  $request->indefinite,
            //         // 'tag'               =>  $request->tag,
            //         // 'note'              =>  $request->note,
            //         'created_at'        =>  date('Y-m-d h:i:s')
            //     );
            //     $shiftid = Shifting::create($insertArray);
            // }
            // else
            // {
            //     $shiftid = $shiftDetails->id;
            // }

            // $scheduleInsertArray = array(
            //     'company_id'        =>  $company_id,
            //     'department'        =>  $departmentId,
            //     'employee'          =>  $userId,
            //     'shift_on'          =>  date('Y-m-d', strtotime($importData[3])),
            //     'shift'             =>  $shiftid,
            //     'min_start_time'    =>  date('h:i:s a', strtotime($importData[4])),
            //     'start_time'        =>  date('h:i:s a', strtotime($importData[5])),
            //     'max_start_time'    =>  date('h:i:s a', strtotime($importData[6])),
            //     'min_end_time'      =>  date('h:i:s a', strtotime($importData[7])),
            //     'end_time'          =>  date('h:i:s a', strtotime($importData[8])),
            //     'max_end_time'      =>  date('h:i:s a', strtotime($importData[9])),
            //     'break_time'        =>  $importData[10],
            //     // 'extra_hours'       =>  $request->extra_hours,
            //     // 'publish'           =>  $request->publish,
            //     'created_at'        =>  date('Y-m-d h:i:s'),
            //     'status'            =>  'active'
            // );
            // $shiftid = Scheduling::create($scheduleInsertArray);
        }
        $return['message'] = 'Import Successful.';
        $return['status'] = 1;
        return $return;
    }

    private function importCSV_OLD($file)
    {
        // File Details 
        $filename = $file->getClientOriginalName();
        
        // File upload location
        $location = 'uploads/schedules';
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
            //check empno exists, if not continue
            $userDetails = $this->getUserDetailsByEmployeeId($importData[1]);
            if(empty($userDetails))
            {
                continue;
            }
            $userId = $userDetails->user_id;//by employee id
            $departmentId = $userDetails->department;

            //check shift exists, if not create shift
            $shiftDetails = $this->getShiftDetailsByName($importData[2]);echo $importData[2];echo '<pre>';print_r($shiftDetails);
            $departmentId = 0;
            if(empty($shiftDetails))
            {
                //create shift
                $insertArray = array(
                    'company_id'        =>  $company_id,
                    'shift_name'        =>  $importData[2],
                    'min_start_time'    =>  date('h:i:s a', strtotime($importData[4])),
                    'start_time'        =>  date('h:i:s a', strtotime($importData[5])),
                    'max_start_time'    =>  date('h:i:s a', strtotime($importData[6])),
                    'min_end_time'      =>  date('h:i:s a', strtotime($importData[7])),
                    'end_time'          =>  date('h:i:s a', strtotime($importData[8])),
                    'max_end_time'      =>  date('h:i:s a', strtotime($importData[9])),
                    'break_time'        =>  $importData[10],
                    // 'recurring_shift'   =>  $request->recurring_shift,
                    // 'repeat_every'      =>  $request->repeat_every,
                    // 'week_day'          =>  implode(',',$request->week_day),
                    // 'end_on'            =>  date('Y-m-d', strtotime(str_replace('/','-',$request->end_on))),
                    // 'indefinite'        =>  $request->indefinite,
                    // 'tag'               =>  $request->tag,
                    // 'note'              =>  $request->note,
                    'created_at'        =>  date('Y-m-d h:i:s')
                );
                $shiftid = Shifting::create($insertArray);
            }
            else
            {
                $shiftid = $shiftDetails->id;
            }

            $scheduleInsertArray = array(
                'company_id'        =>  $company_id,
                'department'        =>  $departmentId,
                'employee'          =>  $userId,
                'shift_on'          =>  date('Y-m-d', strtotime($importData[3])),
                'shift'             =>  $shiftid,
                'min_start_time'    =>  date('h:i:s a', strtotime($importData[4])),
                'start_time'        =>  date('h:i:s a', strtotime($importData[5])),
                'max_start_time'    =>  date('h:i:s a', strtotime($importData[6])),
                'min_end_time'      =>  date('h:i:s a', strtotime($importData[7])),
                'end_time'          =>  date('h:i:s a', strtotime($importData[8])),
                'max_end_time'      =>  date('h:i:s a', strtotime($importData[9])),
                'break_time'        =>  $importData[10],
                // 'extra_hours'       =>  $request->extra_hours,
                // 'publish'           =>  $request->publish,
                'created_at'        =>  date('Y-m-d h:i:s'),
                'status'            =>  'active'
            );
            $shiftid = Scheduling::create($scheduleInsertArray);
        }
        $return['message'] = 'Import Successful.';
        $return['status'] = 1;
        return $return;
    }

    private function getUserDetailsByEmployeeId($empid)
    {
        return Employee::where("emp_generated_id", $empid)->first();
    }

    private function getShiftDetailsById($suid)
    {
        return Shifting::where("suid", $suid)->first();
    }
}
