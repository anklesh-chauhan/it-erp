<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ItemMaster;
use App\Models\Category;
use App\Models\Tax;

class ItemMasterSeeder extends Seeder
{
    public function run(): void
    {
        // Use only LEAF categories
        $leafCategories = Category::whereDoesntHave('children')->get();

        if ($leafCategories->isEmpty()) {
            $this->command->warn('No leaf categories found. Skipping ItemMasterSeeder.');
            return;
        }

        $taxIds = Tax::pluck('id')->toArray();

        if (empty($taxIds)) {
            $this->command->warn('No taxes found. Skipping tax attachment.');
        }

        foreach ($leafCategories as $category) {

            // 1️⃣ Create parent items
            $items = ItemMaster::factory()
                ->count(rand(3, 6))
                ->create([
                    'category_id' => $category->id,
                    'category_type' => Category::class,
                    'has_variants' => true,
                ]);

            foreach ($items as $item) {

                // 🔥 Attach 1 random tax to parent item
                if (! empty($taxIds)) {
                    $item->taxes()->sync([
                        collect($taxIds)->random(),
                    ]);
                }

                // 2️⃣ Create variants
                $variants = ItemMaster::factory()
                    ->count(rand(2, 4))
                    ->variant($item)
                    ->create([
                        'category_id' => $category->id,
                        'category_type' => Category::class,
                    ]);

                // 🔥 Variants inherit parent tax
                foreach ($variants as $variant) {
                    if (! empty($taxIds)) {
                        $variant->taxes()->sync($item->taxes->pluck('id')->toArray());
                    }
                }
            }
        }
    }
}
