<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryHistory extends Model
{
    use HasFactory;
    protected $table = 'employee_salary_history';
    protected $fillable = [
		'ems_id' ,'user_id','entry_type','entry_value', 'remarks', 'status'
     ]; 
}
