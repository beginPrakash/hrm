<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDocuments extends Model
{
    use HasFactory;
    protected $fillable = ['emp_id' ,'document_title','document_file']; 
}
