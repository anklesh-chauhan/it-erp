<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesDcrExpenseItem extends BaseModel
{
    protected $fillable = [
        'sales_dcr_expense_id',
        'label',
        'amount',
        'meta',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'meta' => 'array',
    ];

    /**
     * Get the parent expense record.
     */
    public function expense()
    {
        return $this->belongsTo(SalesDcrExpense::class, 'sales_dcr_expense_id');
    }
}
