<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportationDoc extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = 'transportation_documents';
    protected $fillable = [
		'transportation_id','company','doc_number','doc_name','reg_type','expiry_date','alert_days','cost'
	];

  public function trans_detail()
    {
        return $this->hasOne(Transportation::class,'id','transportation_id');
    }

    public function regis_type(){
      return $this->hasOne(RegistrationType::class, 'id', 'reg_type');
    }
}
