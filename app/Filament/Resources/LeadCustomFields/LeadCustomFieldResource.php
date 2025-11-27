<?php

namespace App\Filament\Resources\LeadCustomFields;

use App\Traits\HasSafeGlobalSearch;

use App\Filament\Actions\ApprovalAction;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\LeadCustomFields\Pages\ListLeadCustomFields;
use App\Filament\Resources\LeadCustomFields\Pages\CreateLeadCustomField;
use App\Filament\Resources\LeadCustomFields\Pages\EditLeadCustomField;
use App\Filament\Resources\LeadCustomFieldResource\Pages;
use App\Filament\Resources\LeadCustomFieldResource\RelationManagers;
use App\Models\LeadCustomField;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeadCustomFieldResource extends Resource
{
    use HasSafeGlobalSearch;
    protected static ?string $model = LeadCustomField::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Global Config';
    protected static ?string $navigationLabel = 'Lead Custom Fields';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationParentItem = 'Lead Config';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('label')
                    ->required()
                    ->maxLength(255),

                Select::make('type')
                    ->options([
                        'text' => 'Text',
                        'number' => 'Number',
                        'date' => 'Date',
                        'email' => 'Email',
                    ])
                    ->required(),

                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')
                    ->searchable(),
                TextColumn::make('type')
                    ->searchable(),
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
                ApprovalAction::make(),
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
            'index' => ListLeadCustomFields::route('/'),
            'create' => CreateLeadCustomField::route('/create'),
            'edit' => EditLeadCustomField::route('/{record}/edit'),
        ];
    }
}
