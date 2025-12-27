<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendancePunch extends BaseModel
{
    protected $table = 'attendance_punches';

    protected $fillable = [
        'employee_id',
        'punch_date',
        'punch_time',
        'punch_type',
        'source',
        'device_id',
        'location',
        'raw_payload',
    ];

    protected $casts = [
        'punch_date' => 'date',
        'raw_payload' => 'array',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
