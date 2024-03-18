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
use App\Models\EmployeeBonus;

class BonusController extends Controller
{
    public function index()
    {
        $userdetails = Employee::with('employee_designation')->where('status', 'active')->get();
        $sql_que = EmployeeBonus::orderBy('id','desc');
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
                $sql_que = $sql_que->whereBetween('bonus_date',array($startDate,$to_date));
                
            }elseif(empty($_POST['from_date']) && !empty($_POST['to_date']))
            {
                $search['to_date'] = $_POST['to_date'];
                $to_date = date('Y-m-d', strtotime($_POST['to_date']));
                $sql_que = $sql_que->whereDate('bonus_date',$to_date);
            }elseif(!empty($_POST['from_date']) && empty($_POST['to_date']))
            {
                $search['from_date'] = $_POST['from_date'];
                $from_date = date('Y-m-d', strtotime($_POST['from_date']));
                $sql_que = $sql_que->whereDate('bonus_date','>=',$from_date);
            }
            
            
        }
        
        
        $bonus_data = $sql_que->get();
        return view('lts.bonus', compact('bonus_data','where','search','userdetails'));
    }  

    public function store(Request $request)
    {
        //check same date bonus exist
        $bonus_date = date('Y-m-d',strtotime($request->bonus_date));
        if(empty($request->id)):
            $bonus_exist = EmployeeBonus::where('employee_id',$request->employee_id)->where('bonus_date', $bonus_date)->first();
        else:
            $bonus_exist = EmployeeBonus::where('employee_id',$request->employee_id)->where('id','!=',$request->id)->where('bonus_date', $bonus_date)->first();
        endif;
        if(!empty($bonus_exist)):
            return redirect()->back()->with('error','Bonus is already created.Please select another date.');
        else:
            $insertArray = array(
                'employee_id'        =>  $request->employee_id,
                'bonus_date'     =>  $bonus_date,
                'bonus_amount'     =>  $request->bonus_amount,
                'remarks'     =>  $request->remarks,
                'title'=>  $request->title,
            );
            if(!empty($request->id)):
                $bonus_data = EmployeeBonus::where('id', $request->id)->update($insertArray);
            else:
                $bonus_data = EmployeeBonus::create($insertArray);
            endif;                    
            return redirect('/bonus')->with('success','Bonus saved successfully!');
        endif;
    }


    public function details(Request $request)
    {
        $userdetails = Employee::with('employee_designation')->where('status', 'active')->get();
        $bonusData = EmployeeBonus::find($request->id);
        $pass_array=array(
			'userdetails' => $userdetails,
            'bonusData' => $bonusData
        );
        $html =  view('lts.bonus_modal', $pass_array )->render();
		$arr = [
			'success' => 'true',
			'html' => $html
		];
		return response()->json($arr);

    }

    public function delete_bonus(Request $request)
    {
        $bonusData = EmployeeBonus::where('id',$request->bonus_id)->delete();
		return redirect('/bonus')->with('success','Bonus deleted successfully!');

    }

    public function bonus_export()
    {
        $csvFileName = 'bonus_sample.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
        ];

        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['Id', 'Name', 'Position', 'Date', 'Title', 'Remarks', 'Amount']); // Add more headers as needed
        fputcsv($handle, ['50026','Hashim showkat','IT Head', '09/03/2024','target achieved', 'testetsteteee','20']); // Add more fields as needed
        fclose($handle);

        return Response::make('', 200, $headers);
    }

    public function bonus_import(Request $request)
    { 
        //validate file
        $validate = $this->validateCSV($request->file('bonus_file'));
        if($validate['status'] == 1)
        {
            //call excel/csv function
            $import = $this->importCSV($request->file('bonus_file'));
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
        $location = 'uploads/bonus';
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
                    $bonus_date = date('Y-m-d', strtotime($importData[3]));
                    $insertData = array(
                        "employee_id" =>  $emp_id,
                        "title"   =>  $importData[4],
                        "bonus_date" =>  $bonus_date,
                        "bonus_amount"=> (int) $importData[6],
                        "remarks"   =>  $importData[5],
                    );
                    $is_bonus_exist = EmployeeBonus::where('employee_id',$emp_id)->whereDate('bonus_date',$bonus_date)->first();
                    if(!empty($is_bonus_exist)):
                        EmployeeBonus::where('id',$is_bonus_exist->id)->update($insertData);
                    else:
                        EmployeeBonus::create($insertData);
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
