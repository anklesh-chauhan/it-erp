<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasCustomerInteractionFields;

class Lead extends Model
{
    use HasFactory, HasCustomerInteractionFields;

    protected $fillable = [
        'owner_id',
        'reference_code',
        'transaction_date',
        'contact_detail_id',
        'company_id',
        'address_id',
        'lead_source_id',
        'rating_type_id',
        'annual_revenue',
        'description',
        'custom_fields',
        'status_id',
        'status_type',
    ];

    public function convertToDeal(bool $createCompanyMaster = false, bool $createAccountMaster = false)
    {
        // Generate a new reference code for the Deal using NumberSeries
        $newReferenceCode = NumberSeries::getNextNumber(Deal::class);

        // Fetch the default status ID for deals (Negotiation/Review)
        $defaultDealStage = DealStage::where('name', 'Negotiation/Review')->first();

        // Get the company name (if available)
        $companyName = $this->company?->name ?? 'Unnamed Deal';

        // Create a new Deal instance
        $deal = Deal::create([
            'owner_id' => $this->owner_id,
            'reference_code' => $newReferenceCode,
            'deal_name' => $companyName,
            'transaction_date' => now(),
            'contact_id' => $this->contact_detail_id,
            'company_id' => $this->company_id,
            'address_id' => $this->address_id,
            'amount' => $this->annual_revenue ?? 0,
            'expected_revenue' => $this->annual_revenue ?? 0,
            'expected_close_date' => now()->addDays(30),
            'description' => $this->description,
            'status_id' => $defaultDealStage ? $defaultDealStage->id : null,
            'status_type' => DealStage::class,
        ]);

        // Optionally create an Account Master if selected
        if ($createAccountMaster && $this->company_id) {
            $company = $this->company;

            if (!$company->account_master_id) {
            // Determine type_master_id (e.g., default to a specific type or make it configurable)
                $typeMasterId = 2; // 2 is for custommer, You can set this based on a default or form input if needed
                $accountCode = NumberSeries::getNextNumber(AccountMaster::class, $typeMasterId);

                $accountMaster = AccountMaster::create([
                    'owner_id' => $this->owner_id,
                    'type_master_id' => $typeMasterId, // Set to null or a default type
                    'name' => $this->company?->name ?? 'Unnamed Account',
                    'account_code' => $accountCode,
                    'phone_number' => $this->company?->phone_number,
                    'email' => $this->company?->email,
                    'secondary_email' => $this->company?->secondary_email,
                    'website' => $this->company?->website,
                    'no_of_employees' => $this->company?->no_of_employees,
                    'twitter' => $this->company?->twitter,
                    'linked_in' => $this->company?->linked_in,
                    'annual_revenue' => $this->annual_revenue,
                    'description' => $this->description,
                    'industry_type_id' => $this->company?->industry_type_id,
                    'ref_dealer_contact' => $this->contact_detail_id,
                ]);

                $this->company->update([
                    'account_master_id' => $accountMaster->id,
                ]);

                // Attach contact details and addresses if applicable
                if ($this->contactComapnyDetails->isNotEmpty()) {
                    $accountMaster->contactDetails()->sync($this->contactComapnyDetails->pluck('id')->toArray());
                }
                if ($this->address) {
                    $accountMaster->addresses()->attach($this->address_id);
                }

                // Log or notify about the creation of the Account Master
                \Filament\Notifications\Notification::make()
                    ->title('Account Master Created')
                    ->body("Account Master for {$this->company?->name} has been created.")
                    ->success()
                    ->send();
            } else {
                \Filament\Notifications\Notification::make()
                    ->title('Account Master Already Exists')
                    ->body("Account Master for {$this->company?->name} already exists.")
                    ->warning()
                    ->send();
            }
        }

        // Optionally update the lead's status to "Converted"
        $convertedStatus = LeadStatus::where('name', 'Converted')->first();
        if ($convertedStatus) {
            $this->update([
                'status_id' => $convertedStatus->id,
                'status_type' => LeadStatus::class,
            ]);
        }

        return $deal;
    }

    public function status()
    {
        return $this->morphTo();
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

    public function leadSource()
    {
        return $this->belongsTo(LeadSource::class);
    }

    // public function leadStatus()
    // {
    //     return $this->belongsTo(LeadStatus::class);
    // }

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

        static::creating(function ($lead) {
            $lead->reference_code = NumberSeries::getNextNumber(Lead::class);
        });

        static::created(function ($lead) {
            NumberSeries::incrementNextNumber(Lead::class);
        });
    }
}
