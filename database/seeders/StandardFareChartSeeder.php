<?php

namespace Database\Seeders;

use App\Models\Patch;
use App\Models\StandardFareChart;
use App\Models\TypeMaster;
use Illuminate\Database\Seeder;
use Illuminate\Validation\ValidationException;

class StandardFareChartSeeder extends Seeder
{
    public function run(): void
    {
        // 🔹 Types
        $hqType = TypeMaster::where('name', 'Local / HQ')->first()?->id ?? 1;
        $exStationType = TypeMaster::where('name', 'Intra-District / Ex-Station / Ex-HQ')->first()?->id ?? 2;
        $outStationType = TypeMaster::where('name', 'Out-Station')->first()?->id ?? 3;

        // 🔹 Keep track of processed pairs in this run to avoid duplicate validation triggers
        $processedPairs = [];

        $patches = Patch::with('territory.cityPinCodes')->get();

        foreach ($patches as $patch) {
            $territory = $patch->territory;
            $originId = $patch->city_pin_code_id;

            if (! $originId) {
                $this->command->warn("Patch missing HQ: {$patch->name}");

                continue;
            }

            $pincodes = $territory->cityPinCodes;

            foreach ($pincodes as $pincode) {
                // Normalize pair
                [$fromId, $toId] = StandardFareChart::normalizeCityPair($originId, $pincode->id);

                // Create a unique key for this loop (Pair + Territory)
                // We include territory_id because your error message mentions "territory scope"
                $uniqueKey = "{$fromId}-{$toId}-{$territory->id}";

                if (isset($processedPairs[$uniqueKey])) {
                    continue; // Skip if already handled in this session
                }

                $km = $this->calculateDistance($fromId, $toId);

                // 🔹 Type logic
                $type = $hqType;
                if ($km > 25 && $km <= 80) {
                    $type = $exStationType;
                }
                if ($km > 80) {
                    $type = $outStationType;
                }

                $fare = $this->calculateFare($km, $type);

                try {
                    StandardFareChart::updateOrCreate(
                        [
                            'from_area_town_id' => $fromId,
                            'to_area_town_id' => $toId,
                            'territory_id' => $territory->id,
                        ],
                        [
                            'patch_id' => $patch->id, // Moved to update section to avoid constraint hits
                            'distance_km' => $km,
                            'fare_amount' => $fare,
                            'is_active' => true,
                            'type_master_id' => $type,
                        ]
                    );

                    $processedPairs[$uniqueKey] = true;
                } catch (ValidationException $e) {
                    $this->command->warn("Skipped duplicate via Model Validation: $uniqueKey");
                }
            }

            $this->command->info("SFC processed for patch: {$patch->name}");
        }
    }

    protected function calculateDistance(int $fromId, int $toId): int
    {
        return abs($fromId - $toId) % 120;
    }

    protected function calculateFare(int $km, int $type): float
    {
        return match ($type) {
            1 => max(20, $km * 2),
            2 => $km * 3,
            3 => $km * 5,
            default => $km * 2,
        };
    }
}
