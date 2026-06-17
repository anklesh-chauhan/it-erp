<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class VisitDocumentLink extends BaseModel
{
    protected $fillable = [
        'visit_id',
        'documentable_type',
        'documentable_id',
    ];

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }
}
