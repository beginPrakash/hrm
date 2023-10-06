<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLoan extends Model
{
    use HasFactory;
    protected $table = 'employee_loan';
    protected $fillable = [
		'emp_id' ,'loan_amount','total_paid' ,'loan_date' ,'installment','install_amount','install_pending',    
        'amount_pending','out_kwd','sec_con_phone2'  
     ]; 
      
}
