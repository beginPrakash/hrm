<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDocFiles extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = 'company_doc_files';
    protected $fillable = [
		'document_id','doc_file'
	];
}
