<?php

namespace App\Filament\Resources\TravelSegments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TravelSegmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('sales_dcr_id')
                    ->relationship('salesDcr', 'id')
                    ->required(),
                Select::make('visit_id')
                    ->relationship('visit', 'id'),
                Select::make('sales_tour_plan_detail_id')
                    ->relationship('salesTourPlanDetail', 'id'),
                Select::make('patch_id')
                    ->relationship('patch', 'name'),
                Select::make('from_account_id')
                    ->relationship('fromAccount', 'name'),
                Select::make('to_account_id')
                    ->relationship('toAccount', 'name'),
                Select::make('from_area_town_id')
                    ->relationship('fromAreaTown', 'area_town'),
                Select::make('to_area_town_id')
                    ->relationship('toAreaTown', 'area_town'),
                Select::make('transport_mode_id')
                    ->relationship('transportMode', 'name'),
                TextInput::make('distance_km')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('distance_source')
                    ->required()
                    ->default('manual'),
                TextInput::make('gps_distance_km')
                    ->numeric(),
                Toggle::make('is_auto_generated')
                    ->required(),
                TextInput::make('created_by'),
                TextInput::make('updated_by'),
                TextInput::make('deleted_by'),
            ]);
    }
}
