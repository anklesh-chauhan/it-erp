<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Traits\HasApprovalWorkflow;

class LeadCustomField extends Model
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = [
        'label',
        'type',
        'name',
    ];

}
