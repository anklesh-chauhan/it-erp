<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\LocationMaster;
use App\Helpers\TypeMasterHelper;

class LocationMasterFactory extends Factory
{
    protected $model = LocationMaster::class;

    public function definition(): array
    {
        $type = TypeMasterHelper::randomLeaf(LocationMaster::class);

        return [
            'name' => $this->faker->city . ' ' . $type->name,
            'description' => $this->faker->sentence(),
            'is_active' => true,

            'latitude' => $this->faker->latitude(8, 37),
            'longitude' => $this->faker->longitude(68, 97),

            'typeable_id' => $type->id,
            'typeable_type' => \App\Models\TypeMaster::class,

            'parent_id' => null,
        ];
    }

    public function subLocation(LocationMaster $parent)
    {
        return $this->state(fn () => [
            'parent_id' => $parent->id,
        ]);
    }
}
