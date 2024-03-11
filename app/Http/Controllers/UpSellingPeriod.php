<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\UpSellingPeriod as UpSellingPeriodModel;
use App\Models\Residency;
use App\Models\Branch;
use Session;
use App\Models\UpSellingHeading as UpSellingHeadingModel;

class UpSellingPeriod extends Controller
{
    public function __construct()
    {
        $this->title = 'Selling Period';
    }

    public function index(Request $request)
    { 
        $search = [];
        $title = $this->title;
        $selling_data = UpSellingPeriodModel::orderBy('id','desc');
        if(isset($request->company) && !empty($request->company))
        {
            $search['company'] = $request->company;
            $selling_data->where('company_id',$request->company);
        }
        if(isset($request->branch) && !empty($request->branch))
        {
            $search['branch'] = $request->branch;
            $selling_data->where('branch_id',$request->branch);
        }
        $selling_data = $selling_data->get();

        $company_list = Residency::where('status','active')->pluck('name','id');
        $branch_list = Branch::where('status','active')->pluck('name','id');
        return view('up_selling_management.selling_period',compact('search','title','selling_data','company_list','branch_list'));
    }

    public function store(Request $request){
        $login_user_id = Session::get('user_id');
        if(!empty($request->selling_id)):
            $save_data = UpSellingPeriodModel::find($request->selling_id);
            if(!empty($save_data)):
                $save_data->item_name = $request->item_name;
                $save_data->is_bill_count = $request->is_bill_count ?? 0;
                $save_data->is_cc = $request->is_cc ?? 0;
                $save_data->save();
            endif;
            return redirect()->back()->with('success','Data updated successfully');
        else:
            $branch_ids = explode(',',$request->branch_id);
            $company_ids = explode(',',$request->company_id);
            if(!empty($company_ids) && !empty($branch_ids)):
                if(!empty($branch_ids) && count($branch_ids) > 0):
                    foreach($branch_ids as $key => $val):
                        $save_data = new UpSellingPeriodModel();
                        $branch_data = Branch::find($val);
                        $save_data->company_id = $branch_data->residency ?? NULL;
                        $save_data->branch_id = $val;
                        $save_data->item_name = $request->item_name;
                        $save_data->is_bill_count = $request->is_bill_count ?? 0;
                        $save_data->is_cc = $request->is_cc ?? 0;
                        $save_data->created_by = $login_user_id;
                        $save_data->save();
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
        $data = UpSellingPeriodModel::find($request->id);
        $pass_array=array(
			'data' => $data,
        );
        $html =  view('up_selling_management.selling_period_modal', $pass_array )->render();
		$arr = [
			'success' => 'true',
			'html' => $html
		];
		return response()->json($arr);

    }

    public function delete(Request $request){
        $id = $request->selling_id ?? '';
        UpSellingHeadingModel::where('sell_p_id',$id)->delete();
        UpSellingPeriodModel::where('id',$id)->delete();
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
            $d = UpSellingPeriodModel::find($id);
            $d->update($data);
        endif;  
        return redirect()->back()->with('success', $message);
    }
        
}
