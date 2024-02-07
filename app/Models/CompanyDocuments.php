<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDocuments extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = 'company_documents';
    protected $fillable = [
		'company_id','reg_name', 'reg_no','reg_type','branch_id','civil_no','issuing_date','expiry_date','alert_days','remarks','cost'
	];

  public function company_details(){
		return $this->hasOne('App\Models\Residency','id','company_id');
	}

  public function branch_details(){
		return $this->hasOne(Branch::class, 'id', 'branch_id');
	}

  public function regis_type(){
		return $this->hasOne(RegistrationType::class, 'id', 'reg_type');
	}

}
