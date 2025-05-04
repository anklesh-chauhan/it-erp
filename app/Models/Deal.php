<?php

namespace App\Models;

use App\Traits\HasCustomerInteractionFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Deal extends Model
{
    use HasFactory, HasCustomerInteractionFields;

    protected $fillable = [
        'owner_id', 'reference_code','deal_name', 'transaction_date', 'contact_detail_id', 'company_id', 'address_id',
        'type', 'amount', 'expected_revenue', 'expected_close_date', 'lead_source_id', 'description', 'status_id', 'status_type',
    ];

    // Override the status relationship from the trait
    public function status()
    {
        return $this->morphTo();
    }

    public function type()
    {
        return $this->morphTo();
    }

    public function leadSource()
    {
        return $this->belongsTo(LeadSource::class);
    }

    public function followUps()
    {
        return $this->morphMany(FollowUp::class, 'followupable');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function contactDetail()
    {
        return $this->belongsTo(ContactDetail::class);
    }

    public function contactComapnyDetails()
    {
        return $this->hasMany(ContactDetail::class, 'company_id', 'company_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function rating()
    {
        return $this->belongsTo(RatingType::class, 'rating_type_id');
    }

    public function customFields()
    {
        return $this->hasMany(LeadCustomField::class);
    }

    /**
     * A lead can have many notes.
     */
    public function leadNotes(): HasMany
    {
        return $this->hasMany(LeadNote::class);
    }

    public function itemMasters()
    {
        return $this->belongsToMany(ItemMaster::class)
            ->withPivot(['quantity', 'price'])
            ->withTimestamps();
    }

    public function leadActivities()
    {
        return $this->hasMany(LeadActivity::class, 'lead_id');
    }

    public function getDisplayNameAttribute()
    {
        if ($this->company) {
            return $this->company->name; // Show company name if available
        } elseif ($this->contactDetail) {
            return $this->contactDetail->full_name; // Otherwise, show contact name
        }
        return 'N/A'; // Default if neither is available
    }

    protected $casts = [
        'custom_fields' => 'array',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($deal) {
            $deal->reference_code = NumberSeries::getNextNumber(Deal::class);
        });

        static::saving(function ($deal) {
            if ($deal->type_id && !$deal->type_type) {
                $deal->type_type = \App\Models\TypeMaster::class;
            }
        });

        static::created(function ($deal) {
            NumberSeries::incrementNextNumber(Deal::class);
        });
    }

}
