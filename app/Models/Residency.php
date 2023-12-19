<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Residency extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
		'name', 'company_id','status',
	];
}
