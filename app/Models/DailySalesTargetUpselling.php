<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySalesTargetUpselling extends Model
{
    protected $table = 'daily_sales_target_upselling';
    public $timestamps = true;
    protected $fillable = [
        'company_id',
        'branch_id',
        'sell_p_id',
        'user_id',
        'sales_date',
        'target_price',
        'sale_price',
        'bill_count',
        'cc_count',
        'target_heading_price',
        'achieve_heading_price',
        'total_cal',
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
