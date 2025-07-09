<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'territory_id',
        'city_pin_code_id',
        'description',
        'color',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function territory()
    {
        return $this->belongsTo(Territory::class);
    }

    public function cityPinCode()
    {
        return $this->belongsTo(CityPinCode::class, 'city_pin_code_id');
    }
    
    public function companies()
    {
        return $this->morphedByMany(Company::class, 'patchable', 'patchables', 'patch_id', 'patchable_id')
                    ->using(Patchable::class);
    }

    public function contacts()
    {
        return $this->morphedByMany(ContactDetail::class, 'patchable', 'patchables', 'patch_id', 'patchable_id')
                    ->using(Patchable::class); 
    }
    
    public function getPatchablesAttribute()
    {
        return $this->companies->merge($this->contacts);
    }

    
}