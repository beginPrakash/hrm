<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\SellingPeriod;
use App\Models\Residency;
use App\Models\Branch;
use App\Models\SalesTargetMaster;

class SalesTarget extends Controller
{
    public function __construct()
    {
        $this->title = 'Sales Target';
    }

    public function index(Request $request)
    { 
        $search = [];
        $b_list = [];
        $s_list = [];
        $title = $this->title;
        $search['month'] = date('m');
        $selling_data = SellingPeriod::orderBy('id','desc');

        if(isset($request->month) && !empty($request->month))
        {
            $search['month'] = $request->month ?? date('m');
        }

        if(isset($request->company) && !empty($request->company))
        {
            $search['company'] = $request->company;
            $b_list = Branch::whereIn('residency',$request->company)->orderBy('residency')->get();
        }
        if(isset($request->brnach_list) && !empty($request->brnach_list))
        {
            $search['brnach_list'] = $request->brnach_list;
            //dd($search['brnach_list']);
            $s_list = SellingPeriod::whereIn('company_id',$request->company)->whereIn('branch_id',$request->brnach_list)->groupBy('item_name')->get();
        }
        if(isset($request->sells_list) && !empty($request->sells_list))
        {
            $search['sells_list'] = $request->sells_list;
        }

       
        $selling_data = $selling_data->get();
        $company_list = Residency::where('status','active')->pluck('name','id');
        $branch_list = Branch::where('status','active')->pluck('name','id');
        return view('selling_management.sales_target',compact('search','b_list','s_list','title','selling_data','company_list','branch_list'));
    }


    public function store(Request $request){
        $month = $request->month ?? date('m');
        $no_of_monthday = cal_days_in_month(CAL_GREGORIAN, $month, date('y'));
        if(isset($request->target_price) && count($request->target_price) > 0):
            foreach($request->target_price as $key => $val):
                if(!empty($request->sales_tar_id[$key])):
                    $save_data = SalesTargetMaster::find($request->sales_tar_id[$key]);
                else:
                    $save_data = new SalesTargetMaster();
                endif;
                $save_data->month = $request->month ?? NULL;
                $save_data->company_id = $request->company_id ?? NULL;
                $save_data->branch_id = $request->branch_id ?? NULL;
                $save_data->sell_p_id = $request->sell_id[$key] ?? NULL;
                $save_data->target_price = $val ?? NULL;
                $save_data->per_day_price = $request->per_day_price[$key] ?? NULL;
                $save_data->no_of_monthday = $no_of_monthday ?? NULL;
                $save_data->save();
            endforeach;
        endif;

        $arr = [
            'sal_id' => $save_data->id ?? '',
			'success' => 'true',
		];
		return response()->json($arr);
       
    }


    public function branchlistbycompany(Request $request)
    {
        $data = Branch::whereIn('residency',explode(',',$request->sel_val))->orderBy('residency')->get();
        $pass_array=array(
			'data' => $data,
            'branch_ids' => $request->sel_val
        );
        $html =  view('selling_management.branch_list', $pass_array )->render();
		$arr = [
			'success' => 'true',
			'html' => $html
		];
		return response()->json($arr);

    }

    public function sellplistbycompany(Request $request)
    {
        $branch_ids = $request->sel_val;
        $company_ids = $request->company_id;
        $data = SellingPeriod::whereIn('company_id',explode(',',$company_ids))->whereIn('branch_id',explode(',',$branch_ids))->groupBy('item_name')->get();
        $pass_array=array(
			'data' => $data,
        );
        $html =  view('selling_management.sells_p_list', $pass_array )->render();
		$arr = [
			'success' => 'true',
			'html' => $html
		];
		return response()->json($arr);

    }

    

    public function delete(Request $request){
        $id = $request->selling_id ?? '';
        SellingPeriod::where('id',$id)->delete();
        return redirect()->back()->with('success','Data deleted successfully');
    }

    public function statuschange($id='',$status=''){
        $message = '';
        if($status == '1'):
            $data = ['is_show' => 0];
            $message = "Data changed successfully.";
        elseif($status == '0'):
            $data = ['is_show' => 1];
            $message = "Data changed successfully.";
        endif;
        if (isset($data) && count($data)):
            $d = SellingPeriod::find($id);
            $d->update($data);
        endif;  
        return redirect()->back()->with('success', $message);
    }
        
}
