<?php

namespace App\Models;


use App\Models\BaseModel;
use App\Traits\HasApprovalWorkflow;

class TermsAndConditionsMaster extends BaseModel
{
    use HasApprovalWorkflow;

    protected $fillable = [
        'document_type',
        'title',
        'content',
        'order',
        'is_default',
    ];
}
