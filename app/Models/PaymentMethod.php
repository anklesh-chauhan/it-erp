<?php

namespace App\Models;


use App\Models\BaseModel;
use App\Traits\HasApprovalWorkflow;

class PaymentMethod extends BaseModel
{
    use HasApprovalWorkflow;

    protected $fillable = ['name', 'description'];
}
