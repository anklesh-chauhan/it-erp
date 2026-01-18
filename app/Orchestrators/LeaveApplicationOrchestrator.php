<?php

namespace App\Orchestrators;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use App\Services\Attendance\{
    LeaveDateValidator,
    LeaveRuleEvaluatorService,
    LeaveBalanceCalculator,
    LeaveWorkflowService,
    LeaveNotificationService
};
use App\Models\{
    LeaveApplication,
    LeaveInstance,
    LeaveType
};

class LeaveApplicationOrchestrator
{
    /**
     * Apply for leave
     */
    public function apply(array $data): LeaveApplication
    {
        return DB::transaction(function () use ($data) {

            $leaveType = LeaveType::where('code', $data['leave_type_code'])->firstOrFail();

            // 1️⃣ Build rule context
            $context = $this->buildContext($data, $leaveType);

            // 2️⃣ Rule evaluation
            $ruleResult = app(LeaveRuleEvaluatorService::class)->evaluate($context);

            if ($ruleResult['denied']) {
                throw ValidationException::withMessages([
                    'leave' => $ruleResult['errors'],
                ]);
            }

            // 3️⃣ Balance check
            $balance = app(LeaveBalanceCalculator::class)->calculate(
                employeeId: $data['employee_id'],
                leaveTypeId: $leaveType->id,
                asOnDate: Carbon::parse($data['from_date']),
                usePayrollSnapshot: $ruleResult['payroll']['use_payroll_snapshot'] ?? false
            );

            if ($balance['closing'] < $data['days']) {
                throw ValidationException::withMessages([
                    'leave' => ['Insufficient leave balance'],
                ]);
            }

            // 4️⃣ Create leave application (authoritative record)
            $application = LeaveApplication::create([
                'employee_id' => $data['employee_id'],
                'leave_type_id' => $leaveType->id,
                'from_date' => $data['from_date'],
                'to_date' => $data['to_date'],
                'is_half_day' => $data['is_half_day'] ?? false,
                'half_day_type' => $data['half_day_type'] ?? null,
                'reason' => $data['reason'] ?? null,
                'status' => 'applied',
                'approval_status' => 'draft',

            ]);

            // Validate dates (duplicate / holiday / weekoff)
            app(LeaveDateValidator::class)->validate(
                employeeId: $application->employee_id,
                from: Carbon::parse($application->from_date),
                to: Carbon::parse($application->to_date)
            );

            // 5️⃣ Generate leave instances
            $this->generateInstances($application, $ruleResult);

            // 6️⃣ Start workflow (this sends notifications internally)
            app(LeaveWorkflowService::class)->start(
                $application,
                $ruleResult
            );

            // 7️⃣ Dispatch notifications USING evaluated rules
            app(LeaveNotificationService::class)
                ->dispatch('LEAVE_APPLIED', $application, $ruleResult);

            return $application;
        });
    }

    /**
     * Cancel / revoke leave
     */
    public function cancel(int $applicationId, int $userId): void
    {
        DB::transaction(function () use ($applicationId, $userId) {

            $application = LeaveApplication::findOrFail($applicationId);

            // Rule evaluation for cancellation
            $ruleResult = app(LeaveRuleEvaluatorService::class)->evaluate([
                'event' => 'LEAVE_CANCEL',
                'future_leave' => now()->lt($application->from_date),
                'role' => 'employee',
            ]);

            // Auto-approved cancellation
            if ($ruleResult['workflow']['auto_approve_cancel'] ?? false) {

                $application->update([
                    'approval_status' => 'cancelled',
                    'revoked_at' => now(),
                ]);

                $application->instances()
                    ->update(['approval_status' => 'cancelled']);

                app(LeaveNotificationService::class)
                    ->dispatch('LEAVE_CANCELLED', $application);

                return;
            }

            /**
             * If cancellation approval is required:
             * Treat cancellation as a new approval workflow
             */
            $application->update(['approval_status' => 'pending_cancel']);

            app(LeaveWorkflowService::class)->start(
                $application,
                $ruleResult
            );

            app(LeaveNotificationService::class)
                ->dispatch('LEAVE_CANCEL_REQUESTED', $application);
        });
    }

    /**
     * Build rule evaluation context
     */
    protected function buildContext(array $data, LeaveType $leaveType): array
    {
        return [
            'employee_id' => $data['employee_id'],
            'leave_type_code' => $leaveType->code,
            'from_date' => $data['from_date'],
            'to_date' => $data['to_date'],
            'days' => $data['days'],
            'is_half_day' => $data['is_half_day'] ?? false,
            'half_day_type' => $data['half_day_type'] ?? null,
            'previous_day_leave' => $data['previous_day_leave'] ?? null,
            'attendance' => $data['attendance'] ?? null,
            'notice_period' => $data['notice_period'] ?? false,
            'event' => 'LEAVE_APPLIED',
            'role' => 'employee',
            'future_leave' => Carbon::parse($data['from_date'])->isFuture(),
        ];
    }

    /**
     * Generate date-wise leave instances
     */
    protected function generateInstances(
        LeaveApplication $application,
        array $ruleResult
    ): void {

        $payFactor = $ruleResult['computation']['pay_factor'] ?? 1;

        foreach (
            Carbon::parse($application->from_date)
                ->daysUntil($application->to_date)
            as $date
        ) {
            LeaveInstance::create([
                'leave_application_id' => $application->id,
                'employee_id' => $application->employee_id,
                'leave_type_id' => $application->leave_type_id,
                'date' => $date,
                'pay_factor' => $payFactor,
                'approval_status' => 'applied',
            ]);
        }
    }
}
