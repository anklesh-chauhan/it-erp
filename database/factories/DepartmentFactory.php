<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Sales',
                'Marketing',
                'Human Resources',
                'Finance',
                'IT',
                'Operations',
                'Customer Support',
                'Research & Development',
                'Procurement',
                'Legal',
            ]),
        ];
    }
}
