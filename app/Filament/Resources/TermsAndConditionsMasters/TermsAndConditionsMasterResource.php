<?php

namespace App\Filament\Resources\TermsAndConditionsMasters;

use App\Filament\Resources\TermsAndConditionsMasters\Pages\CreateTermsAndConditionsMaster;
use App\Filament\Resources\TermsAndConditionsMasters\Pages\EditTermsAndConditionsMaster;
use App\Filament\Resources\TermsAndConditionsMasters\Pages\ListTermsAndConditionsMasters;
use App\Filament\Resources\TermsAndConditionsMasters\Schemas\TermsAndConditionsMasterForm;
use App\Filament\Resources\TermsAndConditionsMasters\Tables\TermsAndConditionsMastersTable;
use App\Models\TermsAndConditionsMaster;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TermsAndConditionsMasterResource extends Resource
{
    protected static ?string $model = TermsAndConditionsMaster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TermsAndConditionsMasterForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TermsAndConditionsMastersTable::configure($table);
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
            'index' => ListTermsAndConditionsMasters::route('/'),
            'create' => CreateTermsAndConditionsMaster::route('/create'),
            'edit' => EditTermsAndConditionsMaster::route('/{record}/edit'),
        ];
    }
}
