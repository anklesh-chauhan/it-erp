<?php

namespace App\Models;


use App\Models\BaseModel;
use App\Traits\HasApprovalWorkflow;

class LeadStatus extends BaseModel
{
    use HasApprovalWorkflow;

    protected $table = 'lead_statuses';

    protected $fillable = ['name', 'color', 'order'];

}
