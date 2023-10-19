<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveHierarchy extends Model
{
    use HasFactory;
    protected $table = 'leave_hierarchy';
    protected $fillable = [
        'leave_type',
        'main_dept_id',
        'main_desig_id',
        'leave_hierarchy',
	];

    
    public function leaves_leavetype(){
        return $this->hasOne(Leavetype::class, 'id', 'leave_type');
    }

    public function designation_detail(){
		return $this->hasOne(Designations::class, 'id', 'main_desig_id');
	} 

	public function department_detail(){
		return $this->hasOne(Departments::class, 'id', 'main_dept_id');
	} 


}
