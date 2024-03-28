<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = 'roles_master';
    public $timestamps = true;
    protected $fillable = [
		'title' 
    ];

    protected $dates = ['deleted_at'];
}
