<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalary extends Model
{
    use HasFactory;
    protected $table = 'employee_salary';
    protected $fillable = [
		'emp_id' ,'financal_year','basic_salary', 'travel_allowance', 'food_allowance', 'house_allowance', 'position_allowance', 'phone_allowance', 'other_allowance', 'total_salary', 'status'
     ]; 
}
