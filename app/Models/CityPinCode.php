<?php

namespace App\Models;

use App\Traits\HasApprovalWorkflow;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CityPinCode extends BaseModel
{
    use HasApprovalWorkflow, HasFactory;

    protected $fillable = [
        'pin_code',
        'area_town',
        'city_id',
        'state_id',
        'country_id',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function territories(): BelongsToMany
    {
        // Pivot table name and foreign keys should be swapped from the Territory model's definition
        return $this->belongsToMany(Territory::class, 'territory_city_pin_code_pivots', 'city_pin_code_id', 'territory_id')
            ->withTimestamps(); // Include withTimestamps if your pivot table uses them
    }

    public function getFullLocationAttribute(): string
    {
        $parts = [
            $this->area_town,
            $this->city?->name,
            $this->pin_code,
        ];

        return implode(', ', array_filter($parts));
    }

    public function scopeSearchLocation($query, string $search)
    {
        return $query->where('area_town', 'like', "%{$search}%")
            ->orWhere('pin_code', 'like', "%{$search}%")
            ->orWhereHas('city', fn ($q) => $q->where('name', 'like', "%{$search}%")
            );
    }
}
