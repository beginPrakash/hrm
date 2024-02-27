<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreDailySales extends Model
{
    protected $table = 'store_daily_sales';
    public $timestamps = true;
    protected $fillable = [
        'company_id',
        'branch_id',
        'sell_p_id',
        'sales_date',
        'achieve_target',
        'bill_count',
        'target_price',
        'avg_bill_count',
        'headings',
        'action_by'
	];

    protected $dates = ['deleted_at'];

    public function company_detail()
    {
        return $this->belongsTo('App\Models\Residency', 'company_id');
    }
    
    public function branch_detail(){
        return $this->hasOne('App\Models\Branch', 'id', 'branch_id');
    }

    public function sellp_detail(){
        return $this->hasOne('App\Models\SellingPeriod', 'id', 'sell_p_id');
    }

}
