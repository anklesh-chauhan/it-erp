<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\AccountMaster;
use App\Models\IndustryType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'name'             => $this->faker->company(),
            'phone_number'     => $this->faker->phoneNumber(),
            'email'            => $this->faker->unique()->safeEmail(),
            'secondary_email'  => $this->faker->optional()->safeEmail(),
            'no_of_employees'  => $this->faker->numberBetween(5, 5000),
            'industry_type_id' => IndustryType::factory(),
            'twitter'          => 'https://twitter.com/' . $this->faker->userName(),
            'linked_in'        => 'https://linkedin.com/in/' . $this->faker->userName(),
            'website'          => $this->faker->optional()->url(),
            'description'      => $this->faker->optional()->paragraph(),
            'account_master_id'=> AccountMaster::factory(),
        ];
    }
}
