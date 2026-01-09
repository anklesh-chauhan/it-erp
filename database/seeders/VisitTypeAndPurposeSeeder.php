<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VisitType;
use App\Models\VisitPurpose;
use App\Enums\TourPurpose;
use Illuminate\Support\Str;

class VisitTypeAndPurposeSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Define Visit Types (stable, few)
        |--------------------------------------------------------------------------
        */
        $visitTypes = [
            'sales_activity' => 'Sales Activity',
            'field_admin_activity' => 'Field / Admin Activity',
            'other' => 'Other',
        ];

        $visitTypeModels = [];

        foreach ($visitTypes as $code => $name) {
            $visitTypeModels[$code] = VisitType::updateOrCreate(
                ['code' => $code],
                [
                    'name'        => $name,
                    'description' => $name,
                    'is_active'   => true,
                    'sort_order'  => array_search($code, array_keys($visitTypes)) + 1,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Map Enum Groups → Visit Types
        |--------------------------------------------------------------------------
        */
        $groupToVisitType = [
            'Sales Activities'       => 'sales_activity',
            'Field / Admin Activities' => 'field_admin_activity',
            'Other'                  => 'other',
        ];

        /*
        |--------------------------------------------------------------------------
        | 3. Seed Visit Purposes from Enum
        |--------------------------------------------------------------------------
        */
        foreach (TourPurpose::groupedOptions() as $groupLabel => $purposes) {

            $visitTypeCode = $groupToVisitType[$groupLabel] ?? 'other';
            $visitType     = $visitTypeModels[$visitTypeCode];

            $sort = 1;

            foreach ($purposes as $code => $label) {
                VisitPurpose::updateOrCreate(
                    ['code' => $code],
                    [
                        'name'          => $label,
                        'description'   => $label,
                        'visit_type_id' => $visitType->id,
                        'is_active'     => true,
                        'sort_order'    => $sort++,
                    ]
                );
            }
        }
    }
}
