<?php

namespace Database\Factories;

use App\Models\LeaveBalance;
use App\Models\Employee;
use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveBalanceFactory extends Factory
{
    protected $model = LeaveBalance::class;

    public function definition(): array
    {
        $yearStart = now()->startOfYear();

        return [
            'opening_balance' => $this->faker->randomFloat(2, 5, 20),
            'year_start_date' => $yearStart,
            'year_end_date'   => $yearStart->copy()->endOfYear(),
        ];
    }
}
