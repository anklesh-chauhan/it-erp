<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasApprovalWorkflow;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseConfiguration extends BaseModel
{
    use HasFactory, HasApprovalWorkflow;

    protected $fillable = [
        'expense_type_id', 'transport_mode_id', 'role_id', 'territory_id',
        'city_id', 'calculation_type', 'rate', 'max_amount', 'min_amount',
        'requires_attachment', 'requires_approval', 'allow_manual_override',
        'effective_from', 'effective_to', 'is_active'
    ];

    protected $casts = [
        'requires_attachment' => 'boolean',
        'requires_approval' => 'boolean',
        'allow_manual_override' => 'boolean',
        'is_active' => 'boolean',
        'effective_from' => 'date',
        'effective_to' => 'date',
    ];

    public function expenseType(): BelongsTo
    {
        return $this->belongsTo(ExpenseType::class);
    }

    public function transportMode(): BelongsTo
    {
        return $this->belongsTo(TransportMode::class);
    }

    public function conditions(): HasMany
    {
        return $this->hasMany(ExpenseConfigurationCondition::class);
    }
}
