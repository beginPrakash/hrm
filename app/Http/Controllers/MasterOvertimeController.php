<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Response;
use DB;
use Session;
use App\Models\Employee;
use App\Models\Leaves;
use App\Models\Leavetype;
use App\Models\LeaveStatus;
use App\Models\LeaveHierarchy;
use App\Models\LeaveApprovalLogs;
use App\Http\Controllers\Auth;
use App\Models\Scheduling;
use Illuminate\Http\Request;
use App\Models\EmployeeOvertime;

class MasterOvertimeController extends Controller
{
    public function index()
    {
        $userdetails = Employee::with('employee_designation')->where('status', 'active')->get();
        $sql_que = EmployeeOvertime::orderBy('id','desc');
        //for search
        $where = [];
        $search = [];
        $search['expiry_date'] = '';
        $search['to_date'] = '';
        $search['search_text'] = $_POST['search_text'] ?? '';
        if(isset($_POST['search']))
        {
            
            //dd($_POST['search_text']);
            
            if(isset($_POST['search_text']) && $_POST['search_text']!='')
            {
                $userdata = Employee::where('first_name', 'like', '%'.$_POST['search_text'].'%')
                ->orWhere('last_name', 'like', '%'.$_POST['search_text'].'%')
                ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)") , 'like', '%'.$_POST['search_text'].'%')
                ->orWhere('emp_generated_id',$search['search_text'])->select(DB::raw("GROUP_CONCAT(id SEPARATOR ',') as `ids`"))->first();                    
                $user_ids = $userdata->ids ?? '';
                $sql_que = $sql_que->whereIn('employee_id',explode(',',$user_ids));
            }
            if(!empty($_POST['from_date']) && !empty($_POST['to_date']))
            {
                $search['from_date'] = $_POST['from_date'];
                $search['to_date'] = $_POST['to_date'];
                $startDate = date('Y-m-d', strtotime($_POST['from_date']));
                $to_date = date('Y-m-d', strtotime($_POST['to_date']));
                $sql_que = $sql_que->whereBetween('ot_date',array($startDate,$to_date));
                
            }elseif(empty($_POST['from_date']) && !empty($_POST['to_date']))
            {
                $search['to_date'] = $_POST['to_date'];
                $to_date = date('Y-m-d', strtotime($_POST['to_date']));
                $sql_que = $sql_que->whereDate('ot_date',$to_date);
            }elseif(!empty($_POST['from_date']) && empty($_POST['to_date']))
            {
                $search['from_date'] = $_POST['from_date'];
                $from_date = date('Y-m-d', strtotime($_POST['from_date']));
                $sql_que = $sql_que->whereDate('ot_date','>=',$from_date);
            }           
            
        }
        
        
        $overtime_data = $sql_que->get();
        return view('lts.overtime', compact('overtime_data','where','search','userdetails'));
    }  

    public function store(Request $request)
    {

        //check same date overtime exist
        $ot_date = date('Y-m-d',strtotime($request->ot_date));
        if(empty($request->id)):
            $bonus_exist = EmployeeOvertime::where('employee_id',$request->employee_id)->where('ot_date', $ot_date)->first();
        else:
            $bonus_exist = EmployeeOvertime::where('employee_id',$request->employee_id)->where('id','!=',$request->id)->where('ot_date', $ot_date)->first();
        endif;
        if(!empty($bonus_exist)):
            return redirect()->back()->with('error','OverTime is already created.Please select another date.');
        else:
            $insertArray = array(
                'employee_id'        =>  $request->employee_id,
                'ot_date'     =>  $ot_date,
                'ot_hours'     =>  $request->ot_hours,
                'description'     =>  $request->description,
            );
            if(!empty($request->id)):
                $bonus_data = EmployeeOvertime::where('id', $request->id)->update($insertArray);
            else:
                $bonus_data = EmployeeOvertime::create($insertArray);
            endif;                    
            return redirect('/master_ot')->with('success','Overtime saved successfully!');
        endif;
    }


    public function details(Request $request)
    {
        $userdetails = Employee::with('employee_designation')->where('status', 'active')->get();
        $otData = EmployeeOvertime::find($request->id);
        $pass_array=array(
			'userdetails' => $userdetails,
            'otData' => $otData
        );
        $html =  view('lts.overtime_modal', $pass_array )->render();
		$arr = [
			'success' => 'true',
			'html' => $html
		];
		return response()->json($arr);

    }

    public function delete_overtime(Request $request)
    {
        $bonusData = EmployeeOvertime::where('id',$request->ot_id)->delete();
		return redirect('/master_ot')->with('success','Overtime deleted successfully!');

    }

    public function overtime_export()
    {
        $csvFileName = 'overtime_sample.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
        ];

        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['Id', 'Name', 'Position', 'Date', 'Description', 'Hours']); // Add more headers as needed
        fputcsv($handle, ['50026','Hashim showkat','IT Head', '09/03/2024', 'testetsteteee','2']); // Add more fields as needed
        fclose($handle);

        return Response::make('', 200, $headers);
    }

    public function overtime_import(Request $request)
    { 
        //validate file
        $validate = $this->validateCSV($request->file('ot_file'));
        if($validate['status'] == 1)
        {
            //call excel/csv function
            $import = $this->importCSV($request->file('ot_file'));
            if($import['status'] == 1):
                return redirect()->back()->with("success", $import['message'] ?? 'Data imported successfully.');
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
        $location = 'uploads/overtime';
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

        // Insert to MySQL database
        $user_idarr = [];
        $id_arr = [];
        foreach($importData_arr as $key => $importData)
        {

            //check empno exists, if not continue
            $userDetails = $this->getUserDetailsByEmployeeId($importData[0]);

            $userId = $userDetails->user_id ?? '';//by employee id
            $emp_id = $userDetails->id ?? '';//by employee id
            $emp_generated_id = $userDetails->emp_generated_id ?? '';
            $i = 1;
            // loop through date_sub
              
                if(!empty($userDetails)):
                    $ot_date = date('Y-m-d', strtotime($importData[3]));
                    $insertData = array(
                        "employee_id" =>  $emp_id,
                        "ot_date" =>  $ot_date,
                        "ot_hours"=> (int) $importData[5],
                        "description"   =>  $importData[4],
                    );
                    $is_ot_exist = EmployeeOvertime::where('employee_id',$emp_id)->whereDate('ot_date',$ot_date)->first();
                    if(!empty($is_ot_exist)):
                        EmployeeOvertime::where('id',$is_ot_exist->id)->update($insertData);
                    else:
                        EmployeeOvertime::create($insertData);
                    endif;
                    
                else:
                    $user_idarr[] = $key;
                    $i++;
                endif;
            
            
        }
        if(!empty($user_idarr) && count($user_idarr) > 0):
            $user_idarr = array_unique($user_idarr);
            $imp_user = implode(',',$user_idarr);
            $message = $imp_user.' Row no does not imported.';
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
}
