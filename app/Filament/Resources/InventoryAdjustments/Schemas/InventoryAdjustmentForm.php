<?php

namespace App\Filament\Resources\InventoryAdjustments\Schemas;

use App\Enums\InventoryAdjustmentType;
use App\Enums\InventoryDocumentStatus;
use App\Models\InventoryAdjustment;
use App\Models\ItemMaster;
use App\Models\LocationMaster;
use App\Models\NumberSeries;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InventoryAdjustmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Adjustment Details')
                    ->columns(3)
                    ->schema([
                        TextInput::make('adjustment_number')
                            ->label('Document No.')
                            ->default(fn (): string => NumberSeries::getNextNumber(InventoryAdjustment::class))
                            ->disabled()
                            ->dehydrated(),

                        Select::make('status')
                            ->options(InventoryDocumentStatus::class)
                            ->default(InventoryDocumentStatus::Draft)
                            ->disabled()
                            ->dehydrated(),

                        Select::make('adjustment_type')
                            ->options(InventoryAdjustmentType::class)
                            ->required(),

                        Select::make('item_master_id')
                            ->label('Item')
                            ->options(fn (): array => ItemMaster::query()
                                ->whereNull('parent_id')
                                ->orderBy('item_name')
                                ->pluck('item_name', 'id')
                                ->all())
                            ->searchable()
                            ->required(),

                        Select::make('location_master_id')
                            ->label('Location')
                            ->options(fn (): array => self::locationOptions())
                            ->searchable()
                            ->required(),

                        TextInput::make('quantity')
                            ->numeric()
                            ->minValue(0.001)
                            ->step(0.001)
                            ->required(),

                        TextInput::make('unit_cost')
                            ->label('Unit Cost')
                            ->numeric(),

                        TextInput::make('reason')
                            ->required()
                            ->columnSpan(2),

                        Textarea::make('remarks')
                            ->columnSpanFull(),
                    ])->columnSpanFull(),
            ]);
    }

    protected static function locationOptions(): array
    {
        $locations = LocationMaster::query()
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->with('subLocations')
            ->orderBy('name')
            ->get();

        $options = [];
        self::buildLocationOptions($locations, $options);

        return $options;
    }

    protected static function buildLocationOptions($locations, array &$options, string $prefix = ''): void
    {
        foreach ($locations as $location) {
            $options[$location->id] = $prefix.$location->name.' ['.$location->location_code.']';

            $activeSubLocations = $location->subLocations->where('is_active', true);

            if ($activeSubLocations->isNotEmpty()) {
                self::buildLocationOptions($activeSubLocations, $options, $prefix.'— ');
            }
        }
    }
}
