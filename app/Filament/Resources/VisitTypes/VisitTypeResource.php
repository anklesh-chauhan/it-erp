<?php

namespace App\Filament\Resources\VisitTypes;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\VisitTypes\Pages\ListVisitTypes;
use App\Filament\Resources\VisitTypes\Pages\CreateVisitType;
use App\Filament\Resources\VisitTypes\Pages\EditVisitType;
use App\Filament\Resources\VisitTypeResource\Pages;
use App\Filament\Resources\VisitTypeResource\RelationManagers;
use App\Models\VisitType;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VisitTypeResource extends Resource
{
    protected static ?string $model = VisitType::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Global Config';
    protected static ?string $navigationParentItem = 'Sales & Marketing';
    protected static ?int $navigationSort = 1001;
    protected static ?string $navigationLabel = 'Visit Types';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => ListVisitTypes::route('/'),
            'create' => CreateVisitType::route('/create'),
            'edit' => EditVisitType::route('/{record}/edit'),
        ];
    }
}
