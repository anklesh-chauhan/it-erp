<?php

namespace App\Services\Approval;

use App\Enums\ApprovalActivityAction;
use App\Enums\ApprovalStatus;
use App\Enums\ApprovalStepStatus;
use App\Events\ApprovalStatusChanged;
use App\Models\Approval;
use App\Models\ApprovalActivity;
use App\Models\ApprovalDelegation;
use App\Models\ApprovalFlow;
use App\Models\ApprovalSetting;
use App\Models\ApprovalStep;
use App\Models\Territory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use LogicException;

class ApprovalService
{
    /**
     * Start approval for any approvable model.
     *
     * @throws LogicException
     */
    public function start(
        Model $approvable,
        string $module,
        ?int $territoryId = null,
        ?float $amount = null
    ): Approval {
        if (! ApprovalSetting::moduleRequiresApproval($module)) {
            throw new LogicException("Approval is not enabled for module [{$module}].");
        }

        $amount ??= $this->resolveAmount($approvable);
        $flow = $this->resolveApprovalFlow($module, $territoryId, $amount);

        return DB::transaction(function () use ($approvable, $module, $flow, $territoryId, $amount): Approval {
            $approvable = $approvable->newQuery()
                ->whereKey($approvable->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($approvable->approval()->whereIn('approval_status', [ApprovalStatus::Pending->value, ApprovalStatus::Approved->value])->exists()) {
                throw new LogicException('Approval already exists for this record.');
            }

            $applicantUser = Auth::user();
            $applicantRoleLevel = $applicantUser?->employee?->positions()
                ->with('jobRole')
                ->get()
                ->pluck('jobRole.level')
                ->min();
            $applicantRoleLevel ??= PHP_INT_MAX;

            $approval = $approvable->approval()->create([
                'approval_flow_id' => $flow->id,
                'module' => $module,
                'record_type' => $approvable::class,
                'record_id' => $approvable->getKey(),
                'requested_by' => Auth::id(),
                'requested_amount' => $amount,
                'territory_id' => $territoryId,
                'flow_version' => $flow->version,
                'approval_status' => ApprovalStatus::Pending->value,
                'submitted_record_summary' => $this->submittedRecordSummary($approvable),
            ]);

            $selectedSteps = [];
            $selectedApprovers = [];

            foreach ($flow->steps as $flowStep) {
                $stepRole = $flowStep->jobRole;

                if (! $stepRole) {
                    if ($flowStep->can_skip) {
                        $this->logActivity($approval, ApprovalActivityAction::Skipped, metadata: [
                            'flow_step_id' => $flowStep->id,
                            'reason' => 'missing_job_role',
                        ]);

                        continue;
                    }

                    throw new LogicException("No job role configured for approval flow step [{$flowStep->id}].");
                }

                if ($stepRole->level <= $applicantRoleLevel) {
                    $this->logActivity($approval, ApprovalActivityAction::Skipped, metadata: [
                        'flow_step_id' => $flowStep->id,
                        'reason' => 'same_or_junior_role',
                    ]);

                    continue;
                }

                $user = $this->resolveApprover(
                    $flowStep->job_role_id,
                    $territoryId,
                    $flowStep->territory_scope
                );

                if (! $user && $flowStep->can_skip) {
                    $this->logActivity($approval, ApprovalActivityAction::Skipped, metadata: [
                        'flow_step_id' => $flowStep->id,
                        'reason' => 'no_approver_found',
                    ]);

                    continue;
                }

                if (! $user) {
                    throw new LogicException("No approver found for approval flow step [{$flowStep->step_order}].");
                }

                $assignedUser = $this->resolveDelegatedApprover($user, $module);

                $step = ApprovalStep::create([
                    'approval_id' => $approval->id,
                    'step_order' => $flowStep->step_order,
                    'job_role_id' => $flowStep->job_role_id,
                    'assigned_user_id' => $assignedUser->id,
                    'reassigned_from_user_id' => $assignedUser->is($user) ? null : $user->id,
                    'status' => ApprovalStepStatus::Pending->value,
                    'due_at' => now()->addHours($flowStep->sla_hours ?? 24),
                ]);

                $selectedSteps[] = [
                    'approval_flow_step_id' => $flowStep->id,
                    'approval_step_id' => $step->id,
                    'step_order' => $flowStep->step_order,
                    'job_role_id' => $flowStep->job_role_id,
                    'territory_scope' => $flowStep->territory_scope,
                    'can_skip' => (bool) $flowStep->can_skip,
                    'sla_hours' => $flowStep->sla_hours,
                ];

                $selectedApprovers[] = [
                    'approval_step_id' => $step->id,
                    'step_order' => $step->step_order,
                    'user_id' => $assignedUser->id,
                    'name' => $assignedUser->name,
                    'delegated_from_user_id' => $assignedUser->is($user) ? null : $user->id,
                ];

                $this->logActivity($approval, ApprovalActivityAction::Assigned, $step);
            }

            $approval->update([
                'selected_steps' => $selectedSteps,
                'selected_approvers' => $selectedApprovers,
            ]);

            $this->logActivity($approval, ApprovalActivityAction::Submitted);

            if ($approval->steps()->count() === 0) {
                $this->finalizeApproval($approval, ApprovalStatus::Approved);
            }

            return $approval->refresh();
        });
    }

    public function approve(Approval $approval, int $userId, ?string $comments = null): bool
    {
        return DB::transaction(function () use ($approval, $userId, $comments): bool {
            $approval = Approval::query()
                ->whereKey($approval->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($approval->isFinalized() || $approval->approval_status !== ApprovalStatus::Pending->value) {
                return false;
            }

            $step = $this->currentStepForUpdate($approval);

            if (! $step || $step->assigned_user_id !== $userId) {
                return false;
            }

            $fromStatus = $step->status->value;
            $step->transitionTo(ApprovalStepStatus::Approved, $comments);

            $this->logActivity($approval, ApprovalActivityAction::Approved, $step, $userId, $fromStatus, ApprovalStepStatus::Approved->value, $comments);

            if ($approval->fresh()->isFullyApproved()) {
                $this->finalizeApproval($approval, ApprovalStatus::Approved);
            }

            return true;
        });
    }

    public function reject(Approval $approval, int $userId, ?string $comments = null): bool
    {
        return DB::transaction(function () use ($approval, $userId, $comments): bool {
            $approval = Approval::query()
                ->whereKey($approval->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($approval->isFinalized() || $approval->approval_status !== ApprovalStatus::Pending->value) {
                return false;
            }

            $step = $this->currentStepForUpdate($approval);

            if (! $step || $step->assigned_user_id !== $userId) {
                return false;
            }

            $fromStatus = $step->status->value;
            $step->transitionTo(ApprovalStepStatus::Rejected, $comments);

            $this->logActivity($approval, ApprovalActivityAction::Rejected, $step, $userId, $fromStatus, ApprovalStepStatus::Rejected->value, $comments);
            $this->finalizeApproval($approval, ApprovalStatus::Rejected);

            return true;
        });
    }

    public function cancel(Approval $approval): void
    {
        DB::transaction(function () use ($approval): void {
            $approval = Approval::query()
                ->whereKey($approval->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($approval->isFinalized()) {
                return;
            }

            $approval->steps()
                ->where('status', ApprovalStepStatus::Pending->value)
                ->each(function (ApprovalStep $step) use ($approval): void {
                    $fromStatus = $step->status->value;
                    $step->transitionTo(ApprovalStepStatus::Cancelled);
                    $this->logActivity($approval, ApprovalActivityAction::Cancelled, $step, Auth::id(), $fromStatus, ApprovalStepStatus::Cancelled->value);
                });

            $this->finalizeApproval($approval, ApprovalStatus::Cancelled);
        });
    }

    protected function currentStepForUpdate(Approval $approval): ?ApprovalStep
    {
        return ApprovalStep::query()
            ->where('approval_id', $approval->id)
            ->where('status', ApprovalStepStatus::Pending->value)
            ->orderBy('step_order')
            ->lockForUpdate()
            ->first();
    }

    protected function finalizeApproval(Approval $approval, ApprovalStatus $status): void
    {
        $updated = Approval::query()
            ->whereKey($approval->id)
            ->whereNull('finalized_at')
            ->update([
                'approval_status' => $status->value,
                'completed_at' => now(),
                'finalized_at' => now(),
            ]);

        if ($updated !== 1) {
            return;
        }

        $approval->refresh();

        DB::afterCommit(function () use ($approval, $status): void {
            event(new ApprovalStatusChanged($approval, $status->value));
        });
    }

    protected function resolveApprovalFlow(string $module, ?int $territoryId, float $amount): ApprovalFlow
    {
        $flow = ApprovalFlow::query()
            ->where('module', $module)
            ->where('active', true)
            ->effectiveOn()
            ->where(fn ($query) => $query->whereNull('territory_id')->orWhere('territory_id', $territoryId))
            ->where(fn ($query) => $query->whereNull('min_amount')->orWhere('min_amount', '<=', $amount))
            ->where(fn ($query) => $query->whereNull('max_amount')->orWhere('max_amount', '>=', $amount))
            ->with('steps.jobRole')
            ->orderByDesc('priority')
            ->orderByDesc('territory_id')
            ->orderByDesc('version')
            ->first();

        if (! $flow) {
            throw new LogicException("No approval flow found for module [{$module}]");
        }

        return $flow;
    }

    protected function resolveApprover(int $jobRoleId, ?int $territoryId, string $territoryScope = 'self'): ?User
    {
        $query = User::query()
            ->whereHas('employee.positions', fn ($query) => $query->where('job_role_id', $jobRoleId));

        $territoryIds = $this->resolveTerritoryIds($territoryId, $territoryScope);

        if ($territoryIds !== null) {
            $query->whereHas('employee.positions.territories', fn ($territoryQuery) => $territoryQuery
                ->whereIn('territories.id', $territoryIds)
            );
        }

        return $query->first();
    }

    protected function resolveDelegatedApprover(User $user, string $module): User
    {
        $delegation = ApprovalDelegation::query()
            ->activeFor($user->id, $module)
            ->with('delegate')
            ->first();

        return $delegation?->delegate ?? $user;
    }

    /**
     * @return array<int>|null
     */
    protected function resolveTerritoryIds(?int $territoryId, string $territoryScope): ?array
    {
        if ($territoryId === null || $territoryScope === 'all') {
            return null;
        }

        if ($territoryScope === 'self') {
            return [$territoryId];
        }

        $territoryIds = [$territoryId];
        $pending = [$territoryId];

        while ($pending !== []) {
            $children = Territory::query()
                ->whereIn('parent_territory_id', $pending)
                ->pluck('id')
                ->all();

            $pending = array_values(array_diff($children, $territoryIds));
            $territoryIds = array_values(array_unique([...$territoryIds, ...$children]));
        }

        return $territoryIds;
    }

    protected function resolveAmount(Model $approvable): float
    {
        return match (true) {
            isset($approvable->total) => (float) $approvable->total,
            isset($approvable->amount) => (float) $approvable->amount,
            isset($approvable->expected_value) => (float) $approvable->expected_value,
            default => 0.0,
        };
    }

    /**
     * @return array<string, mixed>
     */
    protected function submittedRecordSummary(Model $approvable): array
    {
        return [
            'class' => $approvable::class,
            'id' => $approvable->getKey(),
            'document_number' => method_exists($approvable, 'getDocumentNumber') ? $approvable->getDocumentNumber() : null,
            'attributes' => collect($approvable->attributesToArray())
                ->except(['created_by', 'updated_by', 'deleted_by', 'created_at', 'updated_at', 'deleted_at'])
                ->take(25)
                ->all(),
        ];
    }

    /**
     * @param  array<string, mixed>|null  $metadata
     */
    protected function logActivity(
        Approval $approval,
        ApprovalActivityAction $action,
        ?ApprovalStep $step = null,
        ?int $actorId = null,
        ?string $fromStatus = null,
        ?string $toStatus = null,
        ?string $comments = null,
        ?array $metadata = null
    ): void {
        ApprovalActivity::create([
            'approval_id' => $approval->id,
            'approval_step_id' => $step?->id,
            'actor_id' => $actorId ?? Auth::id(),
            'action' => $action,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'comments' => $comments,
            'metadata' => $metadata,
        ]);
    }
}
