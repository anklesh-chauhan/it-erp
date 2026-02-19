<?php

namespace App\Models;

use App\Models\BaseModel;

class VisitFeedbackQuestion extends BaseModel
{
    protected $fillable = [
        'question',
        'code',
        'answer_type',
        'is_active',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope a query to only include active questions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
