<?php

namespace App\Models;

use App\Models\BaseModel;

class SalesDcrExpense extends BaseModel
{
    protected $fillable = [
        'sales_dcr_id',
        'expense_type_id',
        'transport_mode_id',
        'amount',
        'quantity',
        'rate',
        'is_auto_calculated',
        'remarks',
        'meta',
    ];

    protected $casts = [
        'is_auto_calculated' => 'boolean',
        'meta' => 'array',
    ];

    /* ---------------- Relationships ---------------- */

    /* ---------------- Relations ---------------- */

    public function dcr()
    {
        return $this->belongsTo(SalesDcr::class, 'sales_dcr_id');
    }

    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class);
    }

    public function transportMode()
    {
        return $this->belongsTo(TransportMode::class);
    }

    public function items()
    {
        return $this->hasMany(SalesDcrExpenseItem::class);
    }
}
