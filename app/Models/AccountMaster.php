<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AccountMaster extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'owner_id',
        'type_master_id',
        'name',
        'account_code',
        'phone_number',
        'email',
        'secondary_email',
        'website',
        'no_of_employees',
        'twitter',
        'linked_in',
        'annual_revenue',
        'sic_code',
        'ticker_symbol',
        'description',
        'industry_type_id',
        'region_id',
        'ref_dealer_contact',
        'commission',
        'alias',
        'parent_id',
        'rating_type_id',
        'account_ownership_id',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function typeMaster()
    {
        return $this->belongsTo(TypeMaster::class, 'type_master_id');
    }

    public function industryType(): BelongsTo
    {
        return $this->belongsTo(IndustryType::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function dealerName()
    {
        return $this->belongsTo(ContactDetail::class, 'ref_dealer_contact');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(AccountMaster::class, 'parent_id');
    }

    public function ratingType(): BelongsTo
    {
        return $this->belongsTo(RatingType::class);
    }

    public function accountOwnership(): BelongsTo
    {
        return $this->belongsTo(AccountOwnership::class);
    }

    public function contactDetails(): BelongsToMany
    {
        return $this->belongsToMany(ContactDetail::class, 'account_master_contact_details');
    }

    public function addresses(): BelongsToMany
    {
        return $this->belongsToMany(Address::class, 'account_master_address_details', 'account_master_id', 'address_id');
    }

    public function category(): MorphTo
    {
        return $this->morphTo();
    }

    public function typeable(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($accountMaster) {
            $typeMasterId = $accountMaster->type_master_id ?? null;
            $accountMaster->account_code = NumberSeries::getNextNumber(AccountMaster::class, $typeMasterId);
        });

        static::created(function ($accountMaster) {
            $typeMasterId = $accountMaster->type_master_id ?? null;
            NumberSeries::incrementNextNumber(AccountMaster::class, $typeMasterId);

            // Check if type_master_id is 2 => create a Company
            if ($typeMasterId == 2) {
                $company = Company::create([
                    'name' => $accountMaster->name,
                    'phone_number' => $accountMaster->phone_number,
                    'email' => $accountMaster->email,
                    'secondary_email' => $accountMaster->secondary_email,
                    'no_of_employees' => $accountMaster->no_of_employees,
                    'industry_type_id' => $accountMaster->industry_type_id,
                    'twitter' => $accountMaster->twitter,
                    'linked_in' => $accountMaster->linked_in,
                    'website' => $accountMaster->website,
                    'description' => $accountMaster->description,
                    'account_master_id' => $accountMaster->id, // establish link
                ]);
            }
        });

        static::deleting(function ($account_master) {
            $account_master->addresses()->detach();
            $account_master->contactDetails()->detach();
        });
        static::restoring(function ($account_master) {
            $account_master->addresses()->restore();
            $account_master->contactDetails()->restore();
        });
        static::forceDeleted(function ($account_master) {
            $account_master->addresses()->forceDelete();
            $account_master->contactDetails()->forceDelete();
        });
        static::updated(function ($account_master) {
            $account_master->addresses()->sync($account_master->addresses()->pluck('id')->toArray());
            $account_master->contactDetails()->sync($account_master->contactDetails()->pluck('id')->toArray());
        });
        static::restored(function ($account_master) {
            $account_master->addresses()->restore();
            $account_master->contactDetails()->restore();
        });
    }
}
