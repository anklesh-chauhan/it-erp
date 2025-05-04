<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemMeasurementUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_master_id',
        'unit_of_measurement_id',
        'conversion_rate',
    ];

    /**
     * Relationship with ItemMaster
     */
    public function itemMaster()
    {
        return $this->belongsTo(ItemMaster::class);
    }

    /**
     * Relationship with UnitOfMeasurement
     */
    public function unitOfMeasurement()
    {
        return $this->belongsTo(UnitOfMeasurement::class);
    }
}
