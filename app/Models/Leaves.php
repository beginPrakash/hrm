<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\LeaveStatus;

class Leaves extends Model
{
    use HasFactory;
    protected $table = 'employee_leaves';
    protected $fillable = [
        'user_id',
        'leave_type',
        'leave_from',
        'leave_to',
        'leave_days',
        'remaining_leave',
        'leave_reason',
        'leave_status',
        'leave_hierarchy',
        'claimed_annual_days',
        'claimed_public_days',
        'claimed_annual_days_rem',
        'claimed_public_days_rem',
        'opening_leave_balance',
        'claimed_amount',
        'closing_leave_balance',
        'is_post_transaction',
        'basic_salary',
	];

    public function status()
    {
        return $this->belongsTo(LeaveStatus::class, 'status_id');
    }
    
    public function leaves_leavetype(){
        return $this->hasOne(Leavetype::class, 'id', 'leave_type');
    }

    public function leave_user(){
        return $this->hasOne(Employee::class, 'user_id', 'user_id');
    }

    // public function analyst()
    // {
    //     return $this->belongsTo(User::class, 'analyst_id');
    // }

    // public function cfo()
    // {
    //     return $this->belongsTo(User::class, 'cfo_id');
    // }

    // public function employee_leaves()
    // {
    //     return $this->hasMany(Leaves::class, 'leave_type');
    // }

}
