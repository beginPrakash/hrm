<?php

namespace App\Http\Controllers;
use App\Models\Indemnity;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Session;

class IndemnityController extends Controller
{
    public function __construct()
    {
        $this->title = 'Indemnity';
    }

    public function index()
    {
        $title = $this->title;
        $indemnitySettings    = Indemnity::get();
        return view('policies.indemnity', compact('title', 'indemnitySettings'));
    }



    public function update(Request $request)
    { 
        if(isset($request->indemnity_amount))
        {
            foreach($request->indemnity_amount as $key => $val)
            {
                $updateArray = array(
                    // 'min_year'          => $request->min_year[$key],
                    // 'max_year'          => $request->max_year[$key],
                    'indemnity_amount'  => $val,
                    'percentage_ia'     => $request->percentage_ia[$key],
                    'updated_at'        => date('Y-m-d h:i:s')
                );
                Indemnity::where('id', $key)->update($updateArray);
            }
        }
        return redirect('/indemnity')->with('success','Indemnity Updated successfully!');

    }
}
