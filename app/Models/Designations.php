<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designations extends Model
{
    use HasFactory;
    protected $fillable = [
		'name', 'company_id','status','is_sales'
	];

    public function Department()
    {
	    return $this->hasOne('App\Models\Departments', 'id','department');
	}

	public function employees()
    {
        return $this->hasMany(Employee::class, 'designation');
    }


}
