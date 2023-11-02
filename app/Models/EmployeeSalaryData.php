<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryData extends Model
{
    use HasFactory;
    protected $table = 'emp_salary_report_data';
    public $timestamps = true;
    protected $fillable = [
		'employee_id' ,'branch_id','branch_name' ,'name','position','company_id','company_name','license','total_schedule_hours','total_fs_hours','hourly_salary','day_salary','basic_salary','salary','food_allowence','travel_allowence','house_allowence','position_allowence','phone_allowence','other_allowence','deduction','total_earning','es_year','es_month','dates_between','type','report_lock_status' 
    ];
    protected $dates = ['deleted_at'];
}

