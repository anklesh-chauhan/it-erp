<?php

namespace App\Services\Visit;

use App\Models\SalesDcr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DcrService
{
    public function getOrCreateForDate(Carbon|string $date): SalesDcr
    {
        $user = Auth::user();
        $dcrDate = $date instanceof Carbon ? $date->toDateString() : $date;

        return SalesDcr::firstOrCreate(
            [
                'user_id' => $user->id,
                'dcr_date' => $dcrDate,
            ],
            [
                'approval_status' => 'draft',
                'territory_id' => $user->territory_id ?? null,
            ]
        );
    }
}
