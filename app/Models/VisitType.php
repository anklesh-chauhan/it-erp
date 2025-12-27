<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Traits\HasApprovalWorkflow;

class VisitType extends BaseModel
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = ['name'];

}
