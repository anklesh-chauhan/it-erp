<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasApprovalWorkflow;

class PaymentMethod extends Model
{
    use HasApprovalWorkflow;

    protected $fillable = ['name', 'description'];
}
