<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TranspoFiles extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = 'transportation_files';
    protected $fillable = [
		'transpo_id','transpo_file'
	];
}
