<?php

namespace Database\Factories;

use App\Models\AccountMaster;
use App\Models\User;
use App\Models\TypeMaster;
use App\Models\IndustryType;
use App\Models\Region;
use App\Models\ContactDetail;
use App\Models\RatingType;
use App\Models\AccountOwnership;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountMasterFactory extends Factory
{
    protected $model = AccountMaster::class;

    public function definition(): array
    {
        return [
            'owner_id'            => User::factory(10), // create a user unless overridden
            'type_master_id'      => TypeMaster::factory(10), 
            'name'                => $this->faker->company(),
            'account_code'        => null,
            'phone_number'        => $this->faker->phoneNumber(),
            'email'               => $this->faker->safeEmail(),
            'secondary_email'     => $this->faker->safeEmail(),
            'website'             => $this->faker->url(),
            'no_of_employees'     => $this->faker->numberBetween(5, 5000),
            'twitter'             => 'https://twitter.com/' . $this->faker->userName(),
            'linked_in'           => 'https://linkedin.com/in/' . $this->faker->userName(),
            'annual_revenue'      => $this->faker->numberBetween(100000, 50000000),
            'sic_code'            => $this->faker->randomNumber(4),
            'ticker_symbol'       => strtoupper($this->faker->lexify('???')),
            'description'         => $this->faker->paragraph(),
            'industry_type_id'    => IndustryType::factory(10),
            'region_id'           => Region::factory(1),
            'ref_dealer_contact'  => null,
            'commission'          => $this->faker->randomFloat(2, 0, 50), // %
            'alias'               => $this->faker->word(),
            'parent_id'           => null, // can be linked manually if needed
            'rating_type_id'      => RatingType::factory(10),
            'account_ownership_id'=> null,
        ];
    }
}
