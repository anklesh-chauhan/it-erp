<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Territory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'postal_code',
        'name',
        'code',
        'city',
        'state',
        'country',
        'parent_territory_id',
        'description',
        'type_master_id',
        'reporting_position_id', // Added after the third migration
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => \App\Enums\TerritoryStatus::class, // Assuming you might have an enum for status
    ];

    /**
     * Get the parent territory that owns the Territory.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Territory::class, 'parent_territory_id');
    }

    /**
     * Get the child territories for the Territory.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Territory::class, 'parent_territory_id');
    }

    /**
     * Get the type master associated with the Territory.
     */

    public function typeMaster()
    {
        return $this->belongsTo(TypeMaster::class, 'type_master_id');
    }

    /**
     * Get the reporting position associated with the Territory.
     * This relationship is added based on the third migration.
     */
    public function reportingPosition(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'reporting_position_id');
    }

    /**
     * The organizational units that belong to the territory.
     */
    public function organizationalUnits(): BelongsToMany
    {
        return $this->belongsToMany(OrganizationalUnit::class, 'territory_organizational_unit_pivot', 'territory_id', 'organizational_unit_id')
                    ->withTimestamps(); // If your pivot table has timestamps
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class, 'territory_id'); // ADDED: Relationship to Position
    }
}
