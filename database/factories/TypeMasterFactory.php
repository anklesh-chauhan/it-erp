<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TypeMaster;

class TypeMasterFactory extends Factory
{
    protected $model = TypeMaster::class;

    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->unique()->words(2, true)),
            'description' => $this->faker->sentence(),
            'typeable_type' => null,
            'typeable_id' => null,
            'parent_id' => null,
        ];
    }

    /* =====================================================
     | ROOT TYPES
     ===================================================== */

    public function accountRoot()
    {
        return $this->state(fn () => [
            'typeable_type' => \App\Models\AccountMaster::class,
            'parent_id' => null,
        ]);
    }

    public function addressRoot()
    {
        return $this->state(fn () => [
            'typeable_type' => \App\Models\Address::class,
            'parent_id' => null,
        ]);
    }

    public function dealRoot()
    {
        return $this->state(fn () => [
            'typeable_type' => \App\Models\Deal::class,
            'parent_id' => null,
        ]);
    }

    public function locationRoot()
    {
        return $this->state(fn () => [
            'typeable_type' => \App\Models\LocationMaster::class,
            'parent_id' => null,
        ]);
    }

    public function organizationalUnitRoot()
    {
        return $this->state(fn () => [
            'typeable_type' => \App\Models\OrganizationalUnit::class,
            'parent_id' => null,
        ]);
    }

    /* =====================================================
     | SUB TYPES
     ===================================================== */

    public function subType(TypeMaster $parent)
    {
        return $this->state(fn () => [
            'parent_id' => $parent->id,
            'typeable_type' => $parent->typeable_type,
        ]);
    }
}
