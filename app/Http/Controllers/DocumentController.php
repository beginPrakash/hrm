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

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->title = 'Documents';
    }
    
    public function store(Request $request)
    {
        $reg_ids = [];
        $reg_types = explode(', ',$request->reg_type);
        if(!empty($reg_types) && count($reg_types) > 0):
            foreach($reg_types as $key => $val):
                $check_reg_exists = RegistrationType::where('name',$val)->first();
                if(!empty($check_reg_exists)):
                    $reg_ids[] = $check_reg_exists->id;
                else:
                    $save_data = new RegistrationType();
                    $save_data->name = $val;
                    $save_data->save();
                    $reg_ids[] = $save_data->id ?? '';
                endif;
            endforeach;
        endif;

        $doc_data_reg = CompanyDocuments::where('id', $request->id)->first();
        if(!empty($request->reg_type)):
            $reg_im_data = implode(",",$reg_ids);
        else:
            $reg_im_data = $doc_data_reg->reg_type ?? '';
        endif;
        
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
            'reg_type' => $reg_im_data,
            'branch_id' => $request->branch_id
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
        $reg_html = '';
        $doc_data = CompanyDocuments::where('id',$request->id)->first();
        $doc_files = CompanyDocFiles::where('document_id',$request->id)->get();
        $branches    = Branch::where('status','active')->get();
        $get_reg_types = RegistrationType::whereIn('id',explode(',',$doc_data->reg_type ?? ''))->get();
        if(!empty($get_reg_types) && count($get_reg_types) > 0):
            foreach($get_reg_types as $key => $val):
                $reg_html.= '<div class="token" data-value="'.$val->name.'"><span class="token-label">'.$val->name.'</span><a href="#" data-docid="'.$request->id.'" data-reg_id="'.$val->id.'" class="close close_reg_data" tabindex="-1">×</a></div>';
            endforeach;
        endif;
       // dd($doc_files);
       
        $pass_array=array(
			'doc_data' => $doc_data,
            'doc_files'=>$doc_files,
            'branches'=>$branches,
            'reg_html'=>$reg_html,
        );
        $html =  view('settings.document_modal', $pass_array )->render();
		$arr = [
			'success' => 'true',
			'html' => $html
		];
		return response()->json($arr);

    }

    public function deleteregtypebydocument(Request $request){
        $doc_data = CompanyDocuments::where('id',$request->doc_id)->first();
        if(!empty($doc_data)):
            $reg_data = explode(',',$doc_data->reg_type);
            if(!empty($reg_data) && count($reg_data) > 0):
                foreach($reg_data as $key => $val):
                    if($val == $request->reg_id):
                        unset($reg_data[$key]);
                    endif;
                endforeach;
            endif;
            $doc_data->reg_type = implode(',',$reg_data);
            $doc_data->save();
            $arr = [
                'success' => 'true',
            ];
            return response()->json($arr);
        endif;
    }

    public function detail(Request $request,$id)
    {
        $title='Company Detail';
        $company_detail = Residency::where('id',$id)->first();
        $branches    = Branch::where('status','active')->get();
        return view('settings.company_detail',compact('company_detail','title','branches'));

    }
        
}
