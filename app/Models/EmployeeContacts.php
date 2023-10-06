<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeContacts extends Model
{
    use HasFactory;
    protected $table = 'employee_contacts';
    protected $fillable = [
		'emp_id' ,'pri_con_name','pri_con_relation' ,'pri_con_phone' ,'pri_con_phone2','sec_con_name',    
        'sec_con_relation','sec_con_phone','sec_con_phone2'  
     ]; 
      
    public function employee(){
		return $this->hasOne(Employee::class, 'id', 'emp_id');
	}  
    
}
