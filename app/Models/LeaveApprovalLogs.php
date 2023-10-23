<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApprovalLogs extends Model
{
    use HasFactory;
    protected $table = 'leave_approval_logs';
    protected $fillable = [
        'employee_id',
        'leave_id',
        'department_id',
        'designation_id',
	];

    public function designation_detail(){
		return $this->hasOne(Designations::class, 'id', 'designation_id');
	} 

	public function department_detail(){
		return $this->hasOne(Departments::class, 'id', 'department_id');
	}
    
    public function leave_user(){
        return $this->hasOne(Employee::class, 'user_id', 'employee_id');
    }

    public function leaves_details(){
        return $this->hasOne(Leaves::class, 'id', 'leave_id');
    }


}
