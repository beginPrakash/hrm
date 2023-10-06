<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subresidency extends Model
{
    use HasFactory;
    protected $table = 'subresidency';
    protected $fillable = [
        'name', 'company_id', 'residency', 'status',
    ];

    public function Residency()
    {
	    return $this->hasOne('App\Models\Residency', 'id','residency');
	}  

    public function company()
    {
	    return $this->hasOne('App\Models\Company', 'id','company_id');
	}   
}
