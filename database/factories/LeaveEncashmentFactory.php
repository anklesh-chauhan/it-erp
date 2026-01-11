<?php

namespace Database\Factories;

use App\Models\LeaveEncashment;
use App\Models\Employee;
use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveEncashmentFactory extends Factory
{
    protected $model = LeaveEncashment::class;

    public function definition(): array
    {
        return [
            'days' => $this->faker->randomFloat(1, 1, 3),
            'amount' => $this->faker->randomFloat(2, 1000, 5000),
            'encashed_on' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'payroll_month' => now()->startOfMonth(),
        ];
    }
}
