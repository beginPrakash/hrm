<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpSellingHeading extends Model
{
    protected $table = 'upselling_heading_master';
    public $timestamps = true;
    protected $fillable = [
        'company_id',
        'branch_id',
        'sell_p_id',
        'title',
        'parent_id',
        'created_by',
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

    public function sellp_detail(){
        return $this->hasOne('App\Models\SellingPeriod', 'id', 'sell_p_id');
    }

}
