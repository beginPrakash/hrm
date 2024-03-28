<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Response;
use DB;
use Session;
use App\Models\Roles as RolesMaster;
use App\Models\UserRoles as UserRolesModel;
use App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Models\Residency;
use App\Models\Employee;
use App\Models\Branch;

class UserRoles extends Controller
{
    public function index()
    {
        $sql_que = UserRolesModel::whereNull('parent_id')->orderBy('id','desc');
        $company_list = Residency::where('status','active')->pluck('name','id');
        $role_list = RolesMaster::pluck('title','id');
        $emp_list = Employee::where('status','active')->select('id','first_name','last_name','emp_generated_id')->get();
        $uroles_data = $sql_que->get();
        return view('roles_permission.user_roles', compact('uroles_data','company_list','role_list','emp_list'));
    }  

    public function store(Request $request)
    {
        $branch_ids = $request->brnach_list;
        $company_ids = $request->company;
        $is_same_user_role = UserRolesModel::where('role_id',$request->role_id)->where('employee_id',$request->emp_id)->first();
        if(!empty($request->id)):
           $is_same_user_role = UserRolesModel::where('role_id',$request->role_id)->where('id','!=',$request->id)->where('employee_id',$request->emp_id)->first();
        endif;
        if(empty($is_same_user_role)):
            if(!empty($request->id)):
                UserRolesModel::where('id',$request->id)->orWhere('parent_id',$request->id)->delete();
            endif;
            if(!empty($company_ids) && !empty($branch_ids)):
                if(!empty($branch_ids) && count($branch_ids) > 0):
                    foreach($branch_ids as $key => $val):
                        if($key == 0):
                            $save_data = new UserRolesModel();
                            $branch_data = Branch::find($val);
                            $save_data->company_id = $branch_data->residency ?? NULL;
                            $save_data->branch_id = $val;
                            $save_data->role_id = $request->role_id;
                            $save_data->employee_id = $request->emp_id ?? 0;
                            $save_data->save();
                            $id = $save_data->id ?? '';
                        else:
                            $save_data = new UserRolesModel();
                            $branch_data = Branch::find($val);
                            $save_data->company_id = $branch_data->residency ?? NULL;
                            $save_data->branch_id = $val;
                            $save_data->role_id = $request->role_id;
                            $save_data->employee_id = $request->emp_id ?? 0;
                            $save_data->parent_id = $id;
                            $save_data->save();
                        endif;
                    endforeach;
                endif;
                return redirect()->back()->with('success','Data saved successfully');
            else:
                return redirect()->back()->with('error','First select company and branch');
            endif;   
        else:    
            return redirect()->back()->with('error','User role is alredy exist. Please update existing data');
        endif;      
        return redirect('/roles')->with('success','Data saved successfully!');
    }


    public function details(Request $request)
    {
        $rolesData = UserRolesModel::find($request->id);
        $company_list = Residency::where('status','active')->pluck('name','id');
        $role_list = RolesMaster::pluck('title','id');
        $com_data = _get_company_name_by_uroles($request->id);
        $branch_data = Branch::whereIn('residency',$com_data)->orderBy('residency')->get();
        $emp_list = Employee::where('status','active')->select('id','first_name','last_name','emp_generated_id')->get();
        
        $pass_array=array(
            'rolesData' => $rolesData,
            'company_list'=> $company_list,
            'role_list'=> $role_list,
            'emp_list'=> $emp_list,
            'branch_data'=>$branch_data,
        );
        $html =  view('roles_permission.user_roles_modal', $pass_array )->render();
		$arr = [
			'success' => 'true',
			'html' => $html
		];
		return response()->json($arr);

    }

    public function delete(Request $request)
    {
        $rolesData = UserRolesModel::where('id',$request->id)->orWhere('parent_id',$request->id)->delete();
		return redirect('/user_roles')->with('success','Data deleted successfully!');

    }

}
