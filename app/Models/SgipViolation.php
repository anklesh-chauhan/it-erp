<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SgipViolation extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'sgip_distribution_id',
        'sgip_limit_id',
        'violation_type',   // quantity | value
        'allowed_value',
        'actual_value',
    ];

    protected $casts = [
        'allowed_value' => 'decimal:2',
        'actual_value'  => 'decimal:2',
    ];

    /* ============================
     | Relationships
     ============================ */

    public function distribution(): BelongsTo
    {
        return $this->belongsTo(SgipDistribution::class);
    }

    public function limit(): BelongsTo
    {
        return $this->belongsTo(SgipLimit::class, 'sgip_limit_id');
    }
}
