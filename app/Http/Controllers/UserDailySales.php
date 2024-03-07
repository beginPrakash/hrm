<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\UpSellingPeriod as UpSellingPeriodModel;
use App\Models\UpSellingHeading;
use Session, DB;
use App\Models\Employee;
use App\Models\DailySalesTargetUpselling;

class UserDailySales extends Controller
{
    public function __construct()
    {
        $this->title = 'Daily Sales';
    }

    public function index(Request $request)
    { 
        $user_id  = Session::get('user_id');
        $title = $this->title;

        //get login user detail
        $user = Employee::with('employee_branch')->where('user_id',$user_id)->first();
        $branch_id  = $user->branch ?? '';
        $company_id  = $user->company ?? '';
        $designation  = $user->designation ?? '';
        $same_branch_users = Employee::where('branch',$branch_id)->where('company',$company_id)->where('designation',$designation)->join('designations','employees.designation','designations.id')->where('designations.is_sales','1')->where('employees.user_id','!=',$user_id)->select('employees.id as id','employees.first_name as first_name')->get();
        $sell_id_default = UpSellingPeriodModel::where('company_id',$company_id)->where('branch_id',$branch_id)->orderBy('id','asc')->pluck('id')->join(',');
        $sell_id_default = explode(',',$sell_id_default);
        $search['search_date'] = $request->search_date ?? '';
        $search['user_id'] = $request->user_id ?? '';
        $search['sells_id'] = $request->sells_id ?? $sell_id_default;
        $search_sells_data = [];

        //get login user detail
        $user = Employee::with('employee_branch')->where('user_id',$user_id)->first();
        $branch_id  = $user->branch ?? '';
        $company_id  = $user->company ?? '';
        //get sells period by login user branch and company
        $sells_p_data = UpSellingPeriodModel::where('company_id',$company_id)->where('branch_id',$branch_id)->orderBy('id','asc')->pluck('item_name','id');
        if(!empty($search['sells_id'])):
            $search_sells_data = UpSellingPeriodModel::whereIn('id',$search['sells_id'])->orderBy('id','asc')->get();
        endif;
       // dd($search);
        //save data
        if($request->is_post == '1'):
            $search['search_date'] = $request->serch_date ?? date('Y-m-d');
            $search['user_id'] = $request->user_id ?? '';
            $search['sells_id'] = unserialize($request->s_id) ?? $sell_id_default;

            if(!empty($request->daily_sales_id)):
                $save_data =DailySalesTargetUpselling::find($request->daily_sales_id);
            else:
                $save_data = new DailySalesTargetUpselling();
            endif;
            $heading_price = $request->heading_price;
            $target_arr = [];
            $achieve_arr = [];
            if(!empty($heading_price) && count($heading_price) > 0):
                foreach($heading_price as $key => $val):
                    $is_target_heading = UpsellingHeading::whereNull('parent_id')->where('id',$val['id'])->first();
                    if(!empty($is_target_heading)):
                        $target_arr[] = $val['price'];
                    else:
                        $achieve_arr[] = $val['price'];
                    endif;
                endforeach;
            endif;
            if(!empty($target_arr)):
            $total_target_price = array_sum($target_arr);
            endif;
            if(!empty($total_achieve_price)):
            $total_achieve_price = array_sum($achieve_arr);
            endif;
            if(!empty($total_target_price) && !empty($total_achieve_price)):
                $total_cal = $total_achieve_price / $total_target_price * 100/10;
            endif;
            $sells_p_detail = UpSellingPeriodModel::find($request->sells_p_id);
            $save_data->company_id = $sells_p_detail->company_id;
            $save_data->branch_id = $sells_p_detail->branch_id;
            $save_data->sell_p_id = $sells_p_detail->id;
            $save_data->user_id = $request->user_id;
            $save_data->sales_date = (isset($request->serch_date) && !empty($request->serch_date)) ? date('Y-m-d',strtotime($request->serch_date)) : date('Y-m-d');
            $save_data->target_price = $request->achieve_target;
            $save_data->sale_price = $request->sales_val;
            $save_data->bill_count = $request->bill_count;
            $save_data->cc_count = $request->cc_count;
            $save_data->headings = json_encode($heading_price);
            $save_data->total_cal = $total_cal ?? 0;
            $save_data->action_by = $user_id;
            $save_data->save();
        endif;
        $daily_target = _updailytarget_total_cal_by_sell($company_id,$branch_id,$search['sells_id'],$search['search_date'],$search['user_id']);
        $mtd_target = _updailytarget_total_cal_by_sell($company_id,$branch_id,$search['sells_id'],$search['search_date'],$search['user_id'],'mtd');
        $daily_sale = _updailysale_total_cal_by_sell($company_id,$branch_id,$search['sells_id'],$search['search_date'],$search['user_id']);
        $mtd_sale = _updailysale_total_cal_by_sell($company_id,$branch_id,$search['sells_id'],$search['search_date'],$search['user_id'],'mtd');
        $daily_cc = _updailycc_count_cal_by_sell($company_id,$branch_id,$search['sells_id'],$search['search_date'],$search['user_id']);
        $mtd_cc = _updailycc_count_cal_by_sell($company_id,$branch_id,$search['sells_id'],$search['search_date'],$search['user_id'],'mtd');
        $daily_score = _updailyscore_avg($company_id,$branch_id,$search['sells_id'],$search['search_date'],$search['user_id']);
        $mtd_score = _updailyscore_avg($company_id,$branch_id,$search['sells_id'],$search['search_date'],$search['user_id'],'mtd');
        return view('up_selling_management.user_daily_sales',compact('same_branch_users','user','title','sells_p_data','search','search_sells_data','daily_target','mtd_target','daily_sale','mtd_sale','daily_cc','mtd_cc','daily_score','mtd_score'));
    }

    public function save_store_daily_sales(Request $request){
        dd($request->all());
        $user_id  = Session::get('user_id');
        $heading_price = $request->heading_price;
        $sells_p_detail = UpSellingPeriodModel::find($request->sells_p_id);
        if(!empty($sells_p_detail)):
            if(!empty($request->daily_sales_id)):
                $save_data =DailySalesTargetUpselling::find($request->daily_sales_id);
            else:
                $save_data = new DailySalesTargetUpselling();
            endif;
           
            $save_data->company_id = $sells_p_detail->company_id;
            $save_data->branch_id = $sells_p_detail->branch_id;
            $save_data->sell_p_id = $sells_p_detail->id;
            $save_data->sales_date = date('Y-m-d',strtotime($request->serch_date));
            $save_data->achieve_target = $request->achieve_target;
            $save_data->bill_count = $request->bill_count;
            $save_data->target_price = $request->target_price;
            $save_data->avg_bill_count = $request->bill_count;
            $save_data->headings = json_encode($heading_price);
            $save_data->action_by = $user_id;
            $save_data->save();

            $arr = [
                'success' => 'true',
                'sal_id' => $save_data->id ?? '',
            ];
            return response()->json($arr);
        endif;
    }
        
}
