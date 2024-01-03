<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transportation extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = 'transportation';
    protected $fillable = [
		'car_name','colour', 'model','license_no','license_expiry','alert_days','remarks','driver','tag',
        'baladiya_expiry','logo_expiry','under_company','under_subcompany','cost','status'
	];
  
  public function com_detail()
    {
        return $this->hasOne(Residency::class,'id','under_company');
    }
  public function subcom_detail()
    {
        return $this->hasOne(Residency::class,'id','under_subcompany');
    }
}
