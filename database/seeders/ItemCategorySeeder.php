<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\ItemMaster;

class ItemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roots = Category::factory()
            ->count(10)
            ->root(ItemMaster::class)
            ->create();

        foreach ($roots as $root) {
            $children = Category::factory()
                ->count(3)
                ->subCategory($root)
                ->create();

        foreach ($children as $child) {
            Category::factory()
                ->count(2)
                ->subCategory($child)
                ->create();

            }
        }
    }
}
