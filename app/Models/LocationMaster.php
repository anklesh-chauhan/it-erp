<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class LocationMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location_code',
        'description',
        'is_active',
        'latitude',
        'longitude',
        'image',
        'typeable_id',
        'typeable_type',
        'parent_id', // Added for sublocations
    ];

    public function typeable()
    {
        return $this->morphTo();
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function contactDetail()
    {
        return $this->morphOne(ContactDetail::class, 'contactable');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('typeable');
    }

    public function parentLocation()
    {
        return $this->belongsTo(LocationMaster::class, 'parent_id');
    }

    public function subLocations()
    {
        return $this->hasMany(LocationMaster::class, 'parent_id');
    }

    public function items()
    {
        return $this->belongsToMany(ItemMaster::class, 'item_location')
                    ->withPivot('quantity') // Include quantity in the pivot table
                    ->withTimestamps();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($location_master) {
            $location_master->location_code = NumberSeries::getNextNumber(LocationMaster::class);
        });

        static::created(function ($location_master) {
            NumberSeries::incrementNextNumber(LocationMaster::class);
        });

        static::saving(function ($model) {
            if ($model->typeable_id && !$model->typeable_type) {
                $model->typeable_type = TypeMaster::class;
            }
        });
    }
}
