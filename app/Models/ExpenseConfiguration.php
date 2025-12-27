<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasApprovalWorkflow;

class ExpenseConfiguration extends BaseModel
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
