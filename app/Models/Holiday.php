<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Holiday extends BaseModel
{
    protected $fillable = [
        'date',
        'name',
        'country_id',
        'state_id',
        'location_id',
        'is_optional',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'is_optional' => 'boolean',
        'is_active' => 'boolean',
    ];

    /* -----------------------------------------------------------------
     | Relationships
     |-----------------------------------------------------------------*/

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(LocationMaster::class, 'location_master_id');
    }

    /* -----------------------------------------------------------------
     | Scopes
     |-----------------------------------------------------------------*/

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOptional($query)
    {
        return $query->where('is_optional', true);
    }

    /**
     * Scope holidays applicable for a given context
     * (global → country → state → location)
     */
    public function scopeApplicableFor($query, array $context)
    {
        return $query
            ->where(function ($q) use ($context) {
                $q->whereNull('country_id')
                  ->orWhere('country_id', $context['country_id'] ?? null);
            })
            ->where(function ($q) use ($context) {
                $q->whereNull('state_id')
                  ->orWhere('state_id', $context['state_id'] ?? null);
            })
            ->where(function ($q) use ($context) {
                $q->whereNull('location_master_id')
                  ->orWhere('location_master_id', $context['location_master_id'] ?? null);
            });
    }

}
