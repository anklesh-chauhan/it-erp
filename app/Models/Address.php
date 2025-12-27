<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Traits\HasApprovalWorkflow;

class Address extends BaseModel
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = [
        'company_id', 'contact_detail_id', 'address_type', 'street', 'area_town', 'pin_code',
        'city_id', 'state_id', 'country_id', 'sort', 'addressable_id', 'addressable_type', 'type_master_id',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function contactDetail()
    {
        return $this->belongsTo(ContactDetail::class);
    }

    public function typeMaster()
    {
        return $this->belongsTo(TypeMaster::class, 'type_master_id');
    }

    public function addressable()
    {
        return $this->morphTo();
    }

    public function addressType()
    {
        return $this->belongsTo(TypeMaster::class, 'type_master_id');
    }

    public function accountMasters()
    {
        return $this->belongsToMany(AccountMaster::class, 'account_master_address_details', 'address_id', 'account_master_id');
    }

    public function gstDetail(): HasOne
    {
        // Assuming the relationship is 1:1 or 1:0 for a registered GST address
        return $this->hasOne(AccountMasterGSTDetail::class);
    }

}
