<?php

namespace App\Filament\Resources\LeaveLapseRecords;

use App\Filament\Resources\LeaveLapseRecords\Pages\CreateLeaveLapseRecord;
use App\Filament\Resources\LeaveLapseRecords\Pages\EditLeaveLapseRecord;
use App\Filament\Resources\LeaveLapseRecords\Pages\ListLeaveLapseRecords;
use App\Filament\Resources\LeaveLapseRecords\Schemas\LeaveLapseRecordForm;
use App\Filament\Resources\LeaveLapseRecords\Tables\LeaveLapseRecordsTable;
use App\Models\LeaveLapseRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeaveLapseRecordResource extends Resource
{
    protected static ?string $model = LeaveLapseRecord::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'LeaveLapseRecord';

    public static function form(Schema $schema): Schema
    {
        return LeaveLapseRecordForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeaveLapseRecordsTable::configure($table);
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
            'index' => ListLeaveLapseRecords::route('/'),
            'create' => CreateLeaveLapseRecord::route('/create'),
            'edit' => EditLeaveLapseRecord::route('/{record}/edit'),
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
