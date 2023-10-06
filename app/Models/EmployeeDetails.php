<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDetails extends Model
{
    use HasFactory;
    protected $table = 'employee_details';
    protected $fillable = [
		'emp_id', 'address', 'religion', 'state', 'country', 'pin_code', 'c_id', 'expi_c_id', 'b_id', 'expi_b_id', 'marital_status', 'child', 'birthday', 'gender', 'spouse_employment', 'license', 'license_exp', 'blood_group', 'pi_address' 
        
	];
}
