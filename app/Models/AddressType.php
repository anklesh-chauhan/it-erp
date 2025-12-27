<?php

namespace App\Models;


use App\Models\BaseModel;
use App\Traits\HasApprovalWorkflow;

class AddressType extends BaseModel
{
    use HasApprovalWorkflow;

    protected $fillable = ['name'];

    public function addresses()
    {
        return $this->hasMany(Address::class, 'address_type_id');
    }
}
