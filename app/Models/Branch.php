<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $table = 'branch';
    protected $fillable = [
        'name', 'company_id', 'residency', 'status',
    ];

    public function Residency()
    {
	    return $this->hasOne('App\Models\Residency', 'id','residency');
	}  

    public function company()
    {
	    return $this->hasOne('App\Models\Company', 'id','company_id');
	}  

    public function employee_list(){
		return $this->hasMany(Employee::class, 'branch', 'id')->where('employees.status','active')->with('employee_designation','employee_company_details','employee_details','employee_salary');
	}  
}
