<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceDetails extends Model
{
    use HasFactory;

 	protected $table = 'attendance_details';
    protected $fillable = [
		'attendance_id', 'user_id', 'employee_id', 'department', 'attendance_on', 'attendance_time', 'day_type', 'punch_state', 'work_code', 'data_source', 'status','atte_ref_id'
	];
    
}
