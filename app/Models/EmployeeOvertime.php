<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeOvertime extends Model
{
    protected $table = 'employee_overtime';
    public $timestamps = true;
    protected $fillable = [
		'employee_id' ,'ot_date','ot_hours','description' 
    ];

    public function employee(){
      return $this->hasOne('App\Models\Employee','id','employee_id');
    }

    protected $dates = ['deleted_at'];
}
