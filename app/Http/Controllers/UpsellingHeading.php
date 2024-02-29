<?php

namespace App\Http\Controllers;
use Session;
use Illuminate\Http\Request;
use App\Models\SellingPeriod as SellingPeriodModel;
use App\Models\Residency;
use App\Models\Branch;
use App\Models\UpSellingHeading as UpSellingHeadingModel;

class UpsellingHeading extends Controller
{
    public function __construct()
    {
        $this->title = 'Upselling Score Heading';
    }

    public function index(Request $request)
    { 
        $search = [];
        $title = $this->title;
        $selling_data = UpSellingHeadingModel::whereNull('parent_id')->orderBy('id','desc');
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
        return view('up_selling_management.tracking_heading',compact('search','title','selling_data','company_list','branch_list'));
    }

    public function store(Request $request){
        $login_user_id = Session::get('user_id');
        if(!empty($request->tracking_id)):
            $save_data = UpSellingHeadingModel::find($request->tracking_id);
            if(!empty($save_data)):
                $save_data->title = $request->item_name;
                $save_data->save();

                $othersave_data = UpSellingHeadingModel::where('parent_id',$request->tracking_id)->first();
                if(!empty($othersave_data)):
                    $othersave_data->title = $request->item_name;
                    $othersave_data->save();
                endif;
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
                                $save_data = new UpSellingHeadingModel();
                                $save_data->company_id = $sval->company_id ?? NULL;
                                $save_data->branch_id = $sval->branch_id ?? NULL;
                                $save_data->sell_p_id = $sval->id;
                                $save_data->title = $request->item_name;
                                $save_data->created_by = $login_user_id;
                                $save_data->save();

                                $other_save_data = new UpSellingHeadingModel();
                                $other_save_data->company_id = $sval->company_id ?? NULL;
                                $other_save_data->branch_id = $sval->branch_id ?? NULL;
                                $other_save_data->sell_p_id = $sval->id;
                                $other_save_data->parent_id = $save_data->id ?? '';
                                $other_save_data->title = $request->item_name;
                                $other_save_data->created_by = $login_user_id;
                                $other_save_data->save();
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
        $data = UpSellingHeadingModel::find($request->id);
        $pass_array=array(
			'data' => $data,
        );
        $html =  view('up_selling_management.tracking_heading_modal', $pass_array )->render();
		$arr = [
			'success' => 'true',
			'html' => $html
		];
		return response()->json($arr);

    }

    public function delete(Request $request){
        $id = $request->selling_id ?? '';
        UpSellingHeadingModel::where('id',$id)->orWhere('parent_id',$id)->delete();
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
            $d = UpSellingHeadingModel::find($id);
            $d->update($data);
        endif;  
        return redirect()->back()->with('success', $message);
    }
        
}
