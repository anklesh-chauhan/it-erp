<?php

namespace Database\Factories;

use App\Models\LeaveAdjustment;
use App\Models\Employee;
use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveAdjustmentFactory extends Factory
{
    protected $model = LeaveAdjustment::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['positive', 'negative']),
            'days' => $this->faker->randomFloat(1, 0.5, 2),
            'reason' => 'Manual adjustment',
            'effective_date' => $this->faker->dateTimeBetween('-2 months', 'now'),
        ];
    }
}
