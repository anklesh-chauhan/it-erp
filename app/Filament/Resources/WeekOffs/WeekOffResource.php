<?php

namespace App\Filament\Resources\WeekOffs;

use App\Filament\Resources\WeekOffs\Pages\CreateWeekOff;
use App\Filament\Resources\WeekOffs\Pages\EditWeekOff;
use App\Filament\Resources\WeekOffs\Pages\ListWeekOffs;
use App\Filament\Resources\WeekOffs\Pages\ViewWeekOff;
use App\Filament\Resources\WeekOffs\Schemas\WeekOffForm;
use App\Filament\Resources\WeekOffs\Schemas\WeekOffInfolist;
use App\Filament\Resources\WeekOffs\Tables\WeekOffsTable;
use App\Models\WeekOff;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WeekOffResource extends Resource
{
    protected static ?string $model = WeekOff::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'WeekOff';

    public static function form(Schema $schema): Schema
    {
        return WeekOffForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WeekOffInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WeekOffsTable::configure($table);
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
            'index' => ListWeekOffs::route('/'),
            'create' => CreateWeekOff::route('/create'),
            'view' => ViewWeekOff::route('/{record}'),
            'edit' => EditWeekOff::route('/{record}/edit'),
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
