<?php

namespace App\Filament\Resources\AccountTypes;

use App\Traits\HasSafeGlobalSearch;

use App\Filament\Actions\ApprovalAction;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Resources\AccountTypes\Pages\ListAccountTypes;
use App\Filament\Resources\AccountTypes\Pages\CreateAccountType;
use App\Filament\Resources\AccountTypes\Pages\EditAccountType;
use App\Filament\Resources\AccountTypeResource\Pages;
use App\Filament\Resources\AccountTypeResource\RelationManagers;
use App\Models\AccountType;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccountTypeResource extends Resource
{
    use HasSafeGlobalSearch;
    protected static ?string $model = AccountType::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-banknotes';
    protected static string | \UnitEnum | null $navigationGroup = 'Accounting';
    protected static ?string $navigationLabel = 'Account Types';
    protected static ?string $modelLabel = 'Account Type';
    protected static ?string $pluralModelLabel = 'Account Types';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Account Type Details')
                ->schema([
                    TextInput::make('name')
                        ->label('Name')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('e.g., Asset'),

                    TextInput::make('code')
                        ->label('Code')
                        ->required()
                        ->maxLength(10)
                        ->placeholder('e.g., A'),

                    Textarea::make('description')
                        ->maxLength(255)
                        ->rows(2)
                        ->placeholder('Optional description...'),

                    Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),

                    Toggle::make('is_system')
                        ->label('System Type')
                        ->default(false),
                ])
                ->columnSpanFull()
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('code')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('description')
                    ->limit(50)
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                IconColumn::make('is_system')
                    ->label('System')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->label('Deleted At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(), // For soft deletes
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                ApprovalAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
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
            'index' => ListAccountTypes::route('/'),
            'create' => CreateAccountType::route('/create'),
            'edit' => EditAccountType::route('/{record}/edit'),
        ];
    }
}
