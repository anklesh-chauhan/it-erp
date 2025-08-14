<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ledger extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'chart_of_account_id',
        'date',
        'reference',
        'description',
        'debit',
        'credit',
        'ledgerable_type',
        'ledgerable_id',
        'currency',
        'currency_symbol',
        'currency_name',
        'is_reconciled',
        'is_active',
        'is_system',
    ];

    protected $casts = [
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
        'date' => 'date',
        'is_reconciled' => 'boolean',
        'is_active' => 'boolean',
        'is_system' => 'boolean',
    ];

    /**
     * Relationship: Belongs to a chart of account.
     */
    public function chartOfAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class);
    }

    /**
     * Polymorphic relationship: ledgerable (invoice, payment, etc.)
     */
    public function ledgerable(): MorphTo
    {
        return $this->morphTo();
    }
}
