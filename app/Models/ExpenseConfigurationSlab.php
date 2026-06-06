<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseConfigurationSlab extends BaseModel
{
    protected $fillable = [
        'expense_configuration_id',
        'min_value',
        'max_value',
        'rate',
        'flat_amount',
    ];

    public function configuration(): BelongsTo
    {
        return $this->belongsTo(ExpenseConfiguration::class, 'expense_configuration_id');
    }
}
