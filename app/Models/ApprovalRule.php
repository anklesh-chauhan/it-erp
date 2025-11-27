<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalRule extends Model
{
    protected $table = 'approval_rules';

    protected $fillable = [
        'module', 'territory_id', 'level', 'approver_id', 'min_amount', 'max_amount', 'active'
        ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'active' => 'boolean',
        ];

    public function approver(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'approver_id');
    }

    public function territory(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Territory::class, 'territory_id');
    }

    public function scopeForRecord($query, string $module, $record)
    {
        $amount = $record->total ?? ($record->amount ?? 0);

        return $query->where('module', $module)
            ->where('active', true)
            ->where(function ($q) use ($record) {
            $q->whereNull('territory_id')
            ->orWhere('territory_id', $record->territory_id ?? null);
            })
            ->where('min_amount', '<=', $amount)
            ->where(function ($q) use ($amount) {
            $q->whereNull('max_amount')->orWhere('max_amount', '>=', $amount);
            })
        ->orderBy('level');
    }
}
