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
use App\Models\AttendanceDetails;

class UserScheduling extends Controller
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
            if(isset($_POST['department']) && $_POST['department']!='')
            {
                $where['department'] = $_POST['department'];
                $search['department'] = $_POST['department'];
            }
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
        $login_user_id = Session::get('user_id');
        $login_user_detail = _is_user_role_owner($login_user_id);
        $user_branch  = $login_user_detail->branch ?? '';
        $user_designation  = $login_user_detail->designation ?? '';
    	$scheduling   = Employee::with('schedules')->where($where)->where('branch',$user_branch)->where('designation','!=',$user_designation)->where('user_id','!=',$login_user_id)->get();
    	$allEmployees = Employee::with(["employee_designation", 'employee_department'])->where('status','active')->where($empwhere)->where('branch',$user_branch)->where('designation','!=',$user_designation)->where('user_id','!=',$login_user_id)->get();
        // echo '<pre>';print_r($scheduling);exit;
        if(!empty($login_user_detail)):
    	    return view('lts.user_scheduling', compact('title', 'allEmployees', 'department', 'shifts', 'scheduling', 'search', 'overtimeDetails', 'phDetails', 'startDate'));
        else:
            return redirect(route('dashboard'));
        endif;
        }

    public function store(Request $request)
    {
        $login_user_id = Session::get('user_id');
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
                'min_start_time'    =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9 || $request->shift_addschedule > 9)?_convert_time_to_12hour_dateformat(str_replace(' pm','',$request->min_start_time)):NULL,
                'start_time'        =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9 || $request->shift_addschedule > 9)?_convert_time_to_12hour_dateformat(str_replace(' pm','',$request->start_time)):NULL,
                'max_start_time'    =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9 || $request->shift_addschedule > 9)?_convert_time_to_12hour_dateformat(str_replace(' pm','',$request->max_start_time)):NULL,
                'min_end_time'      =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9 || $request->shift_addschedule > 9)?_convert_time_to_12hour_dateformat(str_replace(' pm','',$request->min_end_time)):NULL,
                'end_time'          =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9 || $request->shift_addschedule > 9)?_convert_time_to_12hour_dateformat(str_replace(' pm','',$request->end_time)):NULL,
                'max_end_time'      =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9 || $request->shift_addschedule > 9)?_convert_time_to_12hour_dateformat(str_replace(' pm','',$request->max_end_time)):NULL,
                'break_time'        =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9 || $request->shift_addschedule > 9)?$request->break_time:NULL,
                'extra_hours'   	=>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9 || $request->shift_addschedule > 9)?$request->extra_hours:0,
                'publish'      		=>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9 || $request->shift_addschedule > 9)?$request->publish:0,
                'created_at'        =>   date('Y-m-d h:i:s'),
                'status'			=>	'active',
                'added_by'          =>   $login_user_id
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
        return redirect('/user_scheduling')->with('success', 'Schedule created successfully!')->with('sdate' , $request->add_start_from_date);
    }

    public function update(Request $request)
    { 
        $company_id  = Session::get('company_id');
        $login_user_id = Session::get('user_id');
        $insertArray = array(
            'company_id'        =>  $company_id,
            // 'department'        =>  (isset($request->department_addschedule))?$request->department_addschedule:0,
            'employee'          =>  ($request->employee_addschedule!='')?$request->employee_addschedule:$request->employee_addschedule_id,
            'shift_on'          =>  date('Y-m-d', strtotime(str_replace('/','-',$request->shift_date))),
            'shift'             =>  $request->shift_addschedule,
            'min_start_time'    =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9)?_convert_time_to_12hour_dateformat(str_replace(' pm','',$request->min_start_time)):NULL,
            'start_time'        =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9)?_convert_time_to_12hour_dateformat(str_replace(' pm','',$request->start_time)):NULL,
            'max_start_time'    =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9)?_convert_time_to_12hour_dateformat(str_replace(' pm','',$request->max_start_time)):NULL,
            'min_end_time'      =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9)?_convert_time_to_12hour_dateformat(str_replace(' pm','',$request->min_end_time)):NULL,
            'end_time'          =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9)?_convert_time_to_12hour_dateformat(str_replace(' pm','',$request->end_time)):NULL,
            'max_end_time'      =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9)?_convert_time_to_12hour_dateformat(str_replace(' pm','',$request->max_end_time)):NULL,
            'break_time'        =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9)?$request->break_time:NULL,
            'extra_hours'       =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9)?$request->extra_hours:0,
            'publish'           =>  ($request->shift_addschedule >2 && $request->shift_addschedule < 6 || $request->shift_addschedule > 9)?$request->publish:0,
            'updated_at'        =>  date('Y-m-d h:i:s'),
            'status'            =>  'active',
            'edited_by'         =>  $login_user_id
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
                $att_e_details = AttendanceDetails::where('atte_ref_id',$att_details->atte_ref_id ?? 0)->where('punch_state','clockout')->first();
                $save_data = save_schedule_overtime_hours($shiftDetails->employee,$att_date,$att_details->attendance_time,$att_e_details->attendance_time);
                
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
        return redirect('/user_scheduling')->with('success', 'Schedule updated successfully!')->with('sdate' , $request->add_start_from_date);
    }

    public function user_employeeByDepartment(Request $request)
    {
        $login_user_id = Session::get('user_id');
        $login_user_detail = _is_user_role_owner($login_user_id);
        $user_branch  = $login_user_detail->branch ?? '';
      
        $user_designation  = $login_user_detail->designation ?? '';
    	$employees   = Employee::where('branch',$user_branch)->where('department',$request->id)->where('designation','!=',$user_designation)->where('status','active')->where('user_id','!=',$login_user_id)->get();
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
            if($import['status'] == 1):
                return redirect()->back()->with("success", $import['message'] ?? 'Schedule imported successfully.');
            else:
                return redirect()->back()->with("error", $import['message'] ?? '    ');
            endif;
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

        $er = 0;
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
                    // /echo $num-1;
                    if($i < $num):
                     
                    $importData_arr[$i][] = $filedata [$c];
                    if($c != 3):
                        if($filedata [$c] == ''):
                            $er = 1;
                        endif;
                    endif;
                endif;
                }
                
            }
            $i++;
        }
        if(isset($er) && ($er == 1)):
            //dd($er);
            $return['message'] = 'Column should not be empty.Please fill it.';
            $return['status'] = 0;
            return $return;
        endif;
        fclose($file);
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
        $user_idarr = [];
        $id_arr = [];
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
            $emp_generated_id = $userDetails->emp_generated_id;
            
            $i = 1;
            // loop through date_sub
            
            for($k=4; $k<$totalColumns; $k++)
            {
                $login_user_id = Session::get('user_id');
                $login_user_detail = _is_user_role_owner($login_user_id);
                $user_branch  = $login_user_detail->branch ?? '';
                $user_designation  = $login_user_detail->designation ?? '';
                $is_same_branch_user   = Employee::where('branch',$user_branch)->where('designation','!=',$user_designation)->where('user_id','!=',$login_user_id)->where('user_id',$userId)->first();
                if(!empty($is_same_branch_user)):
                    //check shift exists, if not create shift
                    $explode_shift = explode('/', $importData[$k]);
                    $shiftDetails = $this->getShiftDetailsById($explode_shift[1]);//echo $importData[$k];
                    if(!empty($shiftDetails)):
                        $schedule_date = date('Y-m-d', strtotime(str_replace('/','-',$importHeaderData_arr[$k])));
                        //check schedule is exist or not for specific date
                        $is_schedule_exists = Scheduling::where('employee',$userId)->where('shift_on',$schedule_date)->delete();
                        
                        $shiftid = $shiftDetails->id;
                        if($shiftDetails->is_twoday_shift == '1'):
                            $plusonedate = date('Y-m-d', strtotime("+1 day", strtotime($schedule_date)));
                        else:
                            $plusonedate = $schedule_date;
                        endif;

                        $scheduleInsertArray = array(
                            'company_id'        =>  $company_id,
                            'department'        =>  $departmentId,
                            'employee'          =>  $userId,
                            'shift_on'          =>  date('Y-m-d', strtotime(str_replace('/','-',$importHeaderData_arr[$k]))),
                            'shift'             =>  $shiftid,
                            'min_start_time'    =>  _convert_time_to_12hour_dateformat($schedule_date.' '.$shiftDetails->min_start_time),//date('h:i:s a', strtotime($importData[4])),
                            'start_time'        =>  _convert_time_to_12hour_dateformat($schedule_date.' '.$shiftDetails->start_time),//date('h:i:s a', strtotime($importData[5])),
                            'max_start_time'    =>  _convert_time_to_12hour_dateformat($schedule_date.' '.$shiftDetails->max_start_time),//date('h:i:s a', strtotime($importData[6])),
                            'min_end_time'      =>  _convert_time_to_12hour_dateformat($plusonedate.' '.$shiftDetails->min_end_time),//date('h:i:s a', strtotime($importData[7])),
                            'end_time'          =>  _convert_time_to_12hour_dateformat($plusonedate.' '.$shiftDetails->end_time),//date('h:i:s a', strtotime($importData[8])),
                            'max_end_time'      =>  _convert_time_to_12hour_dateformat($plusonedate.' '.$shiftDetails->max_end_time),//date('h:i:s a', strtotime($importData[9])),
                            'break_time'        =>  $shiftDetails->break_time,//$importData[10],
                            'added_by' => $login_user_id,
                            'created_at'        =>  date('Y-m-d h:i:s'),
                            'status'            =>  'active'
                        );
                       
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
                    endif;
                else:
                    $user_idarr[] = $emp_generated_id;
                    $i++;
                endif;
            }
            
        }
        if(!empty($user_idarr) && count($user_idarr) > 0):
            $user_idarr = array_unique($user_idarr);
            $imp_user = implode(',',$user_idarr);
            $message = $imp_user.' employees id does not imported.';
        else:
            $message = 'Import Successful.';
        endif;
        $return['message'] = $message;
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
