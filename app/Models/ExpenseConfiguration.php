<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasApprovalWorkflow;

class ExpenseConfiguration extends Model
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = [
        'category_id',
        'expense_type_id',
        'mode_of_transport_id',
        'rate_per_km',
        'fixed_expense'
    ];

    public function category()
    {
        return $this->morphTo();
    }
}
