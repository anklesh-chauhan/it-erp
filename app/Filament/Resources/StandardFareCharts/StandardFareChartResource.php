<?php

namespace App\Filament\Resources\StandardFareCharts;

use App\Filament\Clusters\GlobalConfiguration\SalesMarketingConfigurationCluster;
use App\Filament\Resources\StandardFareCharts\Pages\CreateStandardFareChart;
use App\Filament\Resources\StandardFareCharts\Pages\EditStandardFareChart;
use App\Filament\Resources\StandardFareCharts\Pages\ListStandardFareCharts;
use App\Filament\Resources\StandardFareCharts\Schemas\StandardFareChartForm;
use App\Filament\Resources\StandardFareCharts\Tables\StandardFareChartsTable;
use App\Models\StandardFareChart;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StandardFareChartResource extends Resource
{
    protected static ?string $model = StandardFareChart::class;

    protected static ?string $recordTitleAttribute = 'StandardFareChart';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $cluster = SalesMarketingConfigurationCluster::class;
    protected static ?int $navigationSort = 10;
    protected static ?string $navigationLabel = 'SFC';

    public static function form(Schema $schema): Schema
    {
        return StandardFareChartForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StandardFareChartsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStandardFareCharts::route('/'),
            'create' => CreateStandardFareChart::route('/create'),
            'edit' => EditStandardFareChart::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
