<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->unique()->words(2, true)),
            'alias' => $this->faker->slug(),
            'description' => $this->faker->sentence(),
            'image_path' => null,

            'parent_id' => null,
            'modelable_id' => null,
            'modelable_type' => null,
        ];
    }

    /* ================= ROOT CATEGORY ================= */

    public function root(string $modelableType)
    {
        return $this->state(fn () => [
            'parent_id' => null,
            'modelable_type' => $modelableType,
        ]);
    }

    /* ================= SUB CATEGORY ================= */

    public function subCategory(Category $parent)
    {
        return $this->state(fn () => [
            'parent_id' => $parent->id,
            'modelable_type' => $parent->modelable_type,
        ]);
    }
}
