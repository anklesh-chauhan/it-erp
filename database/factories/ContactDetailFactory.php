<?php

namespace Database\Factories;

use App\Models\ContactDetail;
use App\Models\Company;
use App\Models\Designation;
use App\Models\Department;
use App\Models\AccountMaster;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactDetailFactory extends Factory
{
    protected $model = ContactDetail::class;

    public function definition(): array
    {
        return [
            'company_id'        => Company::factory(),   // or null if not always linked
            'salutation'        => $this->faker->randomElement(['Mr.', 'Ms.', 'Mrs.', 'Dr.']),
            'first_name'        => $this->faker->firstName(),
            'last_name'         => $this->faker->lastName(),
            'birthday'          => $this->faker->optional()->date(),
            'email'             => $this->faker->unique()->safeEmail(),
            'mobile_number'     => $this->faker->phoneNumber(),
            'whatsapp_number'   => $this->faker->optional()->phoneNumber(),
            'alternate_phone'   => $this->faker->optional()->phoneNumber(),
            'designation_id'    => Designation::factory(),
            'department_id'     => Department::factory(),
            'linkedin'          => 'https://linkedin.com/in/' . $this->faker->userName(),
            'facebook'          => 'https://facebook.com/' . $this->faker->userName(),
            'twitter'           => 'https://twitter.com/' . $this->faker->userName(),
            'website'           => $this->faker->optional()->url(),
            'notes'             => $this->faker->optional()->sentence(),
            'contactable_type'  => null,  // polymorphic, can be overridden
            'contactable_id'    => null,  // polymorphic, can be overridden
            'account_master_id' => AccountMaster::factory(),
        ];
    }
}
