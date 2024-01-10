<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scheduling extends Model
{
    use HasFactory;
    protected $table = 'scheduling';
    protected $fillable = ['company_id', 'department', 'employee', 'shift_on', 'shift',
		'min_start_time', 'start_time','max_start_time','min_end_time','end_time','max_end_time','break_time','extra_hours','publish' ,'status','added_by','edited_by'
	];

	public function employees()
    {
        return $this->hasMany(Employee::class, 'designation');
    }

    public function shift_details()
    {
        return $this->hasOne(Shifting::class, 'id','shift')->select('is_cod','shift_name');
    }
}
