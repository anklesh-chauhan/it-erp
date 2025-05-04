<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'secondary_email',
        'no_of_employees',
        'industry_type_id',
        'twitter',
        'linked_in',
        'website',
        'description',
        'account_master_id',
    ];

    public function accountMaster()
    {
        return $this->belongsTo(AccountMaster::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function contactDetails()
    {
        return $this->hasMany(ContactDetail::class);
    }

    public function industryType()
    {
        return $this->belongsTo(IndustryType::class, 'industry_type_id');
    }

    public function itemMaster()
    {
        return $this->belongsTo(ItemMaster::class);
    }

}
