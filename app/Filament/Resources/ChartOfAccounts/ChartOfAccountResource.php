<?php

namespace App\Filament\Resources\ChartOfAccounts;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Resources\ChartOfAccounts\Pages\ListChartOfAccounts;
use App\Filament\Resources\ChartOfAccounts\Pages\CreateChartOfAccount;
use App\Filament\Resources\ChartOfAccounts\Pages\EditChartOfAccount;
use App\Filament\Resources\ChartOfAccountResource\Pages;
use App\Filament\Resources\ChartOfAccountResource\RelationManagers;
use App\Models\ChartOfAccount;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChartOfAccountResource extends Resource
{
    protected static ?string $model = ChartOfAccount::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Accounting';
    protected static ?string $navigationLabel = 'Chart of Accounts';
    protected static ?string $modelLabel = 'Account';
    protected static ?string $pluralModelLabel = 'Chart of Accounts';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Account Details')
                ->schema([
                    Select::make('parent_id')
                        ->label('Parent Account')
                        ->relationship('parent', 'name')
                        ->searchable() // Allows for quick searching
                        ->preload()    // Preloads options for better performance on smaller datasets
                        ->nullable()
                        ->placeholder('Select a parent account (optional)'),

                    Select::make('account_type_id')
                        ->label('Account Type')
                        ->relationship('accountType', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->placeholder('Select the account type'),

                    TextInput::make('code')
                        ->label('Account Code')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('e.g., 1000'),

                    TextInput::make('name')
                        ->label('Account Name')
                        ->required()
                        ->maxLength(255),

                    Textarea::make('description')
                        ->label('Description')
                        ->rows(2)
                        ->maxLength(500)
                        ->placeholder('Optional description...'),
                ])
                ->columns(2),

            Section::make('Account Status')
                ->schema([
                    Toggle::make('is_active')
                        ->label('Is Active')
                        ->default(true),

                    Toggle::make('is_group')
                        ->label('Is Group Account')
                        ->helperText('Group accounts cannot have direct transactions. They are used for categorization.'),

                    Toggle::make('is_system')
                        ->label('System Account')
                        ->helperText('System-owned accounts cannot be modified or deleted by users.')
                        ->default(false),
                ])
                ->columns(3),

            Section::make('Balance Information')
                ->schema([
                    TextInput::make('opening_balance')
                        ->label('Opening Balance')
                        ->numeric()
                        ->prefix('₹')
                        ->default(0.00)
                        ->required(),

                    DatePicker::make('opening_balance_date')
                        ->label('Opening Balance Date'),

                    TextInput::make('balance')
                        ->label('Current Balance')
                        ->prefix('₹')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(false)
                        ->helperText('This balance is automatically calculated from ledger entries.'),
                ])
                ->columns(3),

            Section::make('Currency Settings')
                ->schema([
                    Select::make('currency')
                        ->label('Currency')
                        ->options([
                            'INR' => 'Indian Rupee (₹)',
                            'USD' => 'US Dollar ($)',
                            'EUR' => 'Euro (€)',
                        ])
                        ->default('INR')
                        ->required()
                        ->searchable(),
                ])
                ->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Code')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Account Name')
                    ->sortable()
                    ->searchable()
                    ->wrap(),

                TextColumn::make('accountType.name')
                    ->label('Type')
                    ->sortable()
                    ->searchable()
                    ->color(fn (ChartOfAccount $record): string => match ($record->accountType->name) {
                        'Asset' => 'success',
                        'Liability' => 'danger',
                        'Equity' => 'info',
                        'Revenue' => 'success',
                        'Expense' => 'warning',
                        default => 'secondary',
                    })
                    ->badge(),

                TextColumn::make('parent.name')
                    ->label('Parent')
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('is_group')
                    ->label('Group')
                    ->boolean(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                IconColumn::make('is_system')
                    ->label('System')
                    ->boolean(),

                TextColumn::make('opening_balance')
                    ->label('Opening Balance')
                    ->money('INR')
                    ->sortable(),

                TextColumn::make('balance')
                    ->label('Balance')
                    ->money('INR')
                    ->sortable(),

                TextColumn::make('opening_balance_date')
                    ->label('Opening Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('currency')
                    ->sortable()
                    ->toggleable(),

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
                TernaryFilter::make('is_active')
                    ->label('Show Active Only')
                    ->default(true),

                TernaryFilter::make('is_group')
                    ->label('Is Group Account')
                    ->placeholder('All')
                    ->trueLabel('Only Group Accounts')
                    ->falseLabel('Only Non-Group Accounts'),

                SelectFilter::make('account_type_id')
                    ->label('Filter by Account Type')
                    ->relationship('accountType', 'name'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('code');
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
            'index' => ListChartOfAccounts::route('/'),
            'create' => CreateChartOfAccount::route('/create'),
            'edit' => EditChartOfAccount::route('/{record}/edit'),
        ];
    }
}
