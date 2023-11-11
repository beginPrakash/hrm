<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeOvertimeData extends Model
{
    use HasFactory;
    protected $table = 'emp_overtime_report_data';
    public $timestamps = true;
    protected $fillable = [
		'employee_id' ,'branch_id','branch_name' ,'name','position','company_id','company_name','license','total_overtime_hours','hourly_salary','day_salary','basic_salary','overtime_amount','food_allowence','travel_allowence','house_allowence','position_allowence','phone_allowence','other_allowence','deduction','total_earning','es_year','es_month','dates_between','type','report_lock_status' 
    ];
    protected $dates = ['deleted_at'];
}

