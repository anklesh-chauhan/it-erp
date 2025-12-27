<?php

namespace App\Models;


use App\Models\BaseModel;
use App\Traits\HasApprovalWorkflow;

class PaymentTerm extends BaseModel
{
    use HasApprovalWorkflow;

    protected $fillable = ['code', 'name', 'due_in_days', 'description'];
}
