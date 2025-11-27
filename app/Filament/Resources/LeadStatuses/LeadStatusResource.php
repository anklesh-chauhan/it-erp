<?php

namespace App\Filament\Resources\LeadStatuses;

use App\Traits\HasSafeGlobalSearch;

use App\Filament\Actions\ApprovalAction;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\LeadStatuses\Pages\ListLeadStatuses;
use App\Filament\Resources\LeadStatuses\Pages\CreateLeadStatus;
use App\Filament\Resources\LeadStatuses\Pages\EditLeadStatus;
use App\Filament\Resources\LeadStatusResource\Pages;
use App\Filament\Resources\LeadStatusResource\RelationManagers;
use App\Models\LeadStatus;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeadStatusResource extends Resource
{
    use HasSafeGlobalSearch;
    protected static ?string $model = LeadStatus::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Global Config';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Lead Status';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationParentItem = 'Lead Config';

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
            'index' => ListLeadStatuses::route('/'),
            'create' => CreateLeadStatus::route('/create'),
            'edit' => EditLeadStatus::route('/{record}/edit'),
        ];
    }
}
