<?php

namespace Database\Factories;

use App\Models\AccountMaster;
use App\Models\Address;
use App\Models\CityPinCode;
use App\Models\State;
use App\Models\TypeMaster;
use Illuminate\Database\Eloquent\Factories\Factory;

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

            'street' => $this->faker->streetAddress(),

            // 🔥 Derived from CityPinCode
            'area_town_id' => $pin?->id,
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
            'addressable_type' => AccountMaster::class,
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
