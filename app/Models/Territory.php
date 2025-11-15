<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Territory extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'code',
        'parent_territory_id',
        'description',
        'type_master_id',
        'status',
    ];

    protected $casts = [
        'status' => 'string', // Ensures enum is cast as string
    ];

    /**
     * Get the parent territory.
     */
    public function parentTerritory(): BelongsTo
    {
        return $this->belongsTo(Territory::class, 'parent_territory_id');
    }

    /**
     * Get the child territories.
     */
    public function childTerritories(): HasMany
    {
        return $this->hasMany(Territory::class, 'parent_territory_id');
    }

    public function patches()
    {
        return $this->hasMany(\App\Models\Patch::class, 'territory_id');
    }

    /**
     * Get the organizational units associated with the territory.
     */
    public function organizationalUnits(): BelongsToMany
    {
        return $this->belongsToMany(OrganizationalUnit::class, 'territory_organizational_unit_pivot', 'territory_id', 'organizational_unit_id')
                    ->withTimestamps();
    }

    /**
     * Get the city pin codes associated with the territory.
     */
    public function cityPinCodes(): BelongsToMany
    {
        return $this->belongsToMany(CityPinCode::class, 'territory_city_pin_code_pivots', 'territory_id', 'city_pin_code_id')
                    ->with('city')
                    ->withTimestamps();
    }

    /**
     * Get the type master associated with the territory.
     */
    public function typeMaster(): BelongsTo
    {
        return $this->belongsTo(TypeMaster::class, 'type_master_id');
    }

    public function positions()
    {
        return $this->belongsToMany(Position::class, 'position_territory_pivot', 'territory_id', 'position_id')
            ->withTimestamps();
    }
}
