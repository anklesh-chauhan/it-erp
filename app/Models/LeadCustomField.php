<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Traits\HasApprovalWorkflow;

class LeadCustomField extends BaseModel
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = [
        'label',
        'type',
        'name',
    ];

}
