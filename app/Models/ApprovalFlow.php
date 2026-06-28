<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LogicException;

class ApprovalFlow extends BaseModel
{
    protected $fillable = [
        'module',
        'priority',
        'version',
        'condition_type',
        'effective_from',
        'effective_to',
        'territory_id',
        'min_amount',
        'max_amount',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'priority' => 'integer',
            'version' => 'integer',
            'effective_from' => 'date',
            'effective_to' => 'date',
            'min_amount' => 'decimal:2',
            'max_amount' => 'decimal:2',
            'active' => 'boolean',
        ];
    }

    public function steps(): HasMany
    {
        return $this->hasMany(ApprovalFlowStep::class)
            ->orderBy('step_order');
    }

    public function territory(): BelongsTo
    {
        return $this->belongsTo(Territory::class);
    }

    public function scopeEffectiveOn(Builder $query, mixed $date = null): Builder
    {
        $date ??= today();

        return $query
            ->where(fn (Builder $query): Builder => $query->whereNull('effective_from')->orWhereDate('effective_from', '<=', $date))
            ->where(fn (Builder $query): Builder => $query->whereNull('effective_to')->orWhereDate('effective_to', '>=', $date));
    }

    protected static function booted(): void
    {
        static::saving(function (ApprovalFlow $flow): void {
            if (! $flow->active) {
                return;
            }

            $overlapExists = static::query()
                ->when($flow->exists, fn (Builder $query): Builder => $query->whereKeyNot($flow->getKey()))
                ->where('active', true)
                ->where('module', $flow->module)
                ->where('condition_type', $flow->condition_type)
                ->where('version', $flow->version)
                ->when(
                    $flow->territory_id === null,
                    fn (Builder $query): Builder => $query->whereNull('territory_id'),
                    fn (Builder $query): Builder => $query->where('territory_id', $flow->territory_id),
                )
                ->where(fn (Builder $query): Builder => $query
                    ->whereNull('effective_from')
                    ->orWhereNull('effective_to')
                    ->orWhereDate('effective_from', '<=', $flow->effective_to ?? '9999-12-31')
                )
                ->where(fn (Builder $query): Builder => $query
                    ->whereNull('effective_to')
                    ->orWhereDate('effective_to', '>=', $flow->effective_from ?? '0001-01-01')
                )
                ->where(fn (Builder $query): Builder => $query
                    ->whereNull('min_amount')
                    ->orWhere('min_amount', '<=', $flow->max_amount ?? PHP_INT_MAX)
                )
                ->where(fn (Builder $query): Builder => $query
                    ->whereNull('max_amount')
                    ->orWhere('max_amount', '>=', $flow->min_amount ?? 0)
                )
                ->exists();

            if ($overlapExists) {
                throw new LogicException('An active approval flow already overlaps this module, territory, condition, version, and effective date range.');
            }
        });
    }
}
