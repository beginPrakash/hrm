<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDeduction extends Model
{
    protected $table = 'employee_deduction';
    public $timestamps = true;
    protected $fillable = [
		'employee_id' ,'deduction_date','deduction_amount','title' 
    ];

    public function employee(){
      return $this->hasOne('App\Models\Employee','id','employee_id');
    }

    protected $dates = ['deleted_at'];
}
