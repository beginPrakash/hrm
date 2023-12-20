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
		'company_id','reg_name', 'reg_no','civil_no','issuing_date','expiry_date','alert_days','remarks','cost'
	];
}
