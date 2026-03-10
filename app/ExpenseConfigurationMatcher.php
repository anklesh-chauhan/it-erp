<?php

namespace App;

use App\Models\ExpenseConfiguration;
use App\Models\SalesDcr;
use Illuminate\Database\Eloquent\Collection;

class ExpenseConfigurationMatcher
{
    /**
     * @param  array<string, mixed>  $context
     */
    public function getApplicableConfigurations(
        SalesDcr $dcr,
        ?int $expenseTypeId = null,
        ?int $transportModeId = null,
        array $context = []
    ): Collection {
        $query = ExpenseConfiguration::query()
            ->with('conditions')
            ->where('is_active', true)
            ->whereDate('effective_from', '<=', $dcr->dcr_date)
            ->where(function ($q) use ($dcr) {
                $q->whereNull('effective_to')
                    ->orWhereDate('effective_to', '>=', $dcr->dcr_date);
            });

        if ($expenseTypeId !== null) {
            $query->where('expense_type_id', $expenseTypeId);
        }

        if ($transportModeId !== null) {
            $query->where('transport_mode_id', $transportModeId);
        }

        if ($dcr->territory_id !== null) {
            $query->where(function ($q) use ($dcr) {
                $q->whereNull('territory_id')
                    ->orWhere('territory_id', $dcr->territory_id);
            });
        }

        $configs = $query->get();

        if ($configs->isEmpty()) {
            return $configs;
        }

        $facts = $this->buildFacts($dcr, $context);

        return $configs->filter(function (ExpenseConfiguration $config) use ($facts) {
            if ($config->conditions->isEmpty()) {
                return true;
            }

            foreach ($config->conditions as $condition) {
                if (! $this->matchesCondition($condition->condition_key, $condition->operator, $condition->value, $facts)) {
                    return false;
                }
            }

            return true;
        });
    }

    /**
     * @param  array<string, mixed>  $context
     * @return array<string, mixed>
     */

    protected function buildFacts(SalesDcr $dcr, array $context): array
    {
        $dcr->loadMissing('user', 'visits');

        $facts = [];

        /*
        |--------------------------------------------------------------------------
        | Core Visit Aggregates
        |--------------------------------------------------------------------------
        */

        $facts['visit_count'] = $dcr->visits_count
            ?? $dcr->visits->count();

        $facts['joint_work'] = $dcr->visits
            ->where('is_joint_work', true)
            ->isNotEmpty();

        $facts['distance'] = $dcr->distance_covered ?? 0;

        /*
        |--------------------------------------------------------------------------
        | Role & User Facts
        |--------------------------------------------------------------------------
        */

        $facts['role_id'] = $dcr->user?->role_id;
        $facts['user_id'] = $dcr->user_id;

        /*
        |--------------------------------------------------------------------------
        | Territory / Location Facts
        |--------------------------------------------------------------------------
        */

        $facts['territory_id'] = $dcr->territory_id;
        $facts['city_id'] = $dcr->city_id ?? null;

        /*
        |--------------------------------------------------------------------------
        | Date-Based Facts
        |--------------------------------------------------------------------------
        */

        $facts['day_of_week'] = $dcr->dcr_date?->format('l');
        $facts['month'] = $dcr->dcr_date?->format('m');

        /*
        |--------------------------------------------------------------------------
        | Business Logic Facts
        |--------------------------------------------------------------------------
        */

        $facts['outstation'] = $this->isOutstation($dcr);

        /*
        |--------------------------------------------------------------------------
        | Merge Extra Runtime Context
        |--------------------------------------------------------------------------
        */

        return array_merge($facts, $context);
    }

    protected function isOutstation(SalesDcr $dcr): bool
    {
        if (! $dcr->user || ! $dcr->territory_id) {
            return false;
        }

        return $dcr->territory_id !== $dcr->user->territory_id;
    }

    /**
     * @param  array<string, mixed>  $facts
     */
    protected function matchesCondition(string $key, string $operator, string $value, array $facts): bool
    {
        if (! array_key_exists($key, $facts)) {
            return false;
        }

        $factValue = $facts[$key];

        if (is_bool($factValue)) {
            $expected = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

            return $expected === null ? false : $factValue === $expected;
        }

        if (is_numeric($factValue) && is_numeric($value)) {
            $factNumber = (float) $factValue;
            $expectedNumber = (float) $value;

            return match ($operator) {
                '=', '==' => $factNumber === $expectedNumber,
                '!=', '<>' => $factNumber !== $expectedNumber,
                '>' => $factNumber > $expectedNumber,
                '>=' => $factNumber >= $expectedNumber,
                '<' => $factNumber < $expectedNumber,
                '<=' => $factNumber <= $expectedNumber,
                default => false,
            };
        }

        return match ($operator) {
            '=', '==' => (string) $factValue === $value,
            '!=', '<>' => (string) $factValue !== $value,
            default => false,
        };
    }
}
