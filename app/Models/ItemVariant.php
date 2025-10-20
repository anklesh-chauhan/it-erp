<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'item_master_id',
        'variant_name', // e.g., "Red / XL"
        'sku',          // unique variant code
        'barcode',
        'purchase_price',
        'selling_price',
        'tax_rate',
        'discount',
        'stock',
        'expiry_date',
        'unit_of_measurement_id',
        'packaging_type_id',
        'per_packing_qty',
    ];

    public function customerPrices()
    {
        return $this->hasMany(CustomerPrice::class);
    }

    public function itemMaster()
    {
        return $this->belongsTo(ItemMaster::class);
    }

    public function unitOfMeasurement()
    {
        return $this->belongsTo(UnitOfMeasurement::class);
    }

    public function packagingType()
    {
        return $this->belongsTo(PackagingType::class, 'packaging_type_id');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'model');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'model');
    }

    public function taxes()
    {
        return $this->belongsToMany(Tax::class, 'item_variant_tax_pivots')
            ->withTimestamps();
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($variant) {
            $variant->itemMaster->update(['has_variants' => true]);
        });

        static::deleted(function ($variant) {
            $itemMaster = $variant->itemMaster;
            $hasVariants = $itemMaster->variants()->count() > 0;
            $itemMaster->update(['has_variants' => $hasVariants]);
        });
    }

}
