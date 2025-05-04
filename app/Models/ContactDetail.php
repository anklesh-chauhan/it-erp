<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'salutation',
        'first_name',
        'last_name',
        'birthday',
        'email',
        'mobile_number',
        'whatsapp_number',
        'alternate_phone',
        'designation_id',
        'department_id',
        'linkedin',
        'facebook',
        'twitter',
        'website',
        'notes',
    ];

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($query) use ($searchTerm) {
            $query->where('first_name', 'like', "%{$searchTerm}%")
                ->orWhere('last_name', 'like', "%{$searchTerm}%")
                ->orWhereHas('company', fn ($query) =>
                    $query->where('name', 'like', "%{$searchTerm}%")
                );
        });
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Relationship: Contact belongs to a Company (Optional)
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function accountMaster()
    {
        return $this->belongsToMany(AccountMaster::class, 'account_master_contact_details');
    }


    public function companies()
    {
        return $this->belongsToMany(CompanyMaster::class, 'company_master_contact_details');
    }

    public function companyMasters()
    {
        return $this->belongsToMany(CompanyMaster::class, 'company_master_contact_details');
    }

    /**
     * Relationship: Contact can have multiple Addresses
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Accessor: Get full name as 'Salutation FirstName LastName'
     */
    public function getFullNameAttribute(): string
    {
        $name = trim("{$this->salutation} {$this->first_name} {$this->last_name}");
        return $name ?: 'N/A';
    }

    /**
     * Mutator: Ensure names are properly capitalized
     */
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = ucfirst(strtolower($value));
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = ucfirst(strtolower($value));
    }

    /**
     * Scope: Filter contacts by upcoming birthdays
     */
    public function scopeUpcomingBirthdays($query)
    {
        return $query->whereMonth('birthday', now()->month)
                     ->whereDay('birthday', '>=', now()->day)
                     ->orderBy('birthday');
    }

    public function contactable()
    {
        return $this->morphTo();
    }
}
