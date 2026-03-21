<?php

namespace App\Filament\Resources\StandardFareCharts\Schemas;

use App\Models\StandardFareChart;
use App\Models\TypeMaster;
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;

class StandardFareChartForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Route Details')
                    ->schema([
                        Forms\Components\Select::make('from_area_town_id')
                            ->relationship('fromAreaTown', 'area_town')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('to_area_town_id')
                            ->relationship('toAreaTown', 'area_town')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('transport_mode_id')
                            ->relationship('transportMode', 'name')
                            ->required(),

                        Forms\Components\Select::make('territory_id')
                            ->relationship('territory', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('type_master_id')
                            ->label('SFC Type')
                            ->options(
                                TypeMaster::whereNull('parent_id')
                                    ->where('typeable_type', StandardFareChart::class)
                                    ->pluck('name', 'id')
                            ),

                    ])->columns(2),

                Section::make('Pricing & Distance')
                    ->schema([
                        Forms\Components\TextInput::make('distance_km')
                            ->numeric()
                            ->suffix('km')
                            ->required(),

                        Forms\Components\TextInput::make('fare_amount')
                            ->numeric()
                            ->prefix('INR'),

                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->label('Active Status'),
                    ])->columns(3),
            ]);
    }
}
