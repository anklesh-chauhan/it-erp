<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AddressTypeResource\Pages;
use App\Filament\Resources\AddressTypeResource\RelationManagers;
use App\Models\AddressType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressTypeResource extends Resource
{
    protected static ?string $model = AddressType::class;

    protected static ?string $navigationGroup = 'Global Config';
    protected static ?string $navigationParentItem = 'Address Config';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Address Types';
    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListAddressTypes::route('/'),
            'create' => Pages\CreateAddressType::route('/create'),
            'edit' => Pages\EditAddressType::route('/{record}/edit'),
        ];
    }
}
