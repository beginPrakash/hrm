<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesTargetMaster extends Model
{
    protected $table = 'sales_target_master';
    public $timestamps = true;
    protected $fillable = [
        'month',
        'company_id',
        'branch_id',
        'sell_p_id',
        'target_price',
        'per_day_price',
        'no_of_monthday',
	];

    protected $dates = ['deleted_at'];


}
