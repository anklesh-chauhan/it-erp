<?php

namespace App\Models;

use App\Traits\HasApprovalWorkflow;

class DealStage extends Status
{
    use HasApprovalWorkflow;

    protected $table = 'deal_stages';

    protected $fillable = ['name', 'color', 'order'];
}
