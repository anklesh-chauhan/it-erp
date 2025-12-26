<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccountMaster;
use App\Models\Address;
use App\Models\ContactDetail;
use App\Models\City;
use App\Models\CityPinCode;

class AccountMasterSeeder extends Seeder
{
    protected int $batchSize = 500;

    public function run(): void
    {
        $this->seedAccounts(200);              // mixed
        $this->seedAccounts(200, 'customer');  // customers
        $this->seedAccounts(10, 'vendor');       // vendors
    }

    protected function seedAccounts(int $total, ?string $state = null): void
    {
        $batches = ceil($total / $this->batchSize);

        for ($i = 1; $i <= $batches; $i++) {

            $factory = AccountMaster::factory()->count($this->batchSize);

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
                "Batch {$i}/{$batches}" . ($state ? " ({$state})" : '')
            );
        }
    }

    /**
     * Attach Billing & Shipping addresses to accounts
     */
    protected function attachAddresses($accounts): void
    {
        $allowedCities = ['Ahmedabad', 'Mumbai', 'Bangalore'];

        $cities = City::whereIn('name', $allowedCities)
            ->with('state', 'country')
            ->get();

        foreach ($accounts as $account) {

            $city = $cities->random();

            $pinCode = CityPinCode::where('city_id', $city->id)
                ->inRandomOrder()
                ->first();

            if (! $pinCode) {
                continue;
            }

            Address::factory()
                ->forAccount($account)
                ->billing()
                ->state([
                    'city_id'    => $city->id,
                    'state_id'   => $city->state_id,
                    'country_id' => $city->country_id,
                    'pin_code'   => $pinCode->pin_code,
                    'area_town' => $pinCode->area_town,
                ])
                ->create();

            Address::factory()
                ->forAccount($account)
                ->shipping()
                ->state([
                    'city_id'    => $city->id,
                    'state_id'   => $city->state_id,
                    'country_id' => $city->country_id,
                    'pin_code'   => $pinCode->pin_code,
                    'area_town' => $pinCode->area_town,
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

