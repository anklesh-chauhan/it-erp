<?php

namespace App\Filament\Resources\FollowUpResults;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\FollowUpResults\Pages\ListFollowUpResults;
use App\Filament\Resources\FollowUpResults\Pages\CreateFollowUpResult;
use App\Filament\Resources\FollowUpResults\Pages\EditFollowUpResult;
use App\Filament\Resources\FollowUpResultResource\Pages;
use App\Filament\Resources\FollowUpResultResource\RelationManagers;
use App\Models\FollowUpResult;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FollowUpResultResource extends Resource
{
    protected static ?string $model = FollowUpResult::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Global Config';
    protected static ?string $navigationParentItem = 'Follow Up Config';
    protected static ?string $navigationLabel = 'Results';
    protected static ?int $navigationSort = 3;

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
            'index' => ListFollowUpResults::route('/'),
            'create' => CreateFollowUpResult::route('/create'),
            'edit' => EditFollowUpResult::route('/{record}/edit'),
        ];
    }
}
