<?php

namespace App\Traits;

use App\Models\ApprovalRule;
use App\Services\ApprovalService;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasApprovalWorkflow
{
    /**
     * Polymorphic relation
     */
    public function approval(): MorphOne
    {
        return $this->morphOne(\App\Models\Approval::class, 'approvable');
    }

    /**
     * Auto-detect module name (model name)
     */
    protected function detectModuleName(): string
    {
        return class_basename(static::class);
    }

    /**
     * Start approval workflow
     */
    public function startApprovalFromRules(
        ?string $module = null,
        ?int $territoryId = null,
        ?float $amount = null
    ) {
        $module = $module ?? $this->detectModuleName();

        if (! $this->canStartApproval($module)) {
            throw new \Exception("Approval already in progress or no applicable rules.");
        }

        $territoryId = $territoryId ?? ($this->territory_id ?? null);
        $amount      = $amount ?? ($this->total ?? ($this->amount ?? 0));

        /** @var ApprovalService $service */
        $service = app(ApprovalService::class);

        return $service->startFromRules($this, $module, $territoryId, $amount);
    }

    /**
     * Check if any rules exist for this module
     */
    public static function moduleHasAnyRules(?string $module = null): bool
    {
        $model = new static();
        $module = $module ?? $model->detectModuleName();

        return ApprovalRule::where('module', $module)->exists();
    }

    /**
     * Check rule for specific record (territory + amount)
     */
    public function hasApplicableApprovalRule(?string $module = null): bool
    {
        $module = $module ?? $this->detectModuleName();

        $territory = $this->territory_id ?? null;
        $amount    = $this->total ?? ($this->amount ?? 0);

        return ApprovalRule::query()
            ->where('module', $module)
            ->where('active', true)
            ->where(function ($q) use ($territory) {
                $q->whereNull('territory_id')
                  ->orWhere('territory_id', $territory);
            })
            ->where(function ($q) use ($amount) {
                $q->whereNull('min_amount')
                  ->orWhere('min_amount', '<=', $amount);
            })
            ->where(function ($q) use ($amount) {
                $q->whereNull('max_amount')
                  ->orWhere('max_amount', '>=', $amount);
            })
            ->exists();
    }

    /**
     * Main check for Filament actions
     */
    public function canSendForApproval(?string $module = null): bool
    {
        $module = $module ?? $this->detectModuleName();

        // No rules exist for this module?
        if (! static::moduleHasAnyRules($module)) {
            return false;
        }

        // Record does not match territory / amount?
        if (! $this->hasApplicableApprovalRule($module)) {
            return false;
        }

        // Already pending?
        if ($this->approval && $this->approval->status === 'pending') {
            return false;
        }

        return true;
    }

    /**
     * Wrapper
     */
    public function canStartApproval(?string $module = null): bool
    {
        return $this->canSendForApproval($module);
    }
}
