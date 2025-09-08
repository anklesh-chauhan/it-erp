<?php

namespace Database\Factories;

use App\Models\RatingType;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingTypeFactory extends Factory
{
    protected $model = RatingType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Hot',
                'Warm',
                'Cold',
                'Excellent',
                'Good',
                'Average',
                'Poor',
            ]),
        ];
    }
}
