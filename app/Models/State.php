<?php

namespace App\Models;


use App\Models\BaseModel;
use App\Traits\HasApprovalWorkflow;

class State extends BaseModel
{
    use HasApprovalWorkflow;

    protected $fillable = ['name', 'country_id', 'gst_code'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
