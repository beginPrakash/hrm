<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeEducation extends Model
{
    use HasFactory;
    protected $table = 'employee_education';
    protected $fillable = [
		'emp_id' ,'institution','subject' ,'start' ,'end','degree','grade'];
}
