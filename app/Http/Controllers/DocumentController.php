<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Session;
use App\Models\CompanyDocuments;
use App\Models\Residency;
use App\Models\CompanyDocFiles;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->title = 'Documents';
    }
    
    public function store(Request $request)
    {
        //dd($request->all());
        $insertArr = array(
            'company_id'=>$request->company_id ?? 0,
            'reg_name' => $request->reg_name,
            'reg_no'      =>  $request->reg_no,
            'civil_no'   =>  $request->civil_no,
            'issuing_date'   =>  $request->issuing_date,
            'expiry_date'      =>  $request->expiry_date,
            'alert_days'     =>  $request->alert_days,
            'remarks'  =>  $request->remarks,
            'cost'     =>  $request->cost,
        );

        
        if(!empty($request->id)):
            $doc_data = CompanyDocuments::where('id', $request->id)->update($insertArr);
            $doc_id = $request->id;
        else:
            $doc_data = CompanyDocuments::create($insertArr);
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
                            $document->move(public_path('uploads/company_documents'), $filename);
                            $docs_file_data = new CompanyDocFiles();
                            $docs_file_data->document_id = $doc_id;
                            $docs_file_data->doc_file = $filename;
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
        CompanyDocFiles::where('document_id', $request->document_id)->delete();
        CompanyDocuments::where('id', $request->document_id)->delete();
        return redirect()->back()->with('success','Data deleted successfully!');
    }


    public function getdocumentDetailsById(Request $request)
    {
        $doc_data = CompanyDocuments::where('id',$request->id)->first();
        $doc_files = CompanyDocFiles::where('document_id',$request->id)->get();
       // dd($doc_files);
        $pass_array=array(
			'doc_data' => $doc_data,
            'doc_files'=>$doc_files,
        );
        $html =  view('settings.document_modal', $pass_array )->render();
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
        return view('settings.company_detail',compact('company_detail','title'));

    }
        
}
