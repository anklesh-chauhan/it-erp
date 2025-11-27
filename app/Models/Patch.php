<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Traits\HasApprovalWorkflow;

class Patch extends Model
{
    use SoftDeletes, HasApprovalWorkflow;

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

    public function allPatchables()
    {
        return $this->companies->merge($this->contacts);
    }

    public function patchables()
    {
        return $this->hasMany(Patchable::class, 'patch_id', 'id');
    }

    public function companies()
    {
        return $this->morphedByMany(AccountMaster::class, 'patchable', 'patchables', 'patch_id', 'patchable_id')
            ->using(Patchable::class)
            ->orderBy('order');
    }

    public function contacts()
    {
        return $this->morphedByMany(ContactDetail::class, 'patchable', 'patchables', 'patch_id', 'patchable_id')
            ->using(Patchable::class)
            ->orderBy('order');
    }

    public function patchablePivots() // <-- NEW RELATIONSHIP
    {
        return $this->morphMany(Patchable::class, 'patchable');
        // Note: This relies on the convention that your Patchable pivot model
        // uses 'patchable_type' and 'patchable_id' but should only be used
        // for managing the pivot table entries directly.
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_by = Auth::user()->name ?? 'System';
            $model->updated_by = Auth::user()->name ?? 'System';
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::user()->name ?? 'System';
        });
    }


}
