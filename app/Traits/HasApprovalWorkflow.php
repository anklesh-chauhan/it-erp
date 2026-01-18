<?php

namespace App\Traits;

use App\Models\Approval;
use App\Models\ApprovalFlow;
use App\Services\Approval\ApprovalService;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use LogicException;

trait HasApprovalWorkflow
{
    /* =====================================================
     | Relations
     ===================================================== */

    /**
     * Polymorphic approval relation
     */
    public function approval(): MorphOne
    {
        return $this->morphOne(Approval::class, 'approvable');
    }

    /**
     * Auto-detect module name (Model short name)
     */
    public static function approvalModule(): string
    {
        return class_basename(static::class);
    }

    /* =====================================================
     | Approval Flow Detection
     ===================================================== */

    /**
     * Does ANY approval flow exist for this module?
     * (cached, used for UI gating)
     */
    public static function moduleHasApprovalFlow(): bool
    {
        return cache()->remember(
            'approval:module:' . static::approvalModule(),
            now()->addMinutes(10),
            fn () => ApprovalFlow::query()
                ->where('module', static::approvalModule())
                ->where('active', true)
                ->exists()
        );
    }

    /**
     * Does THIS record qualify for any approval flow?
     * (territory + amount aware)
     */
    public function hasApplicableApprovalFlow(): bool
    {
        return ApprovalFlow::query()
            ->where('module', static::approvalModule())
            ->where('active', true)
            ->where(fn ($q) =>
                $q->whereNull('territory_id')
                  ->orWhere('territory_id', $this->resolveApprovalTerritoryId())
            )
            ->where(fn ($q) =>
                $q->whereNull('min_amount')
                  ->orWhere('min_amount', '<=', $this->resolveApprovalAmount())
            )
            ->where(fn ($q) =>
                $q->whereNull('max_amount')
                  ->orWhere('max_amount', '>=', $this->resolveApprovalAmount())
            )
            ->exists();
    }

    /**
     * FINAL check:
     * Should approval workflow apply to this record?
     */
    public function approvalApplies(): bool
    {
        return
            static::moduleHasApprovalFlow()
            && $this->hasApplicableApprovalFlow();
    }

    /* =====================================================
     | Approval Lifecycle
     ===================================================== */

    /**
     * Start approval workflow
     */
    public function startApproval(
        ?int $territoryId = null,
        ?float $amount = null
    ): Approval {
        if (! $this->approvalApplies()) {
            throw new LogicException(
                'No applicable approval flow for this record.'
            );
        }

        if ($this->approval) {
            throw new LogicException(
                'Approval already started for this record.'
            );
        }

        return app(ApprovalService::class)->start(
            approvable: $this,
            module: static::approvalModule(),
            territoryId: $territoryId ?? $this->resolveApprovalTerritoryId(),
            amount: $amount ?? $this->resolveApprovalAmount()
        );
    }

    /* =====================================================
     | Helpers (Used by Filament / Policies)
     ===================================================== */

    public function getApprovalStatus(): ?string
    {
        return $this->approval?->approval_status;
    }

    public function isApprovalCompleted(): bool
    {
        return
            $this->approval !== null
            && in_array($this->approval->approval_status, ['approved', 'rejected'], true);
    }

    public function isApprovalPending(): bool
    {
        return
            $this->approval !== null
            && $this->approval->approval_status === 'pending';
    }

    /* =====================================================
     | Internal Resolution Hooks (EXTENSIBLE)
     ===================================================== */

    /**
     * Resolve territory for approval matching
     * Override this in model if needed
     */
    protected function resolveApprovalTerritoryId(): ?int
    {
        return $this->territory_id ?? null;
    }

    /**
     * Resolve amount/value for approval matching
     * Override this in model if needed
     */
    protected function resolveApprovalAmount(): float
    {
        return (float) (
            $this->total
            ?? $this->amount
            ?? $this->expected_value
            ?? 0
        );
    }
}
