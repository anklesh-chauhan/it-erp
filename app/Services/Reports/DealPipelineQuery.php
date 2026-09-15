<?php

namespace App\Services\Reports;

use App\Models\Deal;
use Illuminate\Database\Eloquent\Builder;

class DealPipelineQuery
{
    public function builder(): Builder
    {
        return Deal::query()
            ->with(['owner', 'status', 'accountMaster']);
    }
}
