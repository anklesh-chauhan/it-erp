<?php

namespace App\Services\Visit;

use App\Models\SalesDcr;
use Illuminate\Support\Facades\Auth;

class DcrService
{
    public function getOrCreateForDate(string $date): SalesDcr
    {
        $user = Auth::user();

        return SalesDcr::firstOrCreate(
            [
                'user_id' => $user->id,
                'dcr_date' => $date,
            ],
            [
                'status' => 'draft',
                'territory_id' => $user->territory_id ?? null,
            ]
        );
    }
}
