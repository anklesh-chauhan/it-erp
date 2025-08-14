<?php

namespace App\Filament\Resources\TermsAndConditions;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\TermsAndConditions\Pages\ListTermsAndConditions;
use App\Filament\Resources\TermsAndConditions\Pages\CreateTermsAndCondition;
use App\Filament\Resources\TermsAndConditions\Pages\EditTermsAndCondition;
use App\Filament\Resources\TermsAndConditionResource\Pages;
use App\Filament\Resources\TermsAndConditionResource\RelationManagers;
use App\Models\TermsAndCondition;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TermsAndConditionResource extends Resource
{
    protected static ?string $model = TermsAndCondition::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Global Config';
    protected static ?string $navigationParentItem = 'Company Config';
    protected static ?int $navigationSort = 1001;
    protected static ?string $navigationLabel = 'Terms & Conditions';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('model_type')
                    ->maxLength(255),
                TextInput::make('model_id')
                    ->numeric(),
                TextInput::make('terms_type_id')
                    ->numeric(),
                Textarea::make('terms_and_conditions')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('remarks')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('model_type')
                    ->searchable(),
                TextColumn::make('model_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('terms_type_id')
                    ->numeric()
                    ->sortable(),
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
            'index' => ListTermsAndConditions::route('/'),
            'create' => CreateTermsAndCondition::route('/create'),
            'edit' => EditTermsAndCondition::route('/{record}/edit'),
        ];
    }
}
