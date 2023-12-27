<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Session;
use App\Models\CompanyDocuments;
use App\Models\Residency;
use App\Models\CompanyDocFiles;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->title = 'Company Settings';
    }

    public function companySettings(Request $request)
    { 
        $query = Residency::where('status','active');
        $search = [];
        if(isset($request->company_name))
        {
            $search['company_name'] = $request->company_name;
            $query->where('name','like',"%$request->company_name%");
        }
        $result = $query->get(); 
        $residency_list = $result;
        return view('settings.residencysettings', compact('residency_list','search'));
    }

    public function companySettingsEdit(Request $request)
    {
        $title = $this->title.' Update';
        $residency = Residency::where('id',$request->id)->first();
        return view('settings.residencysettingsUpdate', compact('title', 'residency'));
    }

    public function companySettingsUpdate(Request $request)
    {
        $updateArray = array(
            'name'      => $request->name,
            'address'   =>  $request->address,
            'country'   =>  $request->country,
            'city'      =>  $request->city,
            'state'     =>  $request->state,
            'postal_code'  =>  $request->postal_code,
            'email'     =>  $request->email,
            'phone_number'  =>  $request->phone_number,
            'fax'       =>  $request->fax,
            'website'   =>  $request->website,
            'created_at'    =>  date('Y-m-d h:i:s')
        );

        if($request->has('image1')) 
        {
            $image = $request->file('image1');
            $filename = $image->getClientOriginalName();
            $image->move(public_path('uploads/logo'), $filename);
            $updateArray['logo'] = $filename;
        } 

        Residency::where('id', $request->id)->update($updateArray);
        return redirect('/company-settings')->with('success','Settings updated successfully!');
    }
    
    public function store(Request $request)
    {
        $company_id  = Session::get('company_id');
        $insertArr = array(
            'company_id' => $company_id,
            'name'      =>  $request->name,
            'address'   =>  $request->address,
            'country'   =>  $request->country,
            'city'      =>  $request->city,
            'state'     =>  $request->state,
            'postal_code'  =>  $request->postal_code,
            'email'     =>  $request->email,
            'phone_number'  =>  $request->phone_number,
            'fax'       =>  $request->fax,
            'website'   =>  $request->website,
            'created_at'    =>  date('Y-m-d h:i:s')
        );

        if($request->has('image1')) 
        {
            $image = $request->file('image1');
            $filename = $image->getClientOriginalName();
            $image->move(public_path('uploads/logo'), $filename);
            $insertArr['logo'] = $filename;
        } 
        if(!empty($request->id)):
            Residency::where('id', $request->id)->update($insertArr);
        else:
            Residency::insert($insertArr);
        endif;
        return redirect()->back()->with('success','Data saved successfully!');
        
    } 

    
    public function delete(Request $request)
    {
        Residency::where('id', $request->residency_id)->delete();
        return redirect('/company-settings')->with('success','Data deleted successfully!');

    }

    public function isSubresidencyExists(Request $request)
    { 
        $status = 'active';
        $where = ['status'=>$status];
        if(isset($request['id']))
        {
            $where['name'] = $request['subresidency_name'];
            $count = Subresidency::where($where)->where('id', '!=', $request['id'])->where('residency', $request['residency'])->get();
        }
        else
        {
            $where['name'] = $request['subresidency'];
            $count = Subresidency::where($where)->where('residency', $request['residency'])->get();
        }
        
        if(count($count) > 0)
        {
            echo "false";
        }
        else
        {
            echo "true";
        }
    }

    public function getcompanyDetailsById(Request $request)
    {
        $residency = Residency::where('id',$request->id)->first();
        $pass_array=array(
			'residency' => $residency,
        );
        $html =  view('settings.company_modal', $pass_array )->render();
		$arr = [
			'success' => 'true',
			'html' => $html
		];
		return response()->json($arr);

    }

    public function detail(Request $request,$id)
    {
        $title='Company Detail';
        $company_detail = Residency::where('id',$id)->first();
        $documents = CompanyDocuments::where('company_id',$id)->get();
        $documents_file = CompanyDocuments::where('company_id',$id)->get();
        return view('settings.company_detail',compact('company_detail','title','documents'));

    }
    
        
}
