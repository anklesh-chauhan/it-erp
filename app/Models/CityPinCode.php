<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Traits\HasApprovalWorkflow;

class CityPinCode extends BaseModel
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = ['pin_code', 'area_town', 'city_id', 'state_id', 'country_id'];

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
}
