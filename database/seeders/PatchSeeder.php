<?php

namespace Database\Seeders;

use App\Models\AccountMaster;
use App\Models\Patch;
use App\Models\Territory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class PatchSeeder extends Seeder
{
    protected int $patchPerTerritory = 2; // 4 cities → 8 patches

    public function run(): void
    {
        $territories = Territory::with('cityPinCodes')->get();

        foreach ($territories as $territory) {

            $pincodeIds = $territory->cityPinCodes->pluck('id');

            if ($pincodeIds->isEmpty()) {
                $this->command->warn("No pincodes for {$territory->name}");

                continue;
            }

            // 🔹 Get ALL accounts in this territory (MAX utilization)
            $accounts = AccountMaster::whereHas('addresses', function ($q) use ($pincodeIds) {
                $q->whereIn('area_town_id', $pincodeIds);
            })->get();

            if ($accounts->isEmpty()) {
                $this->command->warn("No accounts for {$territory->name}");

                continue;
            }

            // 🔹 Split accounts into 2 balanced groups
            $chunks = $accounts->chunk(
                ceil($accounts->count() / $this->patchPerTerritory)
            );

            foreach ($chunks as $index => $chunk) {

                $patch = Patch::create([
                    'name' => "{$territory->name} - Patch ".($index + 1),
                    'code' => strtoupper(substr($territory->name, 0, 3)).'-'.($index + 1),
                    'territory_id' => $territory->id,
                    'city_pin_code_id' => $pincodeIds->first(),
                    'description' => 'Balanced patch with max account utilization',
                    'color' => fake()->hexColor(),
                ]);

                $this->attachAccounts($patch, $chunk);

                $this->command->info(
                    "{$patch->name} → {$chunk->count()} accounts"
                );
            }
        }
    }

    protected function attachAccounts(Patch $patch, Collection $accounts): void
    {
        $sequence = 1;

        foreach ($accounts as $account) {
            $patch->companies()->attach($account->id, [
                'sequence_no' => $sequence++,
                'distance_km' => rand(1, 25), // future: geo distance
            ]);
        }
    }
}
