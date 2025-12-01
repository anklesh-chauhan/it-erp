<?php

namespace App\Filament\Resources\Ledgers;

use App\Filament\Actions\BulkApprovalAction;

use App\Traits\HasSafeGlobalSearch;

use App\Filament\Actions\ApprovalAction;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Ledgers\Pages\ListLedgers;
use App\Filament\Resources\Ledgers\Pages\CreateLedger;
use App\Filament\Resources\Ledgers\Pages\EditLedger;
use App\Filament\Resources\LedgerResource\Pages;
use App\Filament\Resources\LedgerResource\RelationManagers;
use App\Models\Ledger;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LedgerResource extends Resource
{
    use HasSafeGlobalSearch;
    protected static ?string $model = Ledger::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Accounting';
    protected static ?string $navigationLabel = 'Ledgers';
    protected static ?string $modelLabel = 'Ledger Entry';
    protected static ?string $pluralModelLabel = 'Ledger Entries';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ledger Entry Details')
                    ->schema([
                        DatePicker::make('date')
                            ->required()
                            ->default(now())
                            ->label('Entry Date'),

                        Select::make('chart_of_account_id')
                            ->relationship('chartOfAccount', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Chart of Account'),

                        TextInput::make('reference')
                            ->maxLength(255)
                            ->placeholder('Invoice No., Cheque No., or Note'),

                        Textarea::make('description')
                            ->rows(2)
                            ->columnSpan('full')
                            ->placeholder('Enter a detailed description of the transaction...'),
                    ])
                    ->columnSpanFull()
                    ->columns(2),

                Section::make('Amounts')
                    ->schema([
                        Grid::make()
                            ->schema([
                                TextInput::make('debit')
                                    ->numeric()
                                    ->default(0.00)
                                    ->prefix('₹')
                                    ->requiredIf('credit', fn ($state) => empty($state))
                                    ->rules(['nullable', 'min:0', 'regex:/^\d{1,6}(\.\d{0,2})?$/']),

                                TextInput::make('credit')
                                    ->numeric()
                                    ->default(0.00)
                                    ->prefix('₹')
                                    ->requiredIf('debit', fn ($state) => empty($state))
                                    ->rules(['nullable', 'min:0', 'regex:/^\d{1,6}(\.\d{0,2})?$/']),
                            ])
                            ->columnSpanFull()
                            ->columns(2),
                    ])->columnSpanFull(),

                Section::make('Advanced Options')
                    ->collapsed()
                    ->schema([
                        Select::make('ledgerable_type')
                            ->options([
                                'App\\Models\\Invoice' => 'Invoice',
                                'App\\Models\\Payment' => 'Payment',
                                'App\\Models\\Expense' => 'Expense',
                            ])
                            ->required()
                            ->label('Linked Model Type'),

                        TextInput::make('ledgerable_id')
                            ->required()
                            ->numeric()
                            ->label('Linked Model ID'),

                        Grid::make()
                            ->schema([
                                Toggle::make('is_reconciled')
                                    ->label('Reconciled?')
                                    ->helperText('Check if this ledger entry has been reconciled.'),

                                Toggle::make('is_active')
                                    ->label('Active?')
                                    ->default(true),

                                Toggle::make('is_system')
                                    ->label('System Entry?')
                                    ->default(false)
                                    ->helperText('Used for internal, system-generated entries.'),
                            ])
                            ->columnSpanFull()
                            ->columns(3),
                    ])
                    ->columnSpanFull()
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->date()
                    ->sortable()
                    ->label('Date'),

                TextColumn::make('chartOfAccount.name')
                    ->label('Account')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('reference')
                    ->label('Reference')
                    ->searchable(),

                TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('debit')
                    ->money('INR', locale: 'en_IN')
                    ->sortable()
                    ->label('Debit'),

                TextColumn::make('credit')
                    ->money('INR', locale: 'en_IN')
                    ->sortable()
                    ->label('Credit'),

                IconColumn::make('is_reconciled')
                    ->label('Reconciled')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_system')
                    ->label('System Entry')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_reconciled')
                    ->label('Reconciled')
                    ->indicator('Reconciled')
                    ->boolean(),

                TernaryFilter::make('is_active')
                    ->label('Active')
                    ->indicator('Active')
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
                ApprovalAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    
                        BulkApprovalAction::make(),

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
            'index' => ListLedgers::route('/'),
            'create' => CreateLedger::route('/create'),
            'edit' => EditLedger::route('/{record}/edit'),
        ];
    }
}
