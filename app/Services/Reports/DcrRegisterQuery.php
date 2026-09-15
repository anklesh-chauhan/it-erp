<?php

namespace App\Services\Reports;

use App\Models\SalesDcr;
use Illuminate\Database\Eloquent\Builder;

class DcrRegisterQuery
{
    public function builder(): Builder
    {
        return SalesDcr::query()
            ->with(['user', 'territory']);
    }
}
