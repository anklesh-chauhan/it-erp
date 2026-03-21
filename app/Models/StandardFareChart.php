<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasApprovalWorkflow;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StandardFareChart extends BaseModel
{
    use HasFactory, SoftDeletes, HasApprovalWorkflow;

    protected $fillable = [
        'from_area_town_id',
        'to_area_town_id',
        'transport_mode_id',
        'distance_km',
        'fare_amount',
        'territory_id',
        'is_active',
        'type_master_id',
    ];

    public function fromAreaTown(): BelongsTo
    {
        return $this->belongsTo(CityPinCode::class, 'from_area_town_id');
    }

    public function toAreaTown(): BelongsTo
    {
        return $this->belongsTo(CityPinCode::class, 'to_area_town_id');
    }

    public function transportMode(): BelongsTo
    {
        return $this->belongsTo(TransportMode::class, 'transport_mode_id');
    }

    public function territory(): BelongsTo
    {
        return $this->belongsTo(Territory::class, 'territory_id');
    }

    public function typeMaster()
    {
        return $this->belongsTo(TypeMaster::class, 'type_master_id');
    }
}
