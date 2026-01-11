<?php

namespace Database\Factories;

use App\Models\PayrollLeaveSnapshot;
use App\Models\Employee;
use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayrollLeaveSnapshotFactory extends Factory
{
    protected $model = PayrollLeaveSnapshot::class;

    public function definition(): array
    {
        return [
            'processed_till' => now()->subMonth()->endOfMonth(),
            'opening_balance' => $this->faker->randomFloat(2, 5, 20),
            'closing_balance' => $this->faker->randomFloat(2, 1, 15),
        ];
    }
}
