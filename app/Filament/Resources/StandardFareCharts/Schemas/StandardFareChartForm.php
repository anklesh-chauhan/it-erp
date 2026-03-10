<?php

namespace App\Filament\Resources\StandardFareCharts\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Schemas\Components\Section;


class StandardFareChartForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Route Details')
                    ->schema([
                        Forms\Components\Select::make('from_city_id')
                            ->relationship('fromCity', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('to_city_id')
                            ->relationship('toCity', 'name')
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
