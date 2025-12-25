<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ItemMaster;
use App\Models\Category;
use App\Models\ItemBrand;
use App\Models\UnitOfMeasurement;
use App\Models\PackagingType;

class ItemMasterFactory extends Factory
{
    protected $model = ItemMaster::class;

    public function definition(): array
    {
        return [
            'parent_id' => null,
            'variant_name' => null,

            'sku' => strtoupper(
                $this->faker->unique()->bothify('SKU-#####')
            ),
            'item_name' => ucfirst($this->faker->words(3, true)),
            'description' => $this->faker->sentence(),

            'category_id' => $this->randomLeafCategory()?->id,
            'category_type' => Category::class,

            'item_brand_id' => ItemBrand::first()?->id,
            'unit_of_measurement_id' => UnitOfMeasurement::first()?->id,
            'packaging_type_id' => PackagingType::first()?->id,

            'purchase_price' => $this->faker->numberBetween(100, 10000),
            'selling_price' => $this->faker->numberBetween(150, 15000),
            'tax_rate' => $this->faker->randomElement([5, 12, 18]),
            'discount' => $this->faker->numberBetween(0, 20),

            'opening_stock' => $this->faker->numberBetween(10, 500),
            'minimum_stock_level' => 10,
            'reorder_quantity' => 50,

            'hsn_code' => $this->faker->numerify('####'),
            'barcode' => $this->faker->ean13(),

            'lead_time' => $this->faker->numberBetween(1, 15),
            'has_variants' => false,
        ];
    }

    /* ================= VARIANT ================= */

    public function variant(ItemMaster $parent)
    {
        return $this->state(fn () => [
            'parent_id' => $parent->id,
            'variant_name' => $this->faker->colorName(),
            'has_variants' => false,
        ]);
    }

    /* ================= HAS VARIANTS ================= */

    public function hasVariants()
    {
        return $this->state(fn () => [
            'has_variants' => true,
        ]);
    }

    /* ================= HELPERS ================= */

    protected function randomLeafCategory(): ?Category
    {
        return Category::whereNotNull('parent_id')
            ->inRandomOrder()
            ->first();
    }
}
