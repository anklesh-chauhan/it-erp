<?php

namespace App\Services\Reports;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Builder;

class LeadPipelineQuery
{
    public function builder(): Builder
    {
        return Lead::query()
            ->with(['owner', 'territory', 'status', 'accountMaster']);
    }
}
