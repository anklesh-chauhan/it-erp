<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasApprovalWorkflow;

class LeadStatus extends Model
{
    use HasApprovalWorkflow;

    protected $table = 'lead_statuses';

    protected $fillable = ['name', 'color', 'order'];

}
