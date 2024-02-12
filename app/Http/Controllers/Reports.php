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
use App\Models\CompanyDocuments;
use App\Models\TransportationDoc;
use App\Models\RegistrationType;
use App\Models\Transportation;

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

    public function company_report(Request $request)
    { 
        $search = [];
        $search['expiry_date'] = '';
        $search['to_date'] = '';
        $search['company'] = $_POST['company'] ?? '';
        $search['reg_name'] = $_POST['reg_name'] ?? '';
        $search['branch'] = $_POST['branch'] ?? '';
        $search['status'] = $_POST['status'] ?? '';

        $que_list = CompanyDocuments::with('company_details')->whereHas('company_details', function($q){
            $q->whereNotNull('name');
        });

        if(!empty($_POST['expiry_date']) && !empty($_POST['to_date']))
        {
            $search['expiry_date'] = $_POST['expiry_date'];
            $search['to_date'] = $_POST['to_date'];
            $startDate = date('Y-m-d', strtotime($_POST['expiry_date']));
            $to_date = date('Y-m-d', strtotime($_POST['to_date']));
            $que_list->whereBetween('expiry_date',array($startDate,$to_date));
        }elseif(empty($_POST['expiry_date']) && !empty($_POST['to_date']))
        {
            $search['to_date'] = $_POST['to_date'];
            $to_date = date('Y-m-d', strtotime($_POST['to_date']));
            $que_list->whereDate('expiry_date',$to_date);
        }elseif(!empty($_POST['expiry_date']) && empty($_POST['to_date']))
        {
            $search['expiry_date'] = $_POST['expiry_date'];
            $expiry_date = date('Y-m-d', strtotime($_POST['expiry_date']));
            $que_list->whereDate('expiry_date','>',$expiry_date);
        }

        if(isset($_POST['company']) && $_POST['company']!='')
        {
            $que_list->where('company_id',(int)$_POST['company']);
        }

        if(isset($_POST['branch']) && $_POST['branch']!='')
        {
            $que_list->where('branch_id',(int)$_POST['branch']);
        }

        if(isset($_POST['reg_name']) && $_POST['reg_name']!='')
        {
            $que_list->where(DB::raw("reg_name") , 'like', '%'.$_POST['reg_name'].'%');
        }

        if(isset($_POST['status']) && $_POST['status']!='')
        {
            $cur_date = date('Y-m-d');
            if($_POST['status'] == 'expired'):
                $que_list->whereDate('expiry_date','<',$cur_date);
            else:
                $que_list->whereDate('expiry_date','>=',$cur_date);
            endif;

        }
        

        $data_list = $que_list->get();

        $com_list = $que_list->select('company_id')->selectRaw('GROUP_CONCAT(id) as ids')
        ->groupBy('company_id')->orderBy('company_id','asc')->get();
        $company = Residency::where('status','active')->select('id','name')->pluck('name','id');
        $branch = Branch::where('status','active')->select('id','name')->pluck('name','id');
        if(isset($_POST['type']) && $_POST['type']=='pdf'):
            $pass_array = array(
                "data_list" => $data_list,
                "com_list" => $com_list,
            );
            $cdate = date('Y-m-d');
            $rname = $cdate.'_companyreport.pdf';
            $pdf = PDF::loadView('reports.company_report_pdf', $pass_array)->setPaper('a4', 'landscape')->setWarnings(false);
            //print_r($pdf);
            return $pdf->download($rname);
        endif;
        
        return view('reports.company',compact('data_list','search','company','branch'));
    }

    public function transport_report(Request $request)
    { 
        $search = [];
        $search['expiry_date'] = '';
        $search['to_date'] = '';
        $search['company'] = $_POST['company'] ?? '';
        $search['subcompany'] = $_POST['subcompany'] ?? '';
        $search['car_name'] = $_POST['car_name'] ?? '';
        $search['doc_name'] = $_POST['doc_name'] ?? '';
        $search['status'] = $_POST['status'] ?? '';
        $search['reg_type'] = $_POST['reg_type'] ?? '';

        $que_list = TransportationDoc::with('trans_detail')->whereHas('trans_detail', function($q){
            $q->whereNotNull('expiry_date');
        });

        if(!empty($_POST['expiry_date']) && !empty($_POST['to_date']))
        {
            $search['expiry_date'] = $_POST['expiry_date'];
            $search['to_date'] = $_POST['to_date'];
            $startDate = date('Y-m-d', strtotime($_POST['expiry_date']));
            $to_date = date('Y-m-d', strtotime($_POST['to_date']));
            $que_list->whereBetween('expiry_date',array($startDate,$to_date));
        }elseif(empty($_POST['expiry_date']) && !empty($_POST['to_date']))
        {
            $search['to_date'] = $_POST['to_date'];
            $to_date = date('Y-m-d', strtotime($_POST['to_date']));
            $que_list->whereDate('expiry_date',$to_date);
        }elseif(!empty($_POST['expiry_date']) && empty($_POST['to_date']))
        {
            $search['expiry_date'] = $_POST['expiry_date'];
            $expiry_date = date('Y-m-d', strtotime($_POST['expiry_date']));
            $que_list->whereDate('expiry_date','>',$expiry_date);
        }

        if(isset($_POST['company']) && $_POST['company']!='')
        {
            $company = $_POST['company'];
            $que_list->whereHas('trans_detail', function($q) use($company){
                $q->where('under_company',(int)$company);
            });
        }

        if(isset($_POST['subcompany']) && $_POST['subcompany']!='')
        {
            $subcompany = $_POST['subcompany'];
            $que_list->whereHas('trans_detail', function($q) use($subcompany){
                $q->where('under_subcompany',(int)$subcompany);
            });
        }

        if(isset($_POST['car_name']) && $_POST['car_name']!='')
        {
            $car_name = $_POST['car_name'];
            $que_list->whereHas('trans_detail', function($q) use($car_name){
                $q->where(DB::raw("car_name") , 'like', '%'.$car_name.'%');
            });
        }

        if(isset($_POST['doc_name']) && $_POST['doc_name']!='')
        {
            $que_list->where(DB::raw("doc_name") , 'like', '%'.$_POST['doc_name'].'%');
        }

        if(isset($_POST['reg_type']) && $_POST['reg_type']!='')
        {
            $que_list->where('reg_type',$_POST['reg_type']);
        }

        if(isset($_POST['status']) && $_POST['status']!='')
        {
            $cur_date = date('Y-m-d');
            if($_POST['status'] == 'expired'):
                $que_list->whereDate('expiry_date','<',$cur_date);
            else:
                $que_list->whereDate('expiry_date','>=',$cur_date);
            endif;

        }
    
        $data_list = $que_list->get();
        // $com_list = $que_list->select('id','transportation_id')->selectRaw('GROUP_CONCAT(id) as ids')
        // ->groupBy('transportation_id')->orderBy('transportation_id','asc')->get();
        $com_list = $que_list->select('company')->selectRaw('GROUP_CONCAT(id) as ids')
        ->groupBy('company')->orderBy('company','asc')->get();

        $company = Residency::where('status','active')->select('id','name')->pluck('name','id');
        $reg_type = RegistrationType::select('id','name')->pluck('name','id');
        if(isset($_POST['type']) && $_POST['type']=='pdf'):
            $pass_array = array(
                "data_list" => $data_list,
                "com_list" => $com_list,
            );
            $cdate = date('Y-m-d');
            $rname = $cdate.'_transportreport.pdf';
            $pdf = PDF::loadView('reports.transport_report_pdf', $pass_array)->setPaper('a4', 'landscape')->setWarnings(false);
            //print_r($pdf);
            return $pdf->download($rname);
        endif;
        
        return view('reports.transport',compact('data_list','search','company','reg_type'));
    }

    public function passport_report(Request $request)
    { 
        $search = [];
        $search['expiry_date'] = '';
        $search['to_date'] = '';
        $search['user_ids'] = $_POST['user_ids'] ?? '';
        $search['is_passport'] = $_POST['is_passport'] ?? '';
        $search['hiring_type'] = $_POST['hiring_type'] ?? '';
        $search['status'] = $_POST['status'] ?? '';
        $search['designation'] = $_POST['designation'] ?? '';

        $que_list = Employee::with('employee_details')->whereNotNUll('passport_expiry');

        if(!empty($_POST['expiry_date']) && !empty($_POST['to_date']))
        {
            $search['expiry_date'] = $_POST['expiry_date'];
            $search['to_date'] = $_POST['to_date'];
            $startDate = date('Y-m-d', strtotime($_POST['expiry_date']));
            $to_date = date('Y-m-d', strtotime($_POST['to_date']));
            $que_list->whereBetween('passport_expiry',array($startDate,$to_date));
        }elseif(empty($_POST['expiry_date']) && !empty($_POST['to_date']))
        {
            $search['to_date'] = $_POST['to_date'];
            $to_date = date('Y-m-d', strtotime($_POST['to_date']));
            $que_list->whereDate('passport_expiry',$to_date);
        }elseif(!empty($_POST['expiry_date']) && empty($_POST['to_date']))
        {
            $search['expiry_date'] = $_POST['expiry_date'];
            $expiry_date = date('Y-m-d', strtotime($_POST['expiry_date']));
            $que_list->whereDate('passport_expiry','>',$expiry_date);
        }

        if(isset($_POST['user_ids']) && $_POST['user_ids']!='')
        {
            $que_list->whereIn('id',$_POST['user_ids']);
        }

        if(isset($_POST['designation']) && $_POST['designation']!='')
        {
            $que_list->where('designation',(int)$_POST['designation']);
        }

        if(isset($_POST['is_passport']) && $_POST['is_passport']!='')
        {
            $que_list->where('is_passport',(int)$_POST['is_passport']);
        }

        if(isset($_POST['hiring_type']) && $_POST['hiring_type']!='')
        {
            $que_list->where('hiring_type',$_POST['hiring_type']);
        }

        if(isset($_POST['status']) && $_POST['status']!='')
        {
            $cur_date = date('Y-m-d');
            if($_POST['status'] == 'expired'):
                $que_list->whereDate('passport_expiry','<',$cur_date)->whereNotNull('passport_expiry');
            else:
                $que_list->whereDate('passport_expiry','>=',$cur_date)->whereNotNull('passport_expiry');
            endif;

        }
       
        $data_list = $que_list->get();
        $user_list = Employee::with('employee_details')->whereNotNUll('passport_expiry')->get();
        $designation = Designations::where('status','active')->select('id','name')->pluck('name','id');
        if(isset($_POST['type']) && $_POST['type']=='pdf'):
            $pass_array = array(
                "data_list" => $data_list,
            );
            $cdate = date('Y-m-d');
            $rname = $cdate.'_passportreport.pdf';
            $pdf = PDF::loadView('reports.passport_report_pdf', $pass_array)->setPaper('a4', 'landscape')->setWarnings(false);
            //print_r($pdf);
            return $pdf->download($rname);
        endif;
        
        return view('reports.passport',compact('data_list','search','designation','user_list'));
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
