<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FieldGpsPoint extends BaseModel
{
    protected $fillable = [
        'user_id',
        'employee_id',
        'visit_id',
        'latitude',
        'longitude',
        'recorded_at',
        'accuracy_meters',
        'source',
        'device_id',
        'client_uuid',
        'raw_payload',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'accuracy_meters' => 'float',
            'recorded_at' => 'datetime',
            'raw_payload' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }
}
