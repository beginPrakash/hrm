<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
		'user_id', 'company_id', 'first_name' , 'last_name' , 'email', 'password' ,'conf_password' ,'emp_generated_id' ,'joining_date', 'passport_no' ,'passport_expiry' , 'local_address', 'phone' ,'visa_no'  ,'company' , 'branch', 'subcompany', 'department','designation' , 'username', 'opening_leave_days', 'opening_leave_amount', 'public_holidays_balance', 'public_holidays_amount'
	];

	public function getFullNameAttribute(){
        return ucfirst($this->first_name).' '.ucfirst($this->last_name);
    }
	
	public function employee(){
		return $this->hasOne('App\Models\Employee','id','employee_id');
	}  
	  
	 
	 	
	public function employee_company(){
		return $this->hasOne(Residency::class, 'id', 'company');
	} 

	public function employee_branch(){
		return $this->hasOne(Branch::class, 'id', 'branch');
	} 
	
	public function employee_designation(){
		return $this->hasOne(Designations::class, 'id', 'designation');
	} 

	public function employee_department(){
		return $this->hasOne(Departments::class, 'id', 'department');
	} 

	public function employee_accounts(){
		return $this->hasOne(EmployeeAccounts::class, 'emp_id', 'id');
	} 
	
	public function employee_contacts(){
		return $this->hasOne(EmployeeContacts::class, 'emp_id', 'id');
	}  
	
	public function employee_details(){
		return $this->hasOne(EmployeeDetails::class, 'emp_id', 'id');
	}  
	
	public function employee_education(){
		return $this->hasMany(EmployeeEducation::class, 'emp_id', 'id')->where('employee_education.status','active');
	}  
	
	public function employee_experiences(){
		return $this->hasMany(EmployeeExperiences::class, 'emp_id', 'id')->where('employee_experiences.status','active');
	}  
	 
	public function employee_loan(){
		return $this->hasOne(EmployeeLoan::class, 'emp_id', 'id');
	}   
	 
	public function employee_salary(){
		return $this->hasOne(EmployeeSalary::class, 'emp_id', 'id');
	}      
	 
	public function employee_document(){
		return $this->hasMany(EmployeeDocuments::class, 'emp_id', 'id')->where('employee_documents.status','active');
	}   

	public function employee_salary_details(){
		return $this->hasOne(EmployeeMonthlySalary::class, 'emp_id', 'user_id')->where('es_month', 6)->where('es_year', 2023);
	} 

	public function employee_residency(){
		return $this->hasOne(Residency::class, 'id', 'company');
	} 

	public function user()
	{
		return $this->belongsTo(User::class,'user_id','id');
	}

	public function employee_leaves(){
		return $this->hasMany(Leaves::class, 'user_id', 'user_id');
	} 

	public function schedules()
    {
        return $this->hasMany(Scheduling::class, 'employee', 'user_id');
    }
	public function employee_company_details(){
		return $this->hasOne(Company::class, 'id', 'company_id');
	}
}
