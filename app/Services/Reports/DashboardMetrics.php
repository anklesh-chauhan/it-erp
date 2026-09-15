<?php

namespace App\Services\Reports;

use App\Enums\ApprovalStepStatus;
use App\Models\ApprovalStep;
use App\Models\DailyAttendance;
use App\Models\SalesDcr;
use App\Models\User;
use App\Models\Visit;
use App\Services\PositionService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DashboardMetrics
{
    public function forUser(?User $user): DashboardMetricsResult
    {
        if (! $user) {
            return DashboardMetricsResult::empty();
        }

        if (Auth::id() !== $user->id) {
            Auth::setUser($user);
        }

        return new DashboardMetricsResult(
            visitsToday: $this->visitsToday($user),
            pendingDcrs: $this->pendingDcrs($user),
            pendingApprovals: $this->pendingApprovals($user),
            punchedInToday: $this->punchedInToday($user),
        );
    }

    public function visitsToday(User $user): int
    {
        return Visit::query()
            ->applyVisibility('Visit')
            ->whereDate('visit_date', today())
            ->where('visit_status', '!=', 'cancelled')
            ->count();
    }

    public function pendingDcrs(User $user): int
    {
        return SalesDcr::query()
            ->applyVisibility('SalesDcr')
            ->whereIn('approval_status', ['draft', 'pending'])
            ->count();
    }

    public function pendingApprovals(User $user): int
    {
        return $this->pendingApprovalStepsQuery($user)->count();
    }

    public function pendingApprovalStepsQuery(User $user): Builder
    {
        return ApprovalStep::query()
            ->where('assigned_user_id', $user->id)
            ->where('status', ApprovalStepStatus::Pending)
            ->whereRaw(
                'approval_steps.step_order = (
                    select min(current_steps.step_order)
                    from approval_steps as current_steps
                    where current_steps.approval_id = approval_steps.approval_id
                    and current_steps.status = ?
                    and current_steps.deleted_at is null
                )',
                [ApprovalStepStatus::Pending->value]
            );
    }

    public function punchedInToday(User $user): int
    {
        $query = DailyAttendance::query()
            ->whereDate('attendance_date', today())
            ->whereNotNull('first_punch_in');

        if (! $this->userHasFullAccess($user)) {
            $visibleUserIds = PositionService::getVisibleUserIdsFor($user);

            $query->whereHas(
                'employee',
                fn (Builder $employeeQuery): Builder => $employeeQuery->whereIn('login_id', $visibleUserIds)
            );
        }

        return $query->count();
    }

    public function userHasFullAccess(User $user): bool
    {
        return $user->hasRole('super_admin')
            || $user->hasRole('administration_admin')
            || $user->can('AccessAllRecords');
    }
}
