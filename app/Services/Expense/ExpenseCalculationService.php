<?php

namespace App\Services\Expense;

use App\ExpenseConfigurationMatcher;
use App\Models\ExpenseConfiguration;
use App\Models\SalesDcr;
use App\Models\SalesDcrExpense;
use App\Models\Visit;

class ExpenseCalculationService
{
    public function __construct(
        protected ExpenseConfigurationMatcher $matcher
    ) {}

    /**
     * Recalculate and persist all auto-calculated expenses for a DCR.
     */
    public function autoCalculateDcrExpenses(SalesDcr $dcr): void
    {
        $context = $this->buildContextFromDcr($dcr);

        $configs = $this->matcher->getApplicableConfigurations($dcr, context: $context);

        if ($configs->isEmpty()) {
            $dcr->recalculateTotalExpense();

            return;
        }

        $dcr->expenses()->autoCalculated()->delete();

        $expenses = $configs->map(function (ExpenseConfiguration $config) use ($dcr, $context) {
            $calculated = $this->calculateExpense($config, $dcr, $context);

            if ($calculated['amount'] <= 0) {
                return null;
            }

            return new SalesDcrExpense([
                'sales_dcr_id' => $dcr->id,
                'expense_type_id' => $config->expense_type_id,
                'transport_mode_id' => $config->transport_mode_id,
                'quantity' => $calculated['quantity'],
                'rate' => $calculated['rate'],
                'amount' => $calculated['amount'],
                'is_auto_calculated' => true,
                'meta' => $calculated['meta'],
            ]);
        })->filter();

        if ($expenses->isNotEmpty()) {
            $dcr->expenses()->saveMany($expenses->values()->all());
        }

        $dcr->recalculateTotalExpense();
    }

    /**
     * Calculate a single expense for a DCR/configuration pair.
     *
     * @param  array<string, mixed>  $context
     * @return array{amount: float, quantity: float, rate: float, meta: array<string, mixed>}
     */
    public function calculateExpense(ExpenseConfiguration $config, SalesDcr $dcr, array $context = []): array
    {
        $quantity = 0.0;
        $rate = (float) ($config->rate ?? 0);

        switch ($config->calculation_type) {
            case 'fixed':
                $quantity = 1.0;

                break;
            case 'per_km':
                $distance = (float) ($context['distance'] ?? $dcr->distance_covered ?? 0);
                $quantity = $distance;

                break;
            case 'per_day':
                $quantity = 1.0;

                break;
            case 'per_visit':
                $visitCount = (float) ($context['visit_count'] ?? $dcr->visits_count ?? $dcr->SalesDcrVisits()->count());
                $quantity = $visitCount;

                break;
            case 'manual':
            default:
                return [
                    'amount' => 0.0,
                    'quantity' => 0.0,
                    'rate' => $rate,
                    'meta' => [
                        'calculation_type' => $config->calculation_type,
                        'config_id' => $config->id,
                    ],
                ];
        }

        $amount = $quantity * $rate;

        if ($config->min_amount !== null) {
            $amount = max($amount, (float) $config->min_amount);
        }

        if ($config->max_amount !== null) {
            $amount = min($amount, (float) $config->max_amount);
        }

        return [
            'amount' => round($amount, 2),
            'quantity' => round($quantity, 2),
            'rate' => $rate,
            'meta' => [
                'calculation_type' => $config->calculation_type,
                'config_id' => $config->id,
                'context' => $context,
            ],
        ];
    }

    /**
     * Calculate expense for a specific visit based on configuration.
     *
     * @param  array<string, mixed>  $context
     * @return array{amount: float, quantity: float, rate: float, meta: array<string, mixed>}
     */
    public function calculateExpenseForVisit(Visit $visit, ExpenseConfiguration $config, array $context = []): array
    {
        $baseContext = array_merge($this->buildContextFromVisit($visit), $context);

        return $this->calculateExpense($config, $visit->salesDcr, $baseContext);
    }

    /**
     * @return array<string, mixed>
     */
    protected function buildContextFromDcr(SalesDcr $dcr): array
    {
        return [
            'visit_count' => $dcr->visits_count ?? $dcr->visits()->count(),
            'distance' => $dcr->distance_covered,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function buildContextFromVisit(Visit $visit): array
    {
        return [
            'visit_count' => 1,
            'distance' => $visit->salesDcr?->distance_covered,
        ];
    }
}
