<?php

namespace Database\Factories;

use App\Models\TypeMaster;
use Illuminate\Database\Eloquent\Factories\Factory;

class TypeMasterFactory extends Factory
{
    protected $model = TypeMaster::class;

    public function definition(): array
    {
        return [
            'name'           => $this->faker->word(),
            'description'    => $this->faker->sentence(),
            'typeable_id'    => null,   // you can override this when attaching to a morph
            'typeable_type'  => null,   // e.g., App\Models\AccountMaster::class
        ];
    }
}
