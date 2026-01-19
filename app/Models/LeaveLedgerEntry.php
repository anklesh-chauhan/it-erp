<?php

namespace App\Models;

use App\Models\BaseModel;

class LeaveLedgerEntry extends BaseModel
{

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'entry_type',
        'quantity',
        'balance_before',
        'balance_after',
        'effective_date',
        'remarks',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function leaveInstances()
    {
        return $this->hasMany(LeaveInstance::class, 'employee_id', 'employee_id');
    }

    public function leaveAdjustments()
    {
        return $this->hasMany(LeaveAdjustment::class, 'employee_id', 'employee_id');
    }

    public function leaveEncashments()
    {
        return $this->hasMany(LeaveEncashment::class, 'employee_id', 'employee_id');
    }

    public function leaveLapseRecords()
    {
        return $this->hasMany(LeaveLapseRecord::class, 'employee_id', 'employee_id');
    }
}
