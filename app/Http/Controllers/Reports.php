<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Session, PDF, DB;
use App\Models\Employee;
use App\Models\Residency;
use App\Models\Subresidency;
use App\Models\Branch;
use App\Models\Departments;
use App\Models\Designations;

class Reports extends Controller
{
    public function __construct()
    {
        $this->title = 'Reports';
    }

    public function civil_report(Request $request)
    { 
        $search = [];
        $search['expiry_date'] = '';
        $search['to_date'] = '';
        $search['company'] = $_POST['company'] ?? '';
        $search['subcompany'] = $_POST['subcompany'] ?? '';
        $search['branch'] = $_POST['branch'] ?? '';
        $search['department'] = $_POST['department'] ?? '';
        $search['designation'] = $_POST['designation'] ?? '';
        $search['user_ids'] = $_POST['user_ids'] ?? '';

        $que_list = Employee::with('employee_details')->whereNotNUll('joining_date')->whereHas('employee_details', function($q){
            $q->whereNotNull('expi_c_id')->whereNotNull('civil_cost');
        });

        if(!empty($_POST['expiry_date']) && !empty($_POST['to_date']))
        {
            $search['expiry_date'] = $_POST['expiry_date'];
            $search['to_date'] = $_POST['to_date'];
            $startDate = date('Y-m-d', strtotime($_POST['expiry_date']));
            $to_date = date('Y-m-d', strtotime($_POST['to_date']));
            $que_list->whereHas('employee_details', function($q) use($startDate,$to_date){
                $q->whereBetween('expi_c_id',array($startDate,$to_date));
            });
        }elseif(empty($_POST['expiry_date']) && !empty($_POST['to_date']))
        {
            $search['to_date'] = $_POST['to_date'];
            $to_date = date('Y-m-d', strtotime($_POST['to_date']));
            $que_list->whereHas('employee_details', function($q) use($to_date){
                $q->whereDate('expi_c_id',$to_date);
            });
        }elseif(!empty($_POST['expiry_date']) && empty($_POST['to_date']))
        {
            $search['expiry_date'] = $_POST['expiry_date'];
            $expiry_date = date('Y-m-d', strtotime($_POST['expiry_date']));
            $que_list->whereHas('employee_details', function($q) use($expiry_date){
                $q->whereDate('expi_c_id','>',$expiry_date);
            });
        }

        if(isset($_POST['company']) && $_POST['company']!='')
        {
            $que_list->where('company',(int)$_POST['company']);
        }

        if(isset($_POST['user_ids']) && $_POST['user_ids']!='')
        {
            $que_list->whereIn('id',$_POST['user_ids']);
        }

        if(isset($_POST['subcompany']) && $_POST['subcompany']!='')
        {
            $que_list->where('subcompany',(int)$_POST['subcompany']);
        }

        if(isset($_POST['branch']) && $_POST['branch']!='')
        {
            $que_list->where('branch',(int)$_POST['branch']);
        }

        if(isset($_POST['department']) && $_POST['department']!='')
        {
            $que_list->where('department',(int)$_POST['department']);
        }

        if(isset($_POST['designation']) && $_POST['designation']!='')
        {
            $que_list->where('designation',(int)$_POST['designation']);
        }
       
        $data_list = $que_list->get();
        $com_list = $que_list->select('company')->selectRaw('GROUP_CONCAT(id) as ids')
        ->groupBy('company')->orderBy('company','asc')->get();
        $company = Residency::where('status','active')->select('id','name')->pluck('name','id');
        $subcompany = Subresidency::where('status','active')->select('id','name')->pluck('name','id');
        $branch = Branch::where('status','active')->select('id','name')->pluck('name','id');
        $department = Departments::where('status','active')->select('id','name')->pluck('name','id');
        $designation = Designations::where('status','active')->select('id','name')->pluck('name','id');
        $user_list = Employee::with('employee_details')->whereNotNUll('joining_date')->whereHas('employee_details', function($q){
            $q->whereNotNull('expi_c_id')->whereNotNull('civil_cost');
        });
        $user_list = $user_list->get();
        if(isset($_POST['type']) && $_POST['type']=='pdf'):
            $pass_array = array(
                "data_list" => $data_list,
                "com_list" => $com_list,
            );
            $cdate = date('Y-m-d');
            $rname = $cdate.'_civilreport.pdf';
            $pdf = PDF::loadView('reports.civil_report_pdf', $pass_array)->setPaper('a4', 'landscape')->setWarnings(false);
            //print_r($pdf);
            return $pdf->download($rname);
        endif;
        
        return view('reports.index',compact('data_list','user_list','search','company','subcompany','branch','department','designation'));
    }

    public function baladiya_report(Request $request)
    { 
        $search = [];
        $search['expiry_date'] = '';
        $search['to_date'] = '';
        $search['company'] = $_POST['company'] ?? '';
        $search['subcompany'] = $_POST['subcompany'] ?? '';
        $search['branch'] = $_POST['branch'] ?? '';
        $search['department'] = $_POST['department'] ?? '';
        $search['designation'] = $_POST['designation'] ?? '';
        $search['user_ids'] = $_POST['user_ids'] ?? '';

        $que_list = Employee::with('employee_details')->whereNotNUll('joining_date')->whereHas('employee_details', function($q){
            $q->whereNotNull('expi_b_id')->whereNotNull('baladiya_cost');
        });

        if(!empty($_POST['expiry_date']) && !empty($_POST['to_date']))
        {
            $search['expiry_date'] = $_POST['expiry_date'];
            $search['to_date'] = $_POST['to_date'];
            $startDate = date('Y-m-d', strtotime($_POST['expiry_date']));
            $to_date = date('Y-m-d', strtotime($_POST['to_date']));
            $que_list->whereHas('employee_details', function($q) use($startDate,$to_date){
                $q->whereBetween('expi_b_id',array($startDate,$to_date));
            });
        }elseif(empty($_POST['expiry_date']) && !empty($_POST['to_date']))
        {
            $search['to_date'] = $_POST['to_date'];
            $to_date = date('Y-m-d', strtotime($_POST['to_date']));
            $que_list->whereHas('employee_details', function($q) use($to_date){
                $q->whereDate('expi_b_id',$to_date);
            });
        }elseif(!empty($_POST['expiry_date']) && empty($_POST['to_date']))
        {
            $search['expiry_date'] = $_POST['expiry_date'];
            $expiry_date = date('Y-m-d', strtotime($_POST['expiry_date']));
            $que_list->whereHas('employee_details', function($q) use($expiry_date){
                $q->whereDate('expi_b_id','>',$expiry_date);
            });
        }

        if(isset($_POST['company']) && $_POST['company']!='')
        {
            $que_list->where('company',(int)$_POST['company']);
        }

        if(isset($_POST['user_ids']) && $_POST['user_ids']!='')
        {
            $que_list->whereIn('id',$_POST['user_ids']);
        }

        if(isset($_POST['subcompany']) && $_POST['subcompany']!='')
        {
            $que_list->where('subcompany',(int)$_POST['subcompany']);
        }

        if(isset($_POST['branch']) && $_POST['branch']!='')
        {
            $que_list->where('branch',(int)$_POST['branch']);
        }

        if(isset($_POST['department']) && $_POST['department']!='')
        {
            $que_list->where('department',(int)$_POST['department']);
        }

        if(isset($_POST['designation']) && $_POST['designation']!='')
        {
            $que_list->where('designation',(int)$_POST['designation']);
        }
       
        $data_list = $que_list->get();
        $com_list = $que_list->select('company')->selectRaw('GROUP_CONCAT(id) as ids')
        ->groupBy('company')->orderBy('company','asc')->get();
        $company = Residency::where('status','active')->select('id','name')->pluck('name','id');
        $subcompany = Subresidency::where('status','active')->select('id','name')->pluck('name','id');
        $branch = Branch::where('status','active')->select('id','name')->pluck('name','id');
        $department = Departments::where('status','active')->select('id','name')->pluck('name','id');
        $designation = Designations::where('status','active')->select('id','name')->pluck('name','id');
        $user_list = Employee::with('employee_details')->whereNotNUll('joining_date')->whereHas('employee_details', function($q){
            $q->whereNotNull('expi_b_id')->whereNotNull('baladiya_cost');
        });
        $user_list = $user_list->get();
        if(isset($_POST['type']) && $_POST['type']=='pdf'):
            $pass_array = array(
                "data_list" => $data_list,
                "com_list" => $com_list,
            );
            $cdate = date('Y-m-d');
            $rname = $cdate.'_baladeyareport.pdf';
            $pdf = PDF::loadView('reports.baladeya_report_pdf', $pass_array)->setPaper('a4', 'landscape')->setWarnings(false);
            //print_r($pdf);
            return $pdf->download($rname);
        endif;
        
        return view('reports.baladiya',compact('data_list','user_list','search','company','subcompany','branch','department','designation'));
    }

    public function listuserbycompany(Request $request){
        $user_list = Employee::with('employee_details')->where('company',$request->id ?? '')->whereNotNUll('joining_date')->whereHas('employee_details', function($q){
            $q->whereNotNull('expi_c_id')->whereNotNull('civil_cost');
        });
        if(!empty($request->sid)):
            $user_list = $user_list->where('subcompany',$request->sid);
        endif;
        $user_list = $user_list->get();
        $option_html = '';
        $option_html.= '<option value="">Select User</option>';
        if(!empty($user_list) && count($user_list) > 0):
            foreach($user_list as $key => $val):
                $option_html.= '<option value="'.$val->id.'">'.$val->first_name.' '.$val->last_name.'</option>';
            endforeach;
        endif;
        $arr = [
			'success' => 'true',
			'res' => $option_html
		];
		return response()->json($arr);
    }

    public function blistuserbycompany(Request $request){
        $user_list = Employee::with('employee_details')->where('company',$request->id ?? '')->whereNotNUll('joining_date')->whereHas('employee_details', function($q){
            $q->whereNotNull('expi_b_id')->whereNotNull('baladiya_cost');
        });
        if(!empty($request->sid)):
            $user_list = $user_list->where('subcompany',$request->sid);
        endif;
        $user_list = $user_list->get();
        $option_html = '';
        $option_html.= '<option value="">Select User</option>';
        if(!empty($user_list) && count($user_list) > 0):
            foreach($user_list as $key => $val):
                $option_html.= '<option value="'.$val->id.'">'.$val->first_name.' '.$val->last_name.'</option>';
            endforeach;
        endif;
        $arr = [
			'success' => 'true',
			'res' => $option_html
		];
		return response()->json($arr);
    }

        
}
