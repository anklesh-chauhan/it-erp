<?php

namespace Database\Factories;

use App\Models\LeaveInstance;
use App\Models\Employee;
use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveInstanceFactory extends Factory
{
    protected $model = LeaveInstance::class;

    public function definition(): array
    {
        return [
            'date' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'pay_factor' => $this->faker->randomElement([1, 0.5]),
            'approval_status' => 'approved',
            'is_half_day' => false,
            'approved_at' => now(),
        ];
    }
}
