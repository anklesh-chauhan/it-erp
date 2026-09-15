<?php

namespace App\Services\Reports;

use App\Models\InventoryMovement;
use Illuminate\Database\Eloquent\Builder;

class StockMovementRegisterQuery
{
    public function builder(): Builder
    {
        return InventoryMovement::query()
            ->with(['item', 'location']);
    }
}
