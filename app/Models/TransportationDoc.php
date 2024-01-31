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
		'transportation_id','car_document','doc_name','expiry_date','alert_days','cost'
	];
}
