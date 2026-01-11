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
            if ($this->conditionsMatch($rule->condition_json)) {
                $this->applyAction(
                    $rule->category->key,
                    $rule->action_json
                );
            }
        }

        return $this->result;
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
