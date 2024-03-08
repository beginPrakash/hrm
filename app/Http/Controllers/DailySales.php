<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\SellingPeriod as SellingPeriodModel;
use App\Models\TrackingHeading as TrackingHeadingModel;
use Session, DB;
use App\Models\Employee;
use App\Models\StoreDailySales;

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
//dd($request->all());
        //get login user detail
        $user = Employee::with('employee_branch')->where('user_id',$user_id)->first();
        $branch_id  = $user->branch ?? '';
        $company_id  = $user->company ?? '';
        $sell_id_default = SellingPeriodModel::where('company_id',$company_id)->where('branch_id',$branch_id)->where('is_show','1')->orderBy('id','asc')->pluck('id')->join(',');;
        $sell_id_default = explode(',',$sell_id_default);
        $search['search_date'] = $request->search_date ?? date('d-m-Y');
        $search['sells_id'] = $request->sells_id ?? $sell_id_default;
        $search_sells_data = [];

        //get login user detail
        $user = Employee::with('employee_branch')->where('user_id',$user_id)->first();
        $branch_id  = $user->branch ?? '';
        $company_id  = $user->company ?? '';
        //get sells period by login user branch and company
        $sells_p_data = SellingPeriodModel::where('company_id',$company_id)->where('branch_id',$branch_id)->where('is_show','1')->orderBy('id','asc')->pluck('item_name','id');
        if(!empty($search['sells_id'])):
            $search_sells_data = SellingPeriodModel::whereIn('id',$search['sells_id'])->where('is_show','1')->orderBy('id','asc')->get();
        endif;
        $heading_price = $request->heading_price;
        $sells_p_detail = SellingPeriodModel::find($request->sells_p_id);
        if(!empty($sells_p_detail)):
            
            $search['search_date'] = $request->serch_date ?? '';
            $search['sells_id'] = unserialize($request->sl_id) ?? $sell_id_default;
            if(!empty($request->daily_sales_id)):
                $save_data =StoreDailySales::find($request->daily_sales_id);
            else:
                $save_data = new StoreDailySales();
            endif;

            $save_data->company_id = $sells_p_detail->company_id;
            $save_data->branch_id = $sells_p_detail->branch_id;
            $save_data->sell_p_id = $sells_p_detail->id;
            $save_data->sales_date = date('Y-m-d',strtotime($request->serch_date));
            $save_data->achieve_target = $request->achieve_target;
            $save_data->bill_count = $request->bill_count;
            $save_data->target_price = $request->target_price;
            $save_data->avg_bill_count = $request->bill_count_avg;
            $save_data->headings = json_encode($heading_price);
            $save_data->action_by = $user_id;
            $save_data->save();
        endif;

        $today_target = _target_total_cal_by_sell($company_id,$branch_id,$search['sells_id'],$search['search_date']);
        $today_sale = _dailysale_total_cal($company_id,$branch_id,$search['sells_id'],$search['search_date']);
        $today_vari = $today_sale - $today_target;
        $today_bill_avg  = _dailysale_bill_avg($company_id,$branch_id,$search['sells_id'],$search['search_date']);
        $no_of_days = date('d',strtotime($search['search_date']));
        $mtd_target = $no_of_days * $today_target;
        $mtd_sale = _dailysale_total_cal($company_id,$branch_id,$search['sells_id'],$search['search_date'],'mtd');
        $mtd_vari = $mtd_sale - $mtd_target;
        $mtd_bill_avg  = _dailysale_bill_avg($company_id,$branch_id,$search['sells_id'],$search['search_date'],'mtd');
  
        return view('selling_management.store_daily_sales',compact('user','title','sells_p_data','search','search_sells_data','today_target','today_sale','today_vari','today_bill_avg','mtd_target','mtd_sale','mtd_vari','mtd_bill_avg'));
    }

    public function save_store_daily_sales(Request $request){
        $user_id  = Session::get('user_id');
        $heading_price = $request->heading_price;
        $sells_p_detail = SellingPeriodModel::find($request->sells_p_id);
        if(!empty($sells_p_detail)):
            if(!empty($request->daily_sales_id)):
                $save_data =StoreDailySales::find($request->daily_sales_id);
            else:
                $save_data = new StoreDailySales();
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
