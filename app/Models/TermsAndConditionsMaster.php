<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasApprovalWorkflow;

class TermsAndConditionsMaster extends Model
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
