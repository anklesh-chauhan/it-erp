<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyMaster extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'contact_details_id',
        'region_id',
        'company_master_type_id',
        'vendor_code',
        'company_code',
        'address_id',
        'dealer_name_id',
        'commission',
        'category_id',
        'category_type',
        'typeable_id',
        'typeable_type',
    ];

    /**
     * Multiple Contact Details Relationship (Many-to-Many).
     */
    public function contactDetails()
    {
        return $this->belongsToMany(ContactDetail::class, 'company_master_contact_details');
    }

    /**
     * Polymorphic Relation for Category (Specific to `CompanyMaster` only).
     */
    public function category()
    {
        return $this->morphTo();
    }

    public function typeable()
    {
        return $this->morphTo();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function companyMasterType()
    {
        return $this->belongsTo(CompanyMasterType::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function dealerName()
    {
        return $this->belongsTo(ContactDetail::class);
    }

    public function numberSeries()
    {
        return $this->morphOne(NumberSeries::class, 'modelable');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($company_master) {
            $company_master->company_code = NumberSeries::getNextNumber(CompanyMaster::class);
        });

        static::created(function ($company_master) {
            NumberSeries::incrementNextNumber(CompanyMaster::class);
        });
    }

}
