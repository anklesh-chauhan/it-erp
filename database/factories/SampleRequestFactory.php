<?php

namespace Database\Factories;

use App\Enums\SampleRequestStatus;
use App\Models\Employee;
use App\Models\LocationMaster;
use App\Models\Territory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SampleRequest>
 */
class SampleRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::query()->inRandomOrder()->value('id'),
            'territory_id' => Territory::query()->inRandomOrder()->value('id'),
            'location_master_id' => LocationMaster::query()->inRandomOrder()->value('id'),
            'request_date' => fake()->date(),
            'status' => SampleRequestStatus::Draft,
            'purpose' => fake()->sentence(),
        ];
    }
}
