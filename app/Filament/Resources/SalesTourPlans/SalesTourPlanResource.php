<?php

namespace App\Filament\Resources\SalesTourPlans;

use App\Filament\Resources\SalesTourPlans\Pages\CreateSalesTourPlan;
use App\Filament\Resources\SalesTourPlans\Pages\EditSalesTourPlan;
use App\Filament\Resources\SalesTourPlans\Pages\ListSalesTourPlans;
use App\Filament\Resources\SalesTourPlans\Schemas\SalesTourPlanForm;
use App\Filament\Resources\SalesTourPlans\Tables\SalesTourPlansTable;
use App\Models\SalesTourPlan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SalesTourPlanResource extends Resource
{
    protected static ?string $model = SalesTourPlan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'SalesTourPlan';

    public static function form(Schema $schema): Schema
    {
        return SalesTourPlanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalesTourPlansTable::configure($table);
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
            'index' => ListSalesTourPlans::route('/'),
            'create' => CreateSalesTourPlan::route('/create'),
            'edit' => EditSalesTourPlan::route('/{record}/edit'),
        ];
    }
}
