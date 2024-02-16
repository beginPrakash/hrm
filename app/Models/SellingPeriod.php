<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellingPeriod extends Model
{
    protected $table = 'selling_period';
    public $timestamps = true;
    protected $fillable = [
        'company_id',
        'branch_id',
        'item_name',
        'is_bill_count',
        'is_show',
	];

    protected $dates = ['deleted_at'];

    public function company_detail()
    {
        return $this->belongsTo('App\Models\Residency', 'company_id');
    }
    
    public function branch_detail(){
        return $this->hasOne('App\Models\Branch', 'id', 'branch_id');
    }

}
