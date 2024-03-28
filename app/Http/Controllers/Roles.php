<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Response;
use DB;
use Session;
use App\Models\Roles as RolesMaster;
use App\Http\Controllers\Auth;
use Illuminate\Http\Request;

class Roles extends Controller
{
    public function index()
    {
        $sql_que = RolesMaster::orderBy('id','desc');
        $roles_data = $sql_que->get();
        return view('roles_permission.roles', compact('roles_data'));
    }  

    public function store(Request $request)
    {
        //check roles exist
        if(!empty($request->id)):
            $roles_exist = RolesMaster::where('id',$request->id)->first();
        else:
            $roles_exist = new RolesMaster();
        endif;
        
        $insertArray = array(
            'title'        =>  $request->title,
        );
        if(!empty($request->id)):
            $bonus_data = RolesMaster::where('id', $request->id)->update($insertArray);
        else:
            $bonus_data = RolesMaster::create($insertArray);
        endif;                    
        return redirect('/roles')->with('success','Data saved successfully!');
    }


    public function details(Request $request)
    {
        $rolesData = RolesMaster::find($request->id);
        $pass_array=array(
            'rolesData' => $rolesData
        );
        $html =  view('roles_permission.roles_modal', $pass_array )->render();
		$arr = [
			'success' => 'true',
			'html' => $html
		];
		return response()->json($arr);

    }

    public function delete(Request $request)
    {
        $rolesData = RolesMaster::where('id',$request->roles_id)->delete();
		return redirect('/roles')->with('success','Data deleted successfully!');

    }

}
