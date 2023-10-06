<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    use HasFactory;

    protected $fillable = [
		'name', 'company_id','status',
	];

	public function company(){
		return $this->hasOne('App\Models\Company','id','company_id');
	}

	public function designation(){
		return $this->hasMany('App\Models\Designations','id','department');
	}  

}
