<?php

namespace Database\Factories;

use App\Models\LeaveLapseRecord;
use App\Models\Employee;
use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveLapseRecordFactory extends Factory
{
    protected $model = LeaveLapseRecord::class;

    public function definition(): array
    {
        return [
            'days' => $this->faker->randomFloat(1, 0.5, 2),
            'lapsed_on' => now()->subDays(rand(1, 30)),
            'reason' => 'Year-end lapse',
        ];
    }
}
