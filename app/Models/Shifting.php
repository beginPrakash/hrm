<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shifting extends Model
{
    use HasFactory;
    protected $table = 'shifting';
    protected $fillable = ['suid', 'company_id', 'shift_name',
		'min_start_time', 'start_time','max_start_time','min_end_time','end_time','max_end_time','break_time','recurring_shift','repeat_every','week_day','end_on','indefinite','tag','note','is_cod','is_twoday_shift','parent_shift'
	];
}
