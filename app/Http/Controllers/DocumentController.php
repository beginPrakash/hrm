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

        if(isset($request->doc_file))
        {
            foreach($request->doc_file as $dockey => $doc)
            {
                $dataArray = array(
                    'document_id'            =>  $id,
                );
                $employee_docs = EmployeeDocuments::where($dataArray)->first();

                if($doc) 
                {
                    $document = $doc;
                    $filename = $document->getClientOriginalName();
                    $document->move(public_path('uploads/company_documents'), $filename);
                    $dataArray['doc_file'] = $filename;
                }
                if ($employee_docs  ==  null) {
                    EmployeeDocuments::create($dataArray);
                } else {
                    EmployeeDocuments::where('id', $employee_docs->id)->update($dataArray);
                }
            }
            return redirect()->back()->with("success", "Documents saved successfully!");
        }
        
        if(!empty($request->id)):
            Residency::where('id', $request->id)->update($insertArr);
        else:
            $doc_data = CompanyDocuments::create($insertArr);
            if(!empty($doc_file)):
                $data = new CompanyDocFiles();
                $data->document_id = $doc_data->id;
                $data->doc_file = $doc_file;
                $data->save();
            endif;
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
        return view('settings.company_detail',compact('company_detail','title'));

    }
        
}
