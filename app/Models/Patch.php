<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Traits\HasApprovalWorkflow;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Patch extends BaseModel
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

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(AccountMaster::class)
            ->withTimestamps();
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
