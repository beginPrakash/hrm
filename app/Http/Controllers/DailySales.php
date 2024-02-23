<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\SellingPeriod as SellingPeriodModel;
use App\Models\TrackingHeading as TrackingHeadingModel;
use Session;
use App\Models\Employee;

class DailySales extends Controller
{
    public function __construct()
    {
        $this->title = 'Daily Sales';
    }

    public function index(Request $request)
    { 
        $user_id  = Session::get('user_id');
        $title = $this->title;
        $search['search_date'] = $request->search_date ?? '';
        $search['sells_id'] = $request->sells_id ?? '';
        $search_sells_data = [];

        //get login user detail
        $user = Employee::with('employee_branch')->where('user_id',$user_id)->first();
        $branch_id  = $user->branch ?? '';
        $company_id  = $user->company ?? '';
        //get sells period by login user branch and company
        $sells_p_data = SellingPeriodModel::where('company_id',$company_id)->where('branch_id',$branch_id)->orderBy('id','asc')->pluck('item_name','id');
        if(!empty($search['sells_id'])):
            $search_sells_data = SellingPeriodModel::whereIn('id',$search['sells_id'])->orderBy('id','asc')->get();
        endif;
        return view('selling_management.store_daily_sales',compact('user','title','sells_p_data','search','search_sells_data'));
    }

    public function store(Request $request){
        if(!empty($request->tracking_id)):
            $save_data = TrackingHeadingModel::find($request->tracking_id);
            if(!empty($save_data)):
                $save_data->title = $request->item_name;
                $save_data->save();
            endif;
            return redirect()->back()->with('success','Data updated successfully');
        else:
            $branch_ids = explode(',',$request->branch_id);
            $company_ids = explode(',',$request->company_id);
            $selling_ids = explode(',',$request->sell_id);
            if(!empty($company_ids) && !empty($branch_ids) && !empty($selling_ids)):
                if(!empty($selling_ids) && count($selling_ids) > 0):
                    foreach($selling_ids as $key => $val):
                        
                        $sell_data = SellingPeriodModel::find($val);
                        $selling_data = SellingPeriodModel::whereIn('company_id',$company_ids)->whereIn('branch_id',$branch_ids)->where('item_name',$sell_data->item_name)->get();
                        if(!empty($selling_data)):
                            foreach($selling_data as $skey => $sval):
                                $save_data = new TrackingHeadingModel();
                                $save_data->company_id = $sval->company_id ?? NULL;
                                $save_data->branch_id = $sval->branch_id ?? NULL;
                                $save_data->sell_p_id = $sval->id;
                                $save_data->title = $request->item_name;
                                $save_data->save();
                            endforeach;
                        endif;
                    endforeach;
                endif;
                return redirect()->back()->with('success','Data saved successfully');
            else:
                return redirect()->back()->with('error','First select company and branch');
            endif;
        endif;
    }

    public function getsellingdetaiById(Request $request)
    {
        $data = TrackingHeadingModel::find($request->id);
        $pass_array=array(
			'data' => $data,
        );
        $html =  view('selling_management.tracking_heading_modal', $pass_array )->render();
		$arr = [
			'success' => 'true',
			'html' => $html
		];
		return response()->json($arr);

    }

    public function delete(Request $request){
        $id = $request->selling_id ?? '';
        TrackingHeadingModel::where('id',$id)->delete();
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
            $d = TrackingHeadingModel::find($id);
            $d->update($data);
        endif;  
        return redirect()->back()->with('success', $message);
    }
        
}
