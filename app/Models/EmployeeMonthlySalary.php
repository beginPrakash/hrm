<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeMonthlySalary extends Model
{
    use HasFactory;
    protected $table = 'employee_monthly_salary';
    protected $fillable = [
		'residency_id' ,'branch_id','financial_year' ,'emp_id','es_month','es_year','month_w_days','month_holidays','month_fridays','month_salary','day_salary','hourly_salary','off_day','off_days_no','ph_days_no','ph_dates','day_hours','dates_between','excluded_dates','salary_type','salary','deductions','additions','overtime','total_salary','total_work_hours','total_overtime_salary','total_work_overtime','created_at','status' 
    ];

    public function employees(){
		return $this->hasOne(Employee::class, 'user_id', 'emp_id');
	}

	public function employee_designation(){
		return $this->belongsToMany(Designations::class, 'employees', 'user_id', 'designation', 'emp_id');
	} 

	public function employee_residency(){
		return $this->belongsToMany(Residency::class, 'employees', 'user_id', 'company', 'emp_id');
	} 
}

