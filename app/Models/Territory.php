<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\HasApprovalWorkflow;

class Territory extends BaseModel
{
    use SoftDeletes, HasApprovalWorkflow;
    protected $fillable = [
        'name',
        'code',
        'parent_territory_id',
        'division_ou_id',
        'description',
        'type_master_id',
        'region_id',
        'status',
        'approval_status',
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
     * Get the division associated with the position.
     */
    public function division()
    {
        return $this->belongsTo(
            OrganizationalUnit::class,
            'division_ou_id'
        );
    }

    /**
     * Get the region associated with the Territory.
     */
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
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
    public function divisions(): BelongsToMany
    {
        return $this->belongsToMany(OrganizationalUnit::class, 'territory_division_pivot', 'territory_id', 'division_ou_id')
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
