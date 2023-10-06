<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeExperiences extends Model
{
    use HasFactory;
    protected $table = 'employee_experiences'; 
    protected $fillable = ['emp_id' ,'company','location' ,'job_position','period_from' ,'period_to'];
}
