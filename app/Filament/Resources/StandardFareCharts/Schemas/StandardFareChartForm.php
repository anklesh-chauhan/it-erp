<?php

namespace App\Filament\Resources\StandardFareCharts\Schemas;

use App\Models\CityPinCode;
use App\Models\StandardFareChart;
use App\Models\TypeMaster;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StandardFareChartForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Route Details')
                    ->schema([
                        Forms\Components\Select::make('territory_id')
                            ->relationship('territory', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('patch_id')
                            ->relationship('patch', 'name')
                            ->preload()
                            ->searchable(),

                        Forms\Components\Select::make('from_area_town_id')
                            ->label('From Area/Town')
                            ->relationship('fromAreaTown', 'area_town')
                            ->searchable()
                            ->debounce(300)
                            ->preload()
                            ->required()
                            ->getOptionLabelFromRecordUsing(fn (CityPinCode $record) => $record->full_location)
                            ->getSearchResultsUsing(function (string $search) {
                                $words = array_filter(explode(' ', trim($search)));

                                return CityPinCode::query()
                                    ->when(count($words) > 0, function ($query) use ($words) {
                                        foreach ($words as $word) {
                                            $query->where(function ($q) use ($word) {
                                                $q->where('area_town', 'like', "%{$word}%")
                                                    ->orWhere('pin_code', 'like', "%{$word}%")
                                                    ->orWhereHas('city', function ($cityQuery) use ($word) {
                                                        $cityQuery->where('name', 'like', "%{$word}%");
                                                    });
                                            });
                                        }
                                    })
                                    ->with('city')
                                    ->orderBy('area_town')
                                    ->limit(50)
                                    ->get()
                                    ->pluck('full_location', 'id');
                            })
                            ->placeholder('Search by area, city or pin code (e.g. ranip ahmedabad)'),

                        Forms\Components\Select::make('to_area_town_id')
                            ->label('To Area/Town')
                            ->relationship('toAreaTown', 'area_town')
                            ->searchable()
                            ->debounce(300)
                            ->preload()
                            ->required()
                            ->getOptionLabelFromRecordUsing(fn (CityPinCode $record) => $record->full_location)
                            ->getSearchResultsUsing(function (string $search) {
                                $words = array_filter(explode(' ', trim($search)));

                                return CityPinCode::query()
                                    ->when(count($words) > 0, function ($query) use ($words) {
                                        foreach ($words as $word) {
                                            $query->where(function ($q) use ($word) {
                                                $q->where('area_town', 'like', "%{$word}%")
                                                    ->orWhere('pin_code', 'like', "%{$word}%")
                                                    ->orWhereHas('city', function ($cityQuery) use ($word) {
                                                        $cityQuery->where('name', 'like', "%{$word}%");
                                                    });
                                            });
                                        }
                                    })
                                    ->with('city')
                                    ->orderBy('area_town')
                                    ->limit(50)
                                    ->get()
                                    ->pluck('full_location', 'id');
                            })
                            ->placeholder('Search by area, city or pin code (e.g. ranip ahmedabad)'),

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
