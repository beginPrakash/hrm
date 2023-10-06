<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAccounts extends Model
{
    use HasFactory;
    protected $table = 'employee_accounts';
    protected $fillable = [
		'emp_id' ,'bank_name','account_number' ,'branch_code' ,'ifsc_number','swift_code','branch_name'
     ]; 
}
