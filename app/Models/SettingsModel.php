<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingsModel extends Model
{
    protected $table = 'settings';
    public $timestamps = true;
    protected $fillable = [
        'key',
        'value'
	];

    protected $dates = ['deleted_at'];
}
