<?php

namespace App\Traits;

use App\Models\Category;
use App\Models\LocationMaster;
use Dom\Text;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;

trait ItemMasterTableTrait
{
    /**
     * Creates a Filament Select Filter to filter items by type (Main/Variant/All).
     * Defaults to 'main' items only.
     *
     * @return SelectFilter
     */
    public static function getItemTypeFilter(): SelectFilter
    {
        return SelectFilter::make('item_type')
            ->label('Item Type')
            ->options([
                'main' => 'Main Items Only',
                'variant' => 'Variants Only',
                'all' => 'All Items',
            ])
            ->default('main') // <-- Sets the default selection to Main Items
            ->query(function (Builder $query, array $data): Builder {
                $value = $data['value'] ?? 'main'; // Use 'main' if value is not set

                if ($value === 'main') {
                    // Filter: Only records where parent_id is null
                    return $query->whereNull('parent_id');
                }

                if ($value === 'variant') {
                    // Filter: Only records where parent_id is not null
                    return $query->whereNotNull('parent_id');
                }

                // If 'all' is selected, return the query unfiltered
                return $query;
            });
    }

    /**
     * Recursively format locations with indentation for sublocations for select field options.
     * Assumes a 'subLocations' relationship exists on the LocationMaster model.
     * @param \Illuminate\Support\Collection $locations
     * @param string $prefix
     * @return array
     */
    public static function formatLocations($locations, $prefix = ''): array
    {
        $options = [];
        foreach ($locations as $location) {
            $options[$location->id] = $prefix . $location->name;
            if ($location->subLocations->count()) {
                $options += self::formatLocations($location->subLocations, $prefix . '— ');
            }
        }
        return $options;
    }

    /**
     * Recursively format categories with indentation for subcategories for select field options.
     * Assumes a 'subCategories' relationship exists on the Category model.
     * @param \Illuminate\Support\Collection $categories
     * @param string $prefix
     * @return array
     */
    public static function formatCategories($categories, $prefix = ''): array
    {
        $options = [];
        foreach ($categories as $category) {
            $options[$category->id] = $prefix . $category->name;
            if ($category->subCategories->count()) {
                $options += self::formatCategories($category->subCategories, $prefix . '— ');
            }
        }
        return $options;
    }

    /**
     * Get common table columns for Item Master.
     *
     * @return array
     */
    public static function getItemMasterTableTrait(): array
    {
        return [
            TextColumn::make('item_code')->label('Item Code')->sortable()->searchable(),
            TextColumn::make('item_name')->label('Item Name')->sortable()->searchable(),
            TextColumn::make('variant_name')->label('Variant Name')->sortable()->searchable(),
            TextColumn::make('category.name')
                ->label('Category')
                ->formatStateUsing(function ($state, $record) {
                    if (!$record->category) {
                        return '-';
                    }

                    // Find the root parent of the category
                    $category = $record->category;
                    $rootCategory = $category;
                    while ($rootCategory->parent) {
                        $rootCategory = $rootCategory->parent;
                    }

                    // Fetch the hierarchy starting from the root
                    $categories = Category::with('subCategories')
                        ->where('id', $rootCategory->id)
                        ->get();

                    // Using self::formatCategories to call the helper method within this trait
                    $formatted = self::formatCategories($categories);
                    return $formatted[$record->category->id] ?? $record->category->name;
                })
                ->searchable(),

            TextColumn::make('brand.name')->label('Brand')->sortable(),
            TextColumn::make('storage_location')->label('Storage'),
            BadgeColumn::make('expiry_date')->label('Expiry Date')->date(),
            TextColumn::make('locations.name')
                ->label('Locations')
                ->sortable(),
        ];
    }
}
