<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Address;
use App\Models\TypeMaster;
use App\Models\City;
use App\Models\CityPinCode;
use App\Models\State;
use App\Models\Country;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {

        $pin = CityPinCode::with(['city', 'state', 'country'])
            ->inRandomOrder()
            ->first();

        return [
            'company_id' => null,
            'contact_detail_id' => null,

            // // 🔹 Address Type (Billing / Shipping / etc.)
            // 'type_master_id' => TypeMaster::query()
            //     ->ofType(Address::class)
            //     ->inRandomOrder()
            //     ->value('id'),

            'street' => $this->faker->streetAddress(),

            // 🔥 Derived from CityPinCode
            'area_town' => $pin?->area_town,
            'pin_code' => $pin?->pin_code,
            'city_id' => $pin?->city_id,
            'state_id' => $pin?->state_id,
            'country_id' => $pin?->country_id,

            'sort' => 1,

            // Polymorphic owner (set via state)
            'addressable_id' => null,
            'addressable_type' => null,
        ];
    }

    /* =====================================================
     | STATES
     ===================================================== */

    public function forAccount($account)
    {
        return $this->state(fn () => [
            'addressable_id' => $account->id,
            'addressable_type' => \App\Models\AccountMaster::class,
        ]);
    }

    public function billing()
    {
        return $this->state(fn () => [
            'type_master_id' => TypeMaster::firstOrCreate([
                'name' => 'Billing Address',
                'typeable_type' => Address::class,
            ])->id,
        ]);
    }

    public function shipping()
    {
        return $this->state(fn () => [
            'type_master_id' => TypeMaster::firstOrCreate([
                'name' => 'Shipping Address',
                'typeable_type' => Address::class,
            ])->id,
        ]);
    }
}
