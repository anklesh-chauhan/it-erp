<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasApprovalWorkflow;

class AddressType extends Model
{
    use HasApprovalWorkflow;

    protected $fillable = ['name'];

    public function addresses()
    {
        return $this->hasMany(Address::class, 'address_type_id');
    }
}
