<?php

namespace App\Models;


use App\Models\BaseModel;
use App\Traits\HasApprovalWorkflow;

class Status extends BaseModel
{
    use HasApprovalWorkflow;

    // Define common attributes or methods if needed
    protected $fillable = ['name', 'color', 'order'];

    // Polymorphic relationship to entities (Lead, Deal, etc.)
    public function statusable()
    {
        return $this->morphTo();
    }
}
