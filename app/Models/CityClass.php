<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CityClass extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'multiplier',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'multiplier' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Example: Cities belonging to this class (if you have a cities table)
     */
    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
