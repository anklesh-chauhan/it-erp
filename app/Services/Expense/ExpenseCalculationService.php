<?php
namespace App\Services\Expense;

use App\Models\SalesDcr;
use App\Models\ExpenseConfiguration;

class ExpenseCalculationService
{
    public function calculateDcrTotal(SalesDcr $dcr): float
    {
        $total = 0;
        // Logic to fetch applicable configurations based on User Role, City, or Territory
        $configs = ExpenseConfiguration::where('is_active', true)
            ->where('effective_from', '<=', $dcr->dcr_date)
            ->get();

        foreach ($configs as $config) {
            $total += $this->applyConfiguration($config, $dcr);
        }

        return $total;
    }

    protected function applyConfiguration($config, $dcr)
    {
        // Example logic for 'per_visit' type
        if ($config->calculation_type === 'per_visit') {
            return $dcr->visits()->count() * $config->rate;
        }

        return 0;
    }
}
