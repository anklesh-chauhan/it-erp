<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasApprovalWorkflow;

class LeadSource extends Model
{
    use HasApprovalWorkflow;

    protected $fillable = ['name'];
}
