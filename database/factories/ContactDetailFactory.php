<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ContactDetail;
use App\Models\Designation;
use App\Models\Department;
use App\Models\Company;
use App\Models\AccountMaster;

class ContactDetailFactory extends Factory
{
    protected $model = ContactDetail::class;

    public function definition(): array
    {
        $mobile = $this->faker->numerify('9#########');

        return [
            'company_id' => null,

            'salutation' => $this->faker->randomElement([
                'Mr.', 'Mrs.', 'Ms.', 'Dr.', 'Prof.', 'Er.', 'Other',
            ]),

            'first_name' => $this->faker->firstName(),
            'last_name'  => $this->faker->lastName(),

            'birthday' => $this->faker->optional()->date(),

            'email' => $this->faker->unique()->safeEmail(),

            'mobile_number'   => $mobile,
            'whatsapp_number' => $mobile,
            'alternate_phone' => $this->faker->optional()->numerify('9#########'),

            'designation_id' => Designation::inRandomOrder()->value('id'),
            'department_id'  => Department::inRandomOrder()->value('id'),

            'linkedin' => $this->faker->optional()->url(),
            'facebook' => $this->faker->optional()->url(),
            'twitter'  => $this->faker->optional()->url(),
            'website'  => $this->faker->optional()->url(),

            'notes' => $this->faker->optional()->sentence(),

            // Polymorphic owner (set via states)
            'contactable_type' => null,
            'contactable_id'   => null,

            'account_master_id' => null,
        ];
    }

    /* =====================================================
     | STATES
     ===================================================== */

    /**
     * Attach contact to a Company
     */
    public function forCompany(Company $company)
    {
        return $this->state(fn () => [
            'company_id' => $company->id,
        ]);
    }

    /**
     * Attach contact to AccountMaster (polymorphic)
     */
    public function forAccount(AccountMaster $account)
    {
        return $this->state(fn () => [
            'contactable_type' => AccountMaster::class,
            'contactable_id'   => $account->id,
            'account_master_id'=> $account->id,
        ]);
    }

    /**
     * Contact without company
     */
    public function independent()
    {
        return $this->state(fn () => [
            'company_id' => null,
        ]);
    }
}
