<?php

namespace App\Services\Attendance;

use App\Models\LeaveRule;

class LeaveRuleEvaluatorService
{
    protected array $context;
    protected array $result;

    public function evaluate(array $context): array
    {
        $this->context = $context;

        $this->result = [
            'denied' => false,
            'errors' => [],
            'workflow' => [],
            'computation' => [],
            'notifications' => [],
            'visibility' => [],
            'payroll' => [],
        ];

        $rules = LeaveRule::query()
            ->where('is_active', true)
            ->orderBy('priority')
            ->get();

        foreach ($rules as $rule) {

            if (! $this->conditionsMatch($rule->condition_json)) {
                continue;
            }

            /**
             * 🔒 ENTERPRISE VALIDATION HOOK
             * Holiday / Weekoff / Duplicate leave check
             */
            if (
                $rule->category->key === 'validation'
                && ($rule->action_json['check_holiday'] ?? false)
            ) {
                if ($error = $this->validateDateAvailability($context)) {
                    $this->result['denied'] = true;
                    $this->result['errors'][] = $error;

                    // ⛔ Stop evaluating further rules
                    return $this->result;
                }
            }

            // Normal rule processing
            $this->applyAction(
                $rule->category->key,
                $rule->action_json
            );
        }

        return $this->result;
    }

    protected function validateDateAvailability(array $context): ?string
    {
        $employeeId = $context['employee_id'];
        $from = \Carbon\Carbon::parse($context['from_date']);
        $to = \Carbon\Carbon::parse($context['to_date']);

        foreach ($from->daysUntil($to) as $date) {

            // 1️⃣ Holiday check
            if (\App\Models\Holiday::whereDate('date', $date)->exists()) {
                return "Leave not allowed on holiday: {$date->toDateString()}";
            }

            // 2️⃣ Weekly off check
            if ($this->isWeeklyOff($employeeId, $date)) {
                return "Leave not allowed on weekly off: {$date->toDateString()}";
            }

            // 3️⃣ Existing leave check
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
        $day = $date->dayOfWeek; // 0 (Sun) → 6 (Sat)

        return \App\Models\WeekOff::where('is_active', true)
            ->where('day_of_week', $day)
            ->where(function ($q) use ($employeeId) {
                $q->whereNull('employee_id')
                ->orWhere('employee_id', $employeeId);
            })
            ->exists();
    }

    protected function conditionsMatch(array $conditions): bool
    {
        foreach ($conditions as $key => $expected) {

            if (! array_key_exists($key, $this->context)) {
                return false;
            }

            $actual = $this->context[$key];

            // Array condition (IN)
            if (is_array($expected)) {
                if (! in_array($actual, $expected)) {
                    return false;
                }
                continue;
            }

            // Date-based checks
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

    protected function applyAction(string $category, array $actions): void
    {
        match ($category) {
            'validation'   => $this->applyValidation($actions),
            'workflow'     => $this->applyWorkflow($actions),
            'computation'  => $this->applyComputation($actions),
            'notification' => $this->applyNotification($actions),
            'visibility'   => $this->applyVisibility($actions),
            'payroll'      => $this->applyPayroll($actions),
            default        => null,
        };
    }

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

    protected function applyNotification(array $actions): void
    {
        foreach ($actions as $key => $value) {
            $this->result['notifications'][$key] = $value;
        }
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


}
