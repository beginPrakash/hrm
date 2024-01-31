<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Session;
use App\Models\CompanyDocuments;
use App\Models\Residency;
use App\Models\CompanyDocFiles;
use App\Models\Branch;
use App\Models\RegistrationType;
use App\Models\TransportationDoc;
use App\Models\TranspoFiles;

class TransDocument extends Controller
{
    public function __construct()
    {
        $this->title = 'Documents';
    }
    
    public function store(Request $request)
    {
    
        $insertArr = array(
            'transportation_id'=>$request->transpo_id ?? 0,
            'doc_name' => $request->doc_name,
            'expiry_date'      =>  $request->expiry_date,
            'alert_days'     =>  $request->alert_days,
            'cost'     =>  $request->cost
        );

        if($request->has('car_document')) 
        {
            $image = $request->file('car_document');
            $filename = $image->getClientOriginalName();
            $image->move(public_path('uploads/transportation'), $filename);
            $insertArr['car_document'] = $filename;
        }

        if(!empty($request->id)):
            $doc_data = TransportationDoc::where('id', $request->id)->update($insertArr);
            $doc_id = $request->id;
        else:
            $doc_data = TransportationDoc::create($insertArr);
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

    
    public function delete_company_document(Request $request)
    {
        CompanyDocFiles::where('id', $request->id)->delete();
        $arr = [
			'success' => 'true',
			'id' => $request->id
		];
		return response()->json($arr);
    }

    public function delete(Request $request)
    {
        TranspoFiles::where('transpo_id', $request->document_id)->delete();
        TransportationDoc::where('id', $request->document_id)->delete();
        return redirect()->back()->with('success','Data deleted successfully!');
    }


    public function gettransdocumentDetailsById(Request $request)
    {
        $reg_html = '';
        $doc_data = TransportationDoc::where('id',$request->id)->first();
        $doc_files = TranspoFiles::where('transpo_id',$request->id)->get();
       
        $pass_array=array(
			'doc_data' => $doc_data,
            'doc_files'=>$doc_files,
        );
        $html =  view('transportation.document_modal', $pass_array )->render();
		$arr = [
			'success' => 'true',
			'html' => $html
		];
		return response()->json($arr);

    }

        
}
