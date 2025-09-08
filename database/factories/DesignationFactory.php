<?php

namespace Database\Factories;

use App\Models\Designation;
use Illuminate\Database\Eloquent\Factories\Factory;

class DesignationFactory extends Factory
{
    protected $model = Designation::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->jobTitle(), // generates realistic job titles
        ];
    }
}
