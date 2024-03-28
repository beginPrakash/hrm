<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    protected $table = 'user_roles';
    public $timestamps = true;
    protected $fillable = [
		'employee_id',
        'role_id',
        'company_id',
        'branch_id',
        'parent_id'
    ];

    public function employee(){
    return $this->hasOne('App\Models\Employee','id','employee_id');
    }

    public function roles_detail(){
    return $this->hasOne('App\Models\Roles','id','role_id');
    }

    protected $dates = ['deleted_at'];
}
