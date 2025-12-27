<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Traits\HasApprovalWorkflow;

class City extends BaseModel
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = ['name', 'state_id', 'country_id'];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function pinCode()
    {
        return $this->hasOne(CityPinCode::class);
    }
}
