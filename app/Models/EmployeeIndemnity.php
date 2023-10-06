<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeIndemnity extends Model
{
    use HasFactory;
    protected $table = 'employee_indemnity';
    protected $fillable = [
		 'user_id', 'joined_on', 'years_diff', 'indemnity_perc', 'total_months', 'perday_salary', 'total_amount', 'created_at', 'status'
        
	];
}
