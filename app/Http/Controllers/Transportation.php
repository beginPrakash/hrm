<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Session;
use App\Models\CompanyDocuments;
use App\Models\Residency;
use App\Models\TranspoFiles;
use App\Models\Transportation as TransportationModel;

class Transportation extends Controller
{
    public function __construct()
    {
        $this->title = 'Transportation';
    }

    public function transportation_list(Request $request)
    { 
        $company_list = Residency::where('status','active')->get();
        $query = TransportationModel::where('status','active');
        $search = [];
        if(isset($request->car_name))
        {
            $search['car_name'] = $request->car_name;
            $query->where('car_name','like',"%$request->car_name%");
        }
        $result = $query->get(); 
        $transp_list = $result;
        return view('transportation.index', compact('transp_list','search','company_list'));
    }

    public function store(Request $request)
    {
       // dd($request->all());
        $company_id  = Session::get('company_id');
        $insertArr = array(
            'car_name' => $request->car_name,
            'colour'      =>  $request->colour,
            'model'   =>  $request->model,
            'license_no'   =>  $request->license_no,
            'license_expiry' =>  date('Y-m-d',strtotime($request->license_expiry)),
            'alert_days'     =>  $request->alert_days,
            'remarks'  =>  $request->remarks,
            'driver'     =>  $request->driver,
            'tag'  =>  $request->tag,
            'baladiya_expiry' =>  (isset($request->baladiya_expiry) && !empty($request->baladiya_expiry)) ? date('Y-m-d',strtotime($request->baladiya_expiry)) : NULL,
            'logo_expiry' =>  (isset($request->logo_expiry) && !empty($request->logo_expiry)) ? date('Y-m-d',strtotime($request->logo_expiry)) : NULL,
            'under_company'       =>  $request->under_company,
            'under_subcompany'   =>  $request->under_subcompany,
            'cost'    => $request->cost,
            'status' => 'active'
        );
 
        if(!empty($request->id)):
            $doc_data = TransportationModel::where('id', $request->id)->update($insertArr);
            $doc_id = $request->id;
        else:
            $doc_data = TransportationModel::create($insertArr);
            $doc_id = $doc_data->id;
        endif;

        if(!empty($request->doc_file)):
            if(isset($request->doc_file))
            {
                foreach($request->doc_file as $dockey => $doc)
                {
                    if($doc) 
                    {
                        $document = $doc;
                        $filename = $document->getClientOriginalName();
                        $document->move(public_path('uploads/transportation'), $filename);
                        $docs_file_data = new TranspoFiles();
                        $docs_file_data->transpo_id = $doc_id;
                        $docs_file_data->transpo_file = $filename;
                        $docs_file_data->save();
                    }
                }
            }
        endif;
        return redirect()->back()->with('success','Data saved successfully!');
        
    } 

    
    public function delete(Request $request)
    {
        TranspoFiles::where('transpo_id', $request->transp_id)->delete();
        TransportationModel::where('id', $request->transp_id)->delete();
        return redirect()->back()->with('success','Data deleted successfully!');

    }

    public function gettranspoDetailsById(Request $request)
    {
        $trans_data = TransportationModel::where('id',$request->id)->first();
        $trans_files = TranspoFiles::where('transpo_id',$request->id)->get();
        $company_list = Residency::where('status','active')->get();
        $pass_array=array(
			'trans_data' => $trans_data,
            'trans_files'=>$trans_files,
            'company_list' => $company_list
        );
        $html =  view('transportation.transportation_modal', $pass_array )->render();
		$arr = [
			'success' => 'true',
			'html' => $html
		];
		return response()->json($arr);

    }

    public function delete_transp_document(Request $request)
    {
        TranspoFiles::where('id', $request->id)->delete();
        $arr = [
			'success' => 'true',
			'id' => $request->id
		];
		return response()->json($arr);
    }

    public function detail(Request $request,$id)
    {
        $title='Transportation Detail';
        $trans_detail = TransportationModel::where('id',$id)->first();
        if(!empty($trans_detail)):
            return view('transportation.detail',compact('trans_detail','title'));
        else:
            return redirect(route('transportation_list'));
        endif;

    }
    
        
}
