<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
	protected $table = 'companies';
	protected $fillable = [
		'company_name', 'company_type','trading_name', 'registration_no','contact_no','email','website','tax_no','location_id','company_logo',
	];

	public function department(){
		return $this->hasOne('App\Models\Departments','id','department');
	}  

	public function branch(){
		return $this->hasOne('App\Models\Branch','id','company_id');
	}  
}
