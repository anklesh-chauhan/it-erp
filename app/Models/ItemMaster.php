<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class ItemMaster extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'item_code',
        'item_name',
        'description',
        'category_id',
        'category_type', // For polymorphic relationship
        'item_brand_id',
        'purchase_price',
        'selling_price',
        'tax_rate',
        'discount',
        'opening_stock',
        'minimum_stock_level',
        'reorder_quantity',
        'unit_of_measurement_id',
        'lead_time',
        'storage_location',
        'barcode',
        'expiry_date',
        'packaging_type_id',
        'per_packing_qty',
    ];

    /**
     * Polymorphic Category Relation
     */
    public function category()
    {
        return $this->morphTo();
    }

    public function brand()
    {
        return $this->belongsTo(ItemBrand::class, 'item_brand_id');
    }

    public function unitOfMeasurement()
    {
        return $this->belongsTo(UnitOfMeasurement::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(AccountMaster::class, 'item_master_account_masters')
                    ->withTimestamps();
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'model');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'model');
    }

    public function packagingType()
    {
        return $this->belongsTo(PackagingType::class, 'packaging_type_id');
    }

    public function leads()
    {
        return $this->belongsToMany(Lead::class, 'item_master_lead', 'item_master_id', 'lead_id');
    }

    public function locations()
    {
        return $this->belongsToMany(LocationMaster::class, 'item_location')
                    ->withPivot('quantity') // Include quantity in the pivot table
                    ->withTimestamps();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item_master) {
            $item_master->item_code = NumberSeries::getNextNumber(ItemMaster::class);
        });

        static::created(function ($item_master) {
            NumberSeries::incrementNextNumber(ItemMaster::class);
        });

        static::saving(function ($item) {
            if ($item->category_id && !$item->category_type) {
                $item->category_type = \App\Models\Category::class;
            }
        });
    }
}
