<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddressType extends Model
{
    protected $fillable = ['name'];

    public function addresses()
    {
        return $this->hasMany(Address::class, 'address_type_id');
    }
}
