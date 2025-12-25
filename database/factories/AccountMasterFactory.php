<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AccountMaster;
use App\Models\User;
use App\Models\IndustryType;
use App\Models\Region;
use App\Models\RatingType;
use App\Models\AccountOwnership;
use App\Helpers\TypeMasterHelper;

class AccountMasterFactory extends Factory
{
    protected $model = AccountMaster::class;

    public function definition(): array
    {
        $type = TypeMasterHelper::randomLeaf(AccountMaster::class);

        return [
            'owner_id' => User::first()?->id,
            'type_master_id' => $type->id,

            'name' => $this->indianCompany(),
            'phone_number' => $this->indianMobile(),
            'email' => $this->faker->unique()->companyEmail(),
            'secondary_email' => $this->faker->companyEmail(),

            'website' => $this->faker->url(),
            'no_of_employees' => $this->faker->numberBetween(5, 500),
            'annual_revenue' => $this->faker->numberBetween(10_00_000, 50_00_00_000),

            'industry_type_id' => IndustryType::first()?->id,
            'region_id' => Region::first()?->id,
            'rating_type_id' => RatingType::first()?->id,
            'account_ownership_id' => AccountOwnership::first()?->id,

            'description' => 'Factory generated Indian account',
        ];
    }

    /* ================= STATES ================= */

    public function customer()
    {
        return $this->state(fn () => [
            'type_master_id' =>
                TypeMasterHelper::leaf(AccountMaster::class, 'Customer')->id,
        ]);
    }

    public function vendor()
    {
        return $this->state(fn () => [
            'type_master_id' =>
                TypeMasterHelper::leaf(AccountMaster::class, 'Vendor')->id,
        ]);
    }

    public function dealer()
    {
        return $this->state(fn () => [
            'type_master_id' =>
                TypeMasterHelper::leaf(AccountMaster::class, 'Dealer')->id,
        ]);
    }

    public function transporter()
    {
        return $this->state(fn () => [
            'type_master_id' =>
                TypeMasterHelper::leaf(AccountMaster::class, 'Transporter')->id,
        ]);
    }

    /* ================= HELPERS ================= */

    protected function indianCompany(): string
    {
        return sprintf(
            '%s %s %s %04d',
            $this->faker->randomElement([
                'Sharma','Patel', 'Chauhan', 'Agarwal','Gupta','Mehta','Singh','Jain','Reddy',
                'Iyer','Nair','Chopra','Malhotra','Kapoor','Bansal','Mittal',
                'Verma','Yadav','Khan','Ansari','Qureshi','Shaikh', 'Indian', 'Anklesh', 'Chandresh',
                'Shweta', 'Preeti', 'Janvi', 'Gandhi', 'Hetal', 'Jainam', 'Amit',
            ]),
            $this->faker->randomElement([
                'Industries','Enterprises','Traders','Corporation','Solutions',
                'Technologies','Logistics','Exports','Imports','Manufacturing',
                'Engineering','Consultants','Services','Systems','Infrastructure',
            ]),
            $this->faker->randomElement([
                'Pvt Ltd','LLP','Limited','Co','Group'
            ]),
            $this->faker->unique()->numberBetween(1, 9999)
        );
    }


    protected function indianMobile(): string
    {
        return (string) collect([6,7,8,9])->random()
            . $this->faker->numerify('#########');
    }
}
