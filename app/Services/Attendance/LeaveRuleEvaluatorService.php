<?php

namespace App\Services\Attendance;

use App\Models\LeaveRule;
use LogicException;

class LeaveRuleEvaluatorService
{
    protected array $context = [];
    protected array $result = [];

    public function evaluate(array $context): array
    {
        $this->context = $context;

        $this->result = [
            'denied'        => false,
            'errors'        => [],
            'workflow'      => [],
            'computation'   => [],
            'notifications' => [],
            'visibility'    => [],
            'payroll'       => [],
        ];

        $rules = LeaveRule::query()
            ->where('is_active', true)
            ->orderBy('priority')
            ->get();

        foreach ($rules as $rule) {

            // 1️⃣ Rule key must match current event (if provided)
            if (
                isset($this->context['event'])
                && $rule->rule_key !== $this->context['event']
            ) {
                continue;
            }

            // 2️⃣ Condition match
            if (! $this->conditionsMatch($rule->condition_json ?? [])) {
                continue;
            }

            /**
             * 🔒 ENTERPRISE VALIDATION HOOK
             */
            if (
                $rule->category->key === 'validation'
                && ($rule->action_json['check_holiday'] ?? false)
            ) {
                if ($error = $this->validateDateAvailability($context)) {
                    $this->result['denied'] = true;
                    $this->result['errors'][] = $error;
                    return $this->result;
                }
            }

            // 3️⃣ Apply category action
            $this->applyAction(
                category: $rule->category->key,
                ruleKey: $rule->rule_key,
                actions: $rule->action_json ?? []
            );
        }

        return $this->result;
    }

    /* =====================================================
     | Condition Evaluation
     ===================================================== */

    protected function conditionsMatch(array $conditions): bool
    {
        foreach ($conditions as $key => $expected) {

            if (! array_key_exists($key, $this->context)) {
                return false;
            }

            $actual = $this->context[$key];

            // IN condition
            if (is_array($expected)) {
                if (! in_array($actual, $expected, true)) {
                    return false;
                }
                continue;
            }

            // Date semantic checks
            if ($expected === 'future' && now()->parse($actual)->isPast()) {
                return false;
            }

            if ($expected === 'past' && now()->parse($actual)->isFuture()) {
                return false;
            }

            if ($actual != $expected) {
                return false;
            }
        }

        return true;
    }

    /* =====================================================
     | Action Dispatch
     ===================================================== */

    protected function applyAction(
        string $category,
        string $ruleKey,
        array $actions
    ): void {
        match ($category) {
            'validation'   => $this->applyValidation($actions),
            'workflow'     => $this->applyWorkflow($actions),
            'computation'  => $this->applyComputation($actions),
            'notification' => $this->applyNotification($ruleKey, $actions),
            'visibility'   => $this->applyVisibility($actions),
            'payroll'      => $this->applyPayroll($actions),
            default        => null,
        };
    }

    /* =====================================================
     | Category Handlers
     ===================================================== */

    protected function applyValidation(array $actions): void
    {
        if (($actions['deny'] ?? false) === true) {
            $this->result['denied'] = true;
            $this->result['errors'][] =
                $actions['message'] ?? 'Leave rule violation';
        }
    }

    protected function applyWorkflow(array $actions): void
    {
        foreach ($actions as $key => $value) {
            $this->result['workflow'][$key] = $value;
        }
    }

    protected function applyComputation(array $actions): void
    {
        foreach ($actions as $key => $value) {
            $this->result['computation'][$key] = $value;
        }
    }

    /**
     * ✅ FINAL, CORRECT NOTIFICATION HANDLER
     */
    protected function applyNotification(
        string $ruleKey,
        array $actions
    ): void {

        $this->result['notifications'][] = [
            'event'              => $ruleKey,
            'send_email'         => (bool) ($actions['send_email'] ?? false),
            'send_sms'           => (bool) ($actions['send_sms'] ?? false),
            'recipient'          => $actions['recipient'] ?? null,
            'email_action_links' => (bool) ($actions['email_action_links'] ?? false),
        ];
    }

    protected function applyVisibility(array $actions): void
    {
        foreach ($actions as $key => $value) {
            $this->result['visibility'][$key] = $value;
        }
    }

    protected function applyPayroll(array $actions): void
    {
        foreach ($actions as $key => $value) {
            $this->result['payroll'][$key] = $value;
        }
    }

    /* =====================================================
     | Validation Helpers (unchanged)
     ===================================================== */

    protected function validateDateAvailability(array $context): ?string
    {
        $employeeId = $context['employee_id'];
        $from = \Carbon\Carbon::parse($context['from_date']);
        $to = \Carbon\Carbon::parse($context['to_date']);

        foreach ($from->daysUntil($to) as $date) {

            if (\App\Models\Holiday::whereDate('date', $date)->exists()) {
                return "Leave not allowed on holiday: {$date->toDateString()}";
            }

            if ($this->isWeeklyOff($employeeId, $date)) {
                return "Leave not allowed on weekly off: {$date->toDateString()}";
            }

            if ($this->leaveAlreadyExists($employeeId, $date)) {
                return "Leave already applied for: {$date->toDateString()}";
            }
        }

        return null;
    }

    protected function leaveAlreadyExists(int $employeeId, \Carbon\Carbon $date): bool
    {
        return \App\Models\LeaveInstance::where('employee_id', $employeeId)
            ->whereDate('date', $date)
            ->whereIn('approval_status', ['applied', 'approved'])
            ->exists();
    }

    protected function isWeeklyOff(int $employeeId, \Carbon\Carbon $date): bool
    {
        return \App\Models\WeekOff::where('is_active', true)
            ->where('day_of_week', $date->dayOfWeek)
            ->where(fn ($q) =>
                $q->whereNull('employee_id')
                  ->orWhere('employee_id', $employeeId)
            )
            ->exists();
    }
}
