<?php

namespace App\Models;

use App\Traits\HasApprovalWorkflow;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseConfiguration extends BaseModel
{
    use HasApprovalWorkflow, HasFactory;

    protected $fillable = [
        'name',
        'expense_type_id',
        'calculation_strategy',
        'rate',
        'max_amount',
        'min_amount',
        'priority',
        'requires_attachment',
        'requires_approval',
        'allow_manual_override',
        'effective_from',
        'effective_to',
        'is_active',

    ];

    protected $casts = [
        'requires_attachment' => 'boolean',
        'requires_approval' => 'boolean',
        'allow_manual_override' => 'boolean',
        'is_active' => 'boolean',
        'effective_from' => 'date',
        'effective_to' => 'date',
    ];

    public function conditions()
    {
        return $this->hasMany(ExpenseConfigurationCondition::class);
    }

    public function slabs()
    {
        return $this->hasMany(ExpenseConfigurationSlab::class);
    }

    public function roles()
    {
        return $this->belongsToMany(JobRole::class, 'expense_configuration_roles');
    }

    public function positions()
    {
        return $this->belongsToMany(Position::class, 'expense_configuration_positions');
    }

    public function territories()
    {
        return $this->belongsToMany(Territory::class, 'expense_configuration_territories');
    }

    public function transportModes()
    {
        return $this->belongsToMany(TransportMode::class, 'expense_configuration_transport_modes');
    }

    public function grades()
    {
        return $this->belongsToMany(EmpGrade::class, 'expense_configuration_grades');
    }

    public function expenseType(): BelongsTo
    {
        return $this->belongsTo(ExpenseType::class);
    }

    public function transportMode(): BelongsTo
    {
        return $this->belongsTo(TransportMode::class);
    }

    public function jobRole(): BelongsTo
    {
        return $this->belongsTo(JobRole::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function territory(): BelongsTo
    {
        return $this->belongsTo(Territory::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
