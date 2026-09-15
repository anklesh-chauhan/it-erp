<?php

namespace App\Services\Reports;

use App\Models\SalesDcrExpense;
use Illuminate\Database\Eloquent\Builder;

class ExpenseSummaryQuery
{
    public function builder(): Builder
    {
        return SalesDcrExpense::query()
            ->with(['expenseType', 'salesDcr.user', 'salesDcr.territory'])
            ->whereHas(
                'salesDcr',
                fn (Builder $query): Builder => $query->applyVisibility('SalesDcr')
            );
    }
}
