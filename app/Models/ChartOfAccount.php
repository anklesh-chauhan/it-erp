<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\HasApprovalWorkflow;

class ChartOfAccount extends BaseModel
{
    use SoftDeletes, HasApprovalWorkflow;

    protected $fillable = [
        'parent_id',
        'account_type_id',
        'code',
        'name',
        'description',
        'is_group',
        'is_active',
        'is_system',
        'balance',
        'opening_balance',
        'opening_balance_date',
        'currency',
        'currency_symbol',
        'currency_name',
    ];

    protected $casts = [
        'is_group' => 'boolean',
        'is_active' => 'boolean',
        'is_system' => 'boolean',
        'balance' => 'decimal:2',
        'opening_balance' => 'decimal:2',
        'opening_balance_date' => 'date',
    ];

    /**
     * Relationship: Parent account (for nested grouping).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'parent_id');
    }

    /**
     * Relationship: Child accounts (grouped under this account).
     */
    public function children(): HasMany
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_id');
    }

    /**
     * Relationship: Account type (Asset, Liability, etc.)
     */
    public function accountType(): BelongsTo
    {
        return $this->belongsTo(AccountType::class);
    }
}
