<?php

namespace Database\Seeders;

use App\Models\AccountMaster;
use App\Models\Address;
use App\Models\City;
use App\Models\CityPinCode;
use App\Models\ContactDetail;
use Illuminate\Database\Seeder;

class AccountMasterSeeder extends Seeder
{
    protected int $batchSize = 5;

    public function run(): void
    {
        $this->seedAccounts(1);              // mixed
        $this->seedAccounts(40, 'customer');  // customers
    }

    protected function seedAccounts(int $total, ?string $state = null): void
    {
        $batches = ceil($total / $this->batchSize);

        for ($i = 1; $i <= $batches; $i++) {

            $remaining = $total - (($i - 1) * $this->batchSize);
            $count = min($this->batchSize, $remaining);

            if ($count <= 0) {
                break;
            }

            $factory = AccountMaster::factory()->count($count);

            if ($state) {
                $factory = $factory->{$state}();
            }

            // 🔹 CREATE SMALL BATCH
            $accounts = $factory->create();

            $this->attachAddresses($accounts);
            $this->attachContacts($accounts);

            // 🔹 FREE MEMORY
            unset($accounts);
            gc_collect_cycles();

            $this->command?->info(
                "Batch {$i}/{$batches}".($state ? " ({$state})" : '')
            );
        }
    }

    /**
     * Attach Billing & Shipping addresses to accounts
     */
    protected function attachAddresses($accounts): void
    {
        $allowedCities = ['Ahmedabad', 'Mahesana', 'Bhavnagar', 'Vadodara'];

        $cities = City::whereIn('name', $allowedCities)
            ->with('state', 'country')
            ->get();

        foreach ($accounts as $account) {

            $city = $cities->random();

            $pinCodeRecord = CityPinCode::where('city_id', $city->id)
                ->inRandomOrder()
                ->first();

            if (! $pinCodeRecord) {
                continue;
            }

            // 1. Create Billing Address (Set as Primary)
            Address::factory()
                ->forAccount($account)
                ->billing()
                ->state([
                    'city_id' => $city->id,
                    'state_id' => $city->state_id,
                    'country_id' => $city->country_id,
                    'pin_code' => $pinCodeRecord->pin_code,
                    'area_town_id' => $pinCodeRecord->id, // Linked to the pin code
                    'is_primary' => true,               // Mark billing as primary
                ])
                ->create();

            // 2. Create Shipping Address (Not Primary)
            Address::factory()
                ->forAccount($account)
                ->shipping()
                ->state([
                    'city_id' => $city->id,
                    'state_id' => $city->state_id,
                    'country_id' => $city->country_id,
                    'pin_code' => $pinCodeRecord->pin_code,
                    'area_town_id' => $pinCodeRecord->id, // Linked to the pin code
                    'is_primary' => false,
                ])
                ->create();
        }
    }

    /**
     * Attach Contact Details
     */
    protected function attachContacts($accounts): void
    {
        foreach ($accounts as $account) {

            $contacts = ContactDetail::factory()
                ->count(rand(1, 3))
                ->create();

            $account->contactDetails()->attach(
                $contacts->pluck('id')->all()
            );
        }
    }
}
