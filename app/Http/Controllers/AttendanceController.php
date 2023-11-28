<?php

namespace App\Http\Controllers;
use DB;
use Session;
use DateTime;
use Illuminate\Http\Request;

use App\Models\Residency;
use App\Models\Attendance;
use App\Models\AttendanceDetails;
use App\Models\User;
use App\Models\Employee;
use App\Models\Departments;
use App\Models\Scheduling;
use App\Models\Overtime;

use Illuminate\Support\Facades\Hash;
class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->title = 'Attendance';
        $this->current_datetime = date('Y-m-d H:i:s');
        // date_default_timezone_set("Asia/Calcutta");
    }

    public function index(Request $request)
    {
        $title = $this->title;
        // $companies    = Residency::where('status','active')->get();

        $year = date('Y');
        $month = date('m');
        $emp = '';

        $where = array();
            // 'status'    =>  'active');

        if(isset($_POST['search']))
        {
            if(isset($_POST['year']) && $_POST['year']!='')
            {
                $year = $_POST['year'];
            }
            if(isset($_POST['month']) && $_POST['month']!='')
            {
                $month = $_POST['month'];
            }
            if(isset($_POST['employee']) && $_POST['employee']!='')
            {
                $where['user_id'] = $_POST['employee'];
                $emp = $_POST['employee'];
            }
        }

        $attEmployees = Employee::where($where)->where('status', '!=', 'deleted')->get();
        $allEmployees = Employee::where('status', 'active')->get();

        return view('lts.attendance', compact('title', 'attEmployees', 'allEmployees', 'year', 'month', 'emp'));
    }

   
    public function store(Request $request)
    { 
    	//validate file
    	$validate = $this->validateCSV($request->file('attendance_file'));
    	if($validate['status'] == 1)
    	{
    		$this->company_id  = Session::get('company_id');
	        $this->user_id  = Session::get('user_id');

	        $file = $request->file('attendance_file');
	        $filename = $file->getClientOriginalName();

	        $attnArray = array(
	            'company_id'    =>  $this->company_id,
	            'residency_id'	=>	$this->company_id,
	            'branch_id'     =>  0,//$request->branch,
	            'file_name'     =>  $filename,
	            'added_by'      =>  $this->user_id,
	            'created_at'    =>  $this->current_datetime
	        );
	        $attnId = Attendance::create($attnArray)->id;

	        //call excel/csv function
	        $import = $this->importCSV($request->file('attendance_file'), $attnId);
            if($import['error'] == 1):
                return redirect()->back()->with("error", 'Something went wrong.');
            else:
                return redirect()->back()->with("success", 'Attendance imported successfully.');
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

    private function importCSV($file, $attnId)
    {
    	// File Details 
      	$filename = $file->getClientOriginalName();
      	
		// File upload location
  		$location = 'uploads/attendance';
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
          $error = 0;
  		// Insert to MySQL database
        foreach($importData_arr as $importData)
        {
           
            //if(!empty($importData[0]) && !empty($importData[3]) && !empty($importData[5]) && !empty($importData[6])
            //&& !empty($importData[7]) && !empty($importData[8]) && !empty($importData[9]) && !empty($importData[10])):
                if($importData[0]==='')
                {
                    continue;
                }

                //check empno exists, if not create employee
                $userDetails = $this->getUserDetailsByEmployeeId($importData[0]);
                $departmentId = 0;
                if(!empty($userDetails)):
                    // {
                    //     //create employee
                    //     $this->company_id  = Session::get('company_id');
                    //     $userArray = array(
                    //         'company_id'    =>  $this->company_id,
                    //         'username'      =>  $importData[3].'@'.rand(9000,999999),
                    //         'name'          =>  $importData[3],
                    //         'email'         =>  NULL,
                    //         'password'      =>  Hash::make(randomPassword()),
                    //         'created_at'    =>  date('Y-m-d H:i:s')
                    //     );
                    //     $userId = User::create($userArray)->id;
                    //     if($userId)
                    //     {
                    //         $insertArray = array(
                    //             'user_id'       =>  $userId,
                    //             'company_id'    =>  $this->company_id,
                    //             'first_name'    =>  $importData[3],
                    //             'emp_generated_id'  =>  $importData[0]
                    //         );
                    //         $empId = Employee::create($insertArray);
                    //     }
                    // }
                    // else
                
                    $userId = $userDetails->user_id;//by employee id
                    $departmentId = $userDetails->department;
                
                    // $punch = ($importData[5]=='Check In')?'clockin':'clockout';

                    $insertData = array(
                        "user_id"       =>  $userId,
                        "employee_id"   =>  $importData[0],
                        "department"    =>  $departmentId,
                        "attendance_on" =>  date('Y-m-d', strtotime(str_replace('/','-',$importData[5]))),
                        // "work_code"     =>  'NULL',
                        "data_source"   =>  'Device',
                        "status"        =>  'active');

                    //check leave scheduling exist or not
                    $is_scheduling_leave = Scheduling::where('shift_on',$insertData['attendance_on'])->where('employee',$userId)->first();
                    //if(empty(!$is_scheduling_leave)):
                        if($importData[9] === '' && $importData[10] === '')
                        {
                            
                            $insertData['day_type'] = 'off';
                            $insertData["attendance_time"]   = 0;
                            $insertData["punch_state"]          =  'none';
                            // echo '<pre>';print_r(AttendanceDetails::where($insertData)->count());exit;
                            if(AttendanceDetails::where($insertData)->count() == 0)
                            {
                                $insertData['attendance_id'] = $attnId;
                                $insertData["created_at"] = $this->current_datetime;
                                // echo '<pre>';print_r($insertData);//exit;
                                $in_data = AttendanceDetails::updateOrCreate($insertData);
                                continue;
                            }
                        }


                        if($importData[9] !== '')
                        {
                            //off or ph
                            if((strtolower($importData[9])==='off' || strtolower($importData[9])==='ph') && (strtolower($importData[10])==='off' || strtolower($importData[10])==='ph'))
                            {
                                $insertData['day_type'] = strtolower($importData[9]);
                                $insertData["attendance_time"]   = 0;
                                $insertData["punch_state"]          =  'none';
                            }
                            else
                            {
                                $insertData['day_type'] = 'work';
                                $insertData["attendance_time"] = $importData[9];
                                $insertData["punch_state"] = 'clockin';
                            }
                            if(AttendanceDetails::where($insertData)->count() == 0)
                            {
                                $insertData['attendance_id'] = $attnId;
                                $insertData["created_at"] = $this->current_datetime;
                                // echo '<pre>';print_r($insertData);//exit;
                                $in_data = AttendanceDetails::updateOrCreate($insertData);
                            }                
                        }
                        else
                        {
                            if($importData[10] !== '')
                            {
                                $insertData['day_type'] = 'work';
                                $insertData["attendance_time"] =    0;
                                $insertData["punch_state"]  =   'clockin';
                                if(AttendanceDetails::where($insertData)->count() == 0)
                                {
                                    $insertData['attendance_id'] = $attnId;
                                    $insertData["created_at"] = $this->current_datetime;
                                    $in_data = AttendanceDetails::updateOrCreate($insertData);
                                }
                            }
                        }           

                        if($importData[10] !== '')
                        {
                            // if(strtolower($importData[10])==='off' || strtolower($importData[10])==='ph')
                            // {
                            //     $insertData['day_type'] = strtolower($importData[10]);
                            //     $insertData["attendance_time"]   = 0;
                            //     $insertData["punch_state"]          =  'none';
                            // }
                            // else
                            // {
                                $insertData['day_type'] = 'work';
                                $insertData["attendance_time"] =    $importData[10];
                                $insertData["punch_state"]  =   'clockout';
                            // }
                            if(AttendanceDetails::where($insertData)->count() == 0)
                            {
                                $insertData['attendance_id'] = $attnId;
                                $insertData["created_at"] = $this->current_datetime;
                                AttendanceDetails::updateOrCreate($insertData);
                            }
                        }
                        else
                        {
                            if($importData[9] !== '')
                            {
                                $insertData['day_type'] = 'work';
                                $insertData["attendance_time"] =    0;
                                $insertData["punch_state"]  =   'clockout';
                                if(AttendanceDetails::where($insertData)->count() == 0)
                                {
                                    $insertData['attendance_id'] = $attnId;
                                    $insertData["created_at"] = $this->current_datetime;
                                    AttendanceDetails::updateOrCreate($insertData);
                                }
                            }
                        }
                    // dd($insertData);
                        if(!empty($insertData)):
                            // /dd('sds');
                            $att_date = date('Y-m-d', strtotime($insertData['attendance_on']));
                            $shiftDetails = Scheduling::where('employee', $insertData['user_id'])->where('shift_on', $att_date)->where('status', 'active')->first();
                            if(!empty($shiftDetails)):
                                $flag = _check_green_icon_attendance($insertData['attendance_on'],$insertData['user_id']);
                                if($flag === 0):
                                    $save_data = save_schedule_overtime_hours($insertData['user_id'],$att_date,$importData[9],$importData[10]);
                                elseif($flag == 2):
                                    $save_data = AttendanceDetails::where('user_id',$insertData['user_id'])->where('attendance_on',$att_date)->where('punch_state','clockin')->first();
                                    $save_data->schedule_hours = 8;
                                    $save_data->overtime_hours = 0;
                                    $save_data->save();
                                else:
                                    $save_data = AttendanceDetails::where('user_id',$insertData['user_id'])->where('attendance_on',$att_date)->where('punch_state','clockin')->first();
                                    $save_data->schedule_hours = NULL;
                                    $save_data->overtime_hours = NULL;
                                    $save_data->save();
                                endif;  
                            endif;
                        endif;
                        // echo '<pre>';print_r($insertData);exit;
                    //endif;
                    // else:
                    //     AttendanceDetails::where('attendance_id',$attnId)->delete();
                    //     $error = 1;
                    // endif;
                endif;
        }
        $return['message'] = 'Import Successful.';
        $return['status'] = 1;
        $return['error'] = $error;
      	return $return;
    }

    private function importCSV_first_fn($file, $attnId)
    {
        // File Details 
        $filename = $file->getClientOriginalName();
        
        // File upload location
        $location = 'uploads/attendance';
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

        // Insert to MySQL database
        foreach($importData_arr as $importData)
        {
            $userId = $this->getUserIdByEmployeeId($importData[0]);
            $departmentId = $this->getDepartmentIdByName($importData[2]);
            $punch = ($importData[5]=='Check In')?'clockin':'clockout';
            
            $insertData = array(
                "attendance_id" =>  $attnId,
                "user_id"       =>  $userId,
                "employee_id"   =>  $importData[0],
                "department"    =>  $departmentId,
                "attendance_on" =>  date('Y-m-d', strtotime($importData[3])),
                "attendance_time"=> $importData[4],
                "punch_state"   =>  $punch,
                "work_code"     =>  $importData[6],
                "data_source"   =>  $importData[7],
                "created_at"    =>  $this->current_datetime,
                "status"        =>  'active');
            AttendanceDetails::create($insertData);
        }
        $return['message'] = 'Import Successful.';
        $return['status'] = 1;
        return $return;
    }

    private function getUserDetailsByEmployeeId($empid)
    {
    	return Employee::where("emp_generated_id", $empid)->first();
    }

    private function getDepartmentIdByName($department)
    {
    	return Departments::where("name", $department)->first()->id;
    }

    private function getDepartmentIdByEmpId($empid)
    {
        return Departments::where("name", $department)->first()->id;
    }

    public function isAttendanceExists(Request $request)
    { 
    	$where['attendance_date'] = date('Y-m-d',strtotime($request['attendance_date']));
    	$where['company'] = $request['company'];
    	$where['branch'] = $request['branch'];
        
        $count = Attendance::where($where)->get();
        
        if(count($count) > 0)
        {
            echo "false";
        }
        else
        {
            echo "true";
        }
    }

    public function getAttendanceDetails(Request $request)
    {
        $userId = intval($request->userId);
        //find employee
        $emp_detail = Employee::where('user_id',$userId)->where('status','active')->first();
        $popup_type = $request->popup_type ?? '';
        $attnDate = str_replace('"','',preg_replace('/\\\\/', '', $request->attnDate));
        // $emloyeeAttendance = AttendanceDetails::where(array('user_id' => $userId, 'attendance_on' => $attnDate))->orderBy('attendance_time')->get()->toArray();
        $emloyeeAttendance = AttendanceDetails::where(array('user_id' => $userId, 'attendance_on' => $attnDate,'employee_id'=>$emp_detail->emp_generated_id))->get()->toArray();
        $emloyeeSchedule = Scheduling::where(array('employee' => $userId, 'shift_on' => date('Y-m-d', strtotime($attnDate)), 'status' => 'active'))->get()->toArray();
        // echo '<pre>';print_r($emloyeeAttendance);exit;
        if($popup_type == 'create_attn'):
            $html = view('lts.createattendancePopup', compact('attnDate', 'emloyeeSchedule', 'userId'))->render();
        else:
            $attendanceHours = $this->attendanceHoursCalculation($userId, $attnDate);
            $html = view('lts.attendancePopup', compact('emloyeeAttendance', 'attendanceHours', 'attnDate', 'emloyeeSchedule', 'userId','popup_type'))->render();
        endif;
            echo json_encode($html);
    }

    public function getempAttendanceDetails(Request $request)
    {
        $userId = intval($request->userId);
        //find employee
        $emp_detail = Employee::where('user_id',$userId)->where('status','active')->first();
        $popup_type = $request->popup_type ?? '';
        $attnDate = str_replace('"','',preg_replace('/\\\\/', '', $request->attnDate));
        // $emloyeeAttendance = AttendanceDetails::where(array('user_id' => $userId, 'attendance_on' => $attnDate))->orderBy('attendance_time')->get()->toArray();
        $emloyeeAttendance = AttendanceDetails::where(array('user_id' => $userId, 'attendance_on' => $attnDate,'employee_id'=>$emp_detail->emp_generated_id ?? ''))->get()->toArray();
        $emloyeeSchedule = Scheduling::where(array('employee' => $userId, 'shift_on' => date('Y-m-d', strtotime($attnDate)), 'status' => 'active'))->get()->toArray();
        // echo '<pre>';print_r($emloyeeAttendance);exit;
            $attendanceHours = $this->attendanceHoursCalculation($userId, $attnDate);
            $html = view('lts.emp_attendance_popup', compact('emloyeeAttendance', 'attendanceHours', 'attnDate', 'emloyeeSchedule', 'userId','popup_type'))->render();
            echo json_encode($html);
    }
    public function testit()
    {
        $x = $this->attendanceHoursCalculation(220, '2023-06-22');
        echo '<pre>';print_r($x);exit;
    }
    public function attendanceHoursCalculation($userid, $attendanceon)
    {
        $startTime = 0; $totalWorkTime = 0; 
        $breakTime = 0; $totalBreakTime = 0;
        $lastTime = 0;

        $emloyeeAttendance = AttendanceDetails::where(array('user_id' => $userid, 'attendance_on' => $attendanceon))->get()->toArray();

        $punchStates = array_column($emloyeeAttendance, 'punch_state');
        $count = count($emloyeeAttendance);
        $lastIndex = $count-1;

        $startTime = 0;
// echo '<pre>';print_r($punchStates);exit;
        if(isset($punchStates))
        {
            $startIndex = array_search('clockin', $punchStates);
            $startTime = $emloyeeAttendance[$startIndex]['attendance_time'];

            if(isset($punchStates[$lastIndex]) && $punchStates[$lastIndex] == 'clockout')
            {
                $lastTime = $emloyeeAttendance[$lastIndex]['attendance_time'];
            }
            else
            {
                $lastTime = $startTime;
            } 
        }

        foreach($emloyeeAttendance as $key => $ea)
        {
            // echo '<--------------start loop - '.$key.'---------------->';
            // echo '<br>';
            // echo $ea['punch_state'];
            // echo '<br>';
        
            if($ea['punch_state']=='clockin')
            {    
                // echo '$breakTime='.$breakTime;
                // echo '<br>';
                if($breakTime != 0)
                {
                    
                    // echo 'Breaktime';
                    // echo '<br>';
                    $b_time1 = strtotime($breakTime);
                    $b_time2 = strtotime($ea['attendance_time']);

                    $totalBreakTime = $totalBreakTime + round(abs($b_time2 - $b_time1) / 3600,2);
                    

                    // echo $breakTime.'-'.$b_time1;
                    // echo '<br>';
                    // echo $ea['attendance_time'].'-'.$b_time2;
                    // echo '<br>';
                    // echo '$totalBreakTime- '.$totalBreakTime;
                    $startTime = $breakTime;
                    $breakTime = '';
                }
                else
                {
                    
                    $time1 = strtotime($startTime);
                    $time2 = strtotime($ea['attendance_time']);
                    
                    $totalWorkTime = $totalWorkTime + round(abs($time2 - $time1) / 3600,2);
                    // echo $startTime.'-'.$time1;
                    // echo '<br>';
                    // echo $ea['attendance_time'].'-'.$time2;
                    // echo '<br>';
                    // echo '$totalWorkTime- '.$totalWorkTime;

                }
                $startTime = $ea['attendance_time'];

                if($key==$lastIndex)
                { 
                    // echo 'its last<br>';
                    if($lastTime==0)
                    { 
                        // echo 'still working on <br>';
                        // $time1 = strtotime($startTime);
                        // $time2 = strtotime(date('H:i'));
                        // // $time2 = strtotime($ea['attendance_time']);
                        $totalWorkTime = 0;
                        // $totalWorkTime = $totalWorkTime + round(abs($time2 - $time1) / 3600,2);
                        // echo $startTime.'-'.$time1;
                        // echo '<br>';
                        // echo date('d-m-Y H:i').'-'.$time2;
                        // echo '<br>';
                        // echo '$totalWorkTime- '.$totalWorkTime;
                    }
                }
            }
            if($ea['punch_state']=='clockout')
            { 
                
                $time1 = ($startTime!=='0')?strtotime($startTime):strtotime($ea['attendance_time']);
                $time2 = ($ea['attendance_time']!=='0')?strtotime($ea['attendance_time']):$time1;
                $totalWorkTime = $totalWorkTime + round(abs($time2 - $time1) / 3600,2);
                
                // echo $startTime.'-'.$time1;
                // echo '<br>';
                // echo $ea['attendance_time'].'-'.$time2;
                // echo '<br>';
                // echo '$totalWorkTime- '.$totalWorkTime;

                $startTime = '';
                $breakTime = $ea['attendance_time'];
                // echo '<br>';
                // echo '$breakTime- '.$breakTime;
               
            }

            // echo '<br>';
            // echo '<br>';
            // echo '<--------------ended loop - '.$key.'---------------->';
            // echo '<br>';
            // echo '<br>';
        }

        // echo 'totalBreakTime - '.$totalBreakTime; echo '<br> totalWorkTime -'.$totalWorkTime;
        // echo '<br>';

        // Convert to proper hours minutes
        // $data['totalWorkTimeHours'] = $this->convertToHoursMinutes($totalWorkTime);
        // $data['totalBreakTimeHours'] = $this->convertToHoursMinutes($totalBreakTime);
        $data['totalWorkTimeHours'] = ($totalWorkTime != '')?$this->convertToHoursMinutes($totalWorkTime):'0.00';
        $data['totalBreakTimeHours'] = ($totalBreakTime!='')?$this->convertToHoursMinutes($totalBreakTime):array();
        return $data;
    }

    public static function convertToHoursMinutes($hours)
    {
        $whole = floor($hours);
        // echo '<br>';
        // echo $whole;
        // echo '<br>';
        $fraction = $hours - $whole;
        $minutesText = '';
        $secondsText = '';
        $mn = 0;
        if($fraction != '')
        {
            $minutes = $fraction * 60;

            //check for seconds
            // $min_whole = floor($minutes);
            // $min_fraction = $minutes - $min_whole;
            // if($min_fraction !='')
            // {
            //     $secondsText = ceil($min_fraction).' seconds';
            // }
            $mn = $minutes;
            $minutesText = ', '.(int)$minutes.' mns'.$secondsText;
        }
        $result['timetext'] = $whole.' hrs'.$minutesText;
        $result['timevalue'] = $whole.'.'.$mn;
        // echo '$result - '.$result;
        // echo '<br>';
        return $result;
    }

    public function approveOt(Request $request)
    {
        $company_id  = Session::get('company_id');
        $newSchedule = array(
            'employee'      =>  $request->attnUserId,
            'shift_on'      =>  date('Y-m-d', strtotime($request->attnDate)),
            'status'        =>  'active'
        );
        //if schedule exists deactivate it and create new schedule
        $schedule = Scheduling::where($newSchedule)->first();
// echo '<pre>';print_r($schedule);exit;
        $newSchedule['company_id'] =  $company_id;
        $newSchedule['min_start_time']  =  _convert_time_to_12hour_format(str_replace(' pm','',$request->start_time));
        $newSchedule['start_time']  =  _convert_time_to_12hour_format(str_replace(' pm','',$request->start_time));
        $newSchedule['max_start_time']  =  _convert_time_to_12hour_format(str_replace(' pm','',$request->start_time));
        $newSchedule['min_end_time']  =  _convert_time_to_12hour_format(str_replace(' pm','',$request->end_time));
        $newSchedule['end_time']  =  _convert_time_to_12hour_format(str_replace(' pm','',$request->end_time));
        $newSchedule['max_end_time']  =  _convert_time_to_12hour_format(str_replace(' pm','',$request->end_time));
        // $newSchedule['over_time'] = $request->ottime;
        // echo '<pre>';print_r($_POST);echo '<pre>';print_r($newSchedule);exit;
        $shiftid = Scheduling::where('id', $schedule->id)->update($newSchedule);

        $updateArray    = array(
            'ottime'                => $request->ottime,
            'ot_approve_status'     => (isset($request->approve_status))?$request->approve_status:0,
            'ot_approve_remark'     => $request->approve_remark,
            'updated_at'            =>  date('Y-m-d h:i:s')
        );

        $where = array(
            'user_id'           =>  $request->attnUserId,
            'attendance_on'     =>  $request->attnDate,
        );

        if(isset($request->start_time))
        {
            $updateArray['attendance_time'] = date('H:i', strtotime(str_replace(' pm','',$request->start_time)));
            $where['punch_state'] = 'clockin';
            AttendanceDetails::where($where)->update($updateArray);
        }
        if(isset($request->end_time))
        {
            $updateArray['attendance_time'] = date('H:i', strtotime(str_replace(' pm','',$request->end_time)));
            $where['punch_state'] = 'clockout';
            AttendanceDetails::where($where)->update($updateArray);
        }
        $end_time = date('H:i', strtotime(str_replace(' pm','',$request->end_time)));
        $start_time = date('H:i', strtotime(str_replace(' pm','',$request->start_time)));
        //get schedule data
        $att_date = date('Y-m-d', strtotime($request->attnDate));
        $shiftDetails = Scheduling::where('employee', $request->attnUserId)->where('shift_on', $att_date)->where('status', 'active')->first();
        if(!empty($shiftDetails)):
            $flag = _check_green_icon_attendance($request->attnDate,$request->attnUserId);
            if($flag === 0):
                $save_data = save_schedule_overtime_hours($request->attnUserId,$att_date,$start_time,$end_time);
            elseif($flag == 2):
                $save_data = AttendanceDetails::where('user_id',$request->attnUserId)->where('attendance_on',$request->attnDate)->where('punch_state','clockin')->first();
                $save_data->schedule_hours = 8;
                $save_data->overtime_hours = 0;
                $save_data->save();
            else:
                $save_data = AttendanceDetails::where('user_id',$request->attnUserId)->where('attendance_on',$request->attnDate)->where('punch_state','clockin')->first();
                if(!empty($save_data)):
                $save_data->schedule_hours = NULL;
                $save_data->overtime_hours = NULL;
                $save_data->save();
                endif;
            endif;  
        endif;
        echo json_encode('done');
        // return redirect('/attendance')->with('success','Attendance updated successfully!');
    }

    public function create_attendance_by_date(Request $request){
        //dd($request->all());
        $userDetails = Employee::where("user_id", $request->attnUserId)->where('status','active')->first();
        $userId = $userDetails->emp_generated_id;//by employee id
        $departmentId = $userDetails->department;
        $end_time = date('H:i', strtotime(str_replace(' pm','',$request->end_time)));
        $start_time = date('H:i', strtotime(str_replace(' pm','',$request->start_time)));
        $att_date = date('Y-m-d', strtotime($request->attnDate));
        AttendanceDetails::where('user_id',$request->attnUserId)->where('employee_id',$userId)->where('attendance_on',$att_date)->delete();

        if(isset($request->start_time))
        {
            $attnId = 1;
            $insertData = array(
            "user_id"       =>  $request->attnUserId,
            "employee_id"   =>  $userId,
            "department"    =>  $departmentId,
            "attendance_on" =>  date('Y-m-d', strtotime($request->attnDate)),
            "attendance_time"=> $start_time,
            "punch_state"   =>  'clockin',
            "day_type"     =>  'work',
            "data_source"   =>  'Device',
            "created_at"    =>  $this->current_datetime,
            "status"        =>  'active');
            $in_data = AttendanceDetails::create($insertData);
            $in_id = $in_data->id ?? '';
            }
        if(isset($request->end_time))
        {
            $insertData = array(
            "user_id"       =>  $request->attnUserId,
            "employee_id"   =>  $userId,
            "department"    =>  $departmentId,
            "attendance_on" =>  date('Y-m-d', strtotime($request->attnDate)),
            "attendance_time"=> $end_time,
            "punch_state"   =>  'clockout',
            "day_type"     =>  'work',
            "data_source"   =>  'Device',
            "created_at"    =>  $this->current_datetime,
            "status"        =>  'active');

            $out_data = AttendanceDetails::create($insertData);
            $out_id = $out_data->id ?? '';
        }
        //get schedule data
        $att_date = date('Y-m-d', strtotime($request->attnDate));
        $shiftDetails = Scheduling::where('employee', $request->attnUserId)->where('shift_on', $att_date)->where('status', 'active')->first();
        if(!empty($shiftDetails)):
            $flag = _check_green_icon_attendance($request->attnDate,$request->attnUserId);
            if($flag === 0):
                $save_data = save_schedule_overtime_hours($request->attnUserId,$att_date,$start_time,$end_time);
            elseif($flag == 2):
                $save_data = AttendanceDetails::find($in_id);
                $save_data->schedule_hours = 8;
                $save_data->overtime_hours = 0;
                $save_data->save();
            else:
                $save_data = AttendanceDetails::find($in_id);
                $save_data->schedule_hours = NULL;
                $save_data->overtime_hours = NULL;
                $save_data->save();
            endif;  
        endif;
        return redirect()->back()->with('success','Attendance created successfully');
    }

    public function save_clock_data(Request $request,$type){
        $user_id  = Session::get('user_id');
        //find employee generated id
        $cdate = date('Y-m-d');
        $ctime = date('h:i');
        $userDetails = Employee::where("user_id", $user_id)->where('status','active')->first();
        $employee_id = $userDetails->emp_generated_id;//by employee id
        $departmentId = $userDetails->department;
        //$ctime = date('H:i', strtotime(str_replace(' pm','',$request->start_time)));
        if($type == 'in'):
            $insertData = array(
            "user_id"       =>  $user_id,
            "employee_id"   =>  $employee_id,
            "department"    =>  $departmentId,
            "attendance_on" =>  $cdate,
            "attendance_time"=> $ctime,
            "punch_state"   =>  'clockin',
            "day_type"     =>  'work',
            "data_source"   =>  'Device',
            "created_at"    =>  $this->current_datetime,
            "status"        =>  'active');

            $in_data = AttendanceDetails::create($insertData);
        elseif($type == 'out'):
            $insertData = array(
            "user_id"       =>  $user_id,
            "employee_id"   =>  $employee_id,
            "department"    =>  $departmentId,
            "attendance_on" =>  $cdate,
            "attendance_time"=> $ctime,
            "punch_state"   =>  'clockout',
            "day_type"     =>  'work',
            "data_source"   =>  'Device',
            "created_at"    =>  $this->current_datetime,
            "status"        =>  'active');

            $out_data = AttendanceDetails::create($insertData);
        endif;
        //get schedule data
        $att_date = date('Y-m-d', strtotime($cdate));
        $start_time = AttendanceDetails::where('user_id', $user_id)->where('punch_state', 'clockin')->whereDate('attendance_on', $att_date 
        )->first();
        $end_time = AttendanceDetails::where('user_id', $user_id)->where('punch_state', 'clockout')->whereDate('attendance_on', $att_date 
        )->limit(1)->orderBy('id', 'desc')->first();
        if(!empty($start_time) && !empty($end_time)):
            $shiftDetails = Scheduling::where('employee', $user_id)->where('shift_on', $att_date)->where('status', 'active')->first();
            if(!empty($shiftDetails)):
                $flag = _check_green_icon_attendance($cdate,$user_id);
                if($flag === 0):
                    $save_data = save_schedule_overtime_hours($user_id,$att_date,$start_time->attendance_time,$end_time->attendance_time);
                elseif($flag == 2):
                    $save_data = AttendanceDetails::find($in_id);
                    $save_data->schedule_hours = 8;
                    $save_data->overtime_hours = 0;
                    $save_data->save();
                else:
                    $save_data = AttendanceDetails::find($start_time->id);
                    $save_data->schedule_hours = NULL;
                    $save_data->overtime_hours = NULL;
                    $save_data->save();
                endif;  
            endif;
        endif;
        return redirect()->back()->with('success','Time saved successfully');
    }

    public function emp_attendance_list(Request $request){
        $user_id  = Session::get('user_id');
        $month = date('m');
        $year = date('Y');
        $start_date = $year."-".$month."-01";
        $end_date = date('Y-m-t',strtotime($start_date));
        $from_date = $_POST['from_date'] ?? '';
        $to_date = $_POST['to_date'] ?? '';
        $search = [];
        $search['from_date'] = $from_date;
        $search['to_date'] = $to_date;
       
        if(!empty($from_date) && !empty($to_date)):
            $start_date = $from_date;
            $end_date = $to_date;
            $fromDate = date('Y-m-d', strtotime($_POST['from_date']));
            $toDate = date('Y-m-d', strtotime($_POST['to_date']));
            $att_details = AttendanceDetails::where('user_id',$user_id)->whereBetween('attendance_on', [$fromDate, $toDate])->where('punch_state','clockin')->where('status','active')->get();
        elseif(!empty($from_date) && empty($to_date)):
            $start_date = $from_date;
            $fromDate = date('Y-m-d', strtotime($_POST['from_date']));
            $end_date = date('Y-m-t',strtotime($start_date));
            $att_details = AttendanceDetails::where('user_id',$user_id)->where('attendance_on','>=',$fromDate)->where('punch_state','clockin')->where('status','active')->get();
        elseif(empty($from_date) && !empty($to_date)):
            $end_date = $to_date;
            $toDate = date('Y-m-d', strtotime($_POST['to_date']));
            $month = date('m', strtotime($_POST['to_date']));
            $att_details = AttendanceDetails::where('user_id',$user_id)->where('attendance_on','<=',$toDate)->where('punch_state','clockin')->where('status','active')->get();
            
        else:
        $att_details = AttendanceDetails::where('user_id',$user_id)->whereMonth('attendance_on',$month)->whereYear('attendance_on',$year)->where('punch_state','clockin')->where('status','active')->get();
        endif;  
        
        return view('lts.emp_attendance',compact('att_details','month','year','user_id','start_date','end_date','search'));
    }
}
