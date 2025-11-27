<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasApprovalWorkflow;

class ShippingMethod extends Model
{
    use HasApprovalWorkflow;

    protected $fillable = ['name', 'description'];
}
