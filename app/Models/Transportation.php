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
		'car_name','colour', 'model','license_no','remarks','driver','tag',
        'under_company','under_subcompany','logo','status'
	];
  
  public function com_detail()
    {
        return $this->hasOne(Residency::class,'id','under_company');
    }
  public function subcom_detail()
    {
        return $this->hasOne(Residency::class,'id','under_subcompany');
    }
  public function doc_detail()
    {
        return $this->hasMany(TransportationDoc::class,'transportation_id','id');
    }
}
