<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\HasApprovalWorkflow;

class PaymentTerm extends Model
{
    use HasApprovalWorkflow;

    protected $fillable = ['code', 'name', 'due_in_days', 'description'];
}
