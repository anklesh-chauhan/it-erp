<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobRole extends BaseModel
{
    protected $fillable = [
        'name',
        'code',
        'level',
        'reports_to_job_role_id',
        'description',
    ];

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    public function reportsTo(): BelongsTo
    {
        return $this->belongsTo(JobRole::class, 'reports_to_job_role_id');
    }
}
