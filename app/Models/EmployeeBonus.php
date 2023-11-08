<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeBonus extends Model
{
    protected $table = 'employee_bonus';
    public $timestamps = true;
    protected $fillable = [
		'employee_id' ,'bonus_date','bonus_amount','title' 
    ];

    public function employee(){
      return $this->hasOne('App\Models\Employee','id','employee_id');
    }

    protected $dates = ['deleted_at'];
}
