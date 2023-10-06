<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addschedule extends Model
{
    use HasFactory;
    protected $table = 'addschedule';
    // protected $fillable = ['department','shift_name',
	// 	'min_start_time', 'start_time','max_start_time','min_end_time','end_time','max_end_time','break_time','recurring_shift','repeat_every','week_day','end_on','indefinite','tag','note'
	// ];
    protected $guarded = [];

    public function addschedule_shifting(){
		return $this->hasOne(Shifting::class, 'id', 'shift');
	} 
}
