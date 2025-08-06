<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalesDocumentPreferenceResource\Pages;
use App\Filament\Resources\SalesDocumentPreferenceResource\RelationManagers;
use App\Models\SalesDocumentPreference;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalesDocumentPreferenceResource extends Resource
{
    protected static ?string $model = SalesDocumentPreference::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationGroup = 'Sales';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('PDF Preferences')
                    ->schema([
                        Forms\Components\Toggle::make('attach_pdf_in_email')
                            ->label('Attach PDF with email')
                            ->default(true),
                        Forms\Components\Toggle::make('encrypt_pdf')
                            ->label('Encrypt PDF before sending'),
                    ]),

                Forms\Components\Section::make('Discount Settings')
                    ->schema([
                        Forms\Components\Select::make('discount_level')
                            ->options([
                                'none' => 'No Discount',
                                'line_item' => 'At Line Item Level',
                                'transaction' => 'At Transaction Level',
                            ])
                            ->required(),
                    ]),

                Forms\Components\Section::make('Additional Charges')
                    ->schema([
                        Forms\Components\Toggle::make('include_adjustments')
                            ->label('Include Adjustments'),
                        Forms\Components\Toggle::make('include_shipping_charges')
                            ->label('Include Shipping Charges'),
                    ]),

                Forms\Components\Section::make('Tax Settings')
                    ->schema([
                        Forms\Components\Select::make('tax_mode')
                            ->options([
                                'inclusive' => 'Tax Inclusive',
                                'exclusive' => 'Tax Exclusive',
                                'both' => 'Tax Inclusive or Tax Exclusive',
                            ])
                            ->required(),
                    ]),

                Forms\Components\Section::make('Rounding Settings')
                    ->schema([
                        Forms\Components\Select::make('rounding_option')
                            ->options([
                                'none' => 'No Rounding',
                                'nearest' => 'Round off to nearest whole number',
                            ])
                            ->required(),
                    ]),

                Forms\Components\Section::make('Sales & Expense Settings')
                    ->schema([
                        Forms\Components\Toggle::make('enable_salesperson')
                            ->label('Enable Salesperson Field'),
                        Forms\Components\Toggle::make('enable_billable_expenses')
                            ->label('Enable Billable Expenses'),
                        Forms\Components\TextInput::make('default_markup_percentage')
                            ->label('Default Markup (%)')
                            ->suffix('%')
                            ->numeric()
                            ->step(0.01),
                    ]),

                Forms\Components\Section::make('Document Copies')
                    ->schema([
                        Forms\Components\Select::make('document_copy_type')
                            ->label('Document Copy Format')
                            ->options([
                                'original_duplicate' => 'ORIGINAL / DUPLICATE',
                                'original_duplicate_triplicate' => 'ORIGINAL / DUPLICATE / TRIPLICATE',
                                'original_duplicate_triplicate_quadruplicate' => 'ORIGINAL / DUPLICATE / TRIPLICATE / QUADRUPLICATE',
                                'original_duplicate_triplicate_quadruplicate_quintuplicate' => 'ORIGINAL / DUPLICATE / TRIPLICATE / QUADRUPLICATE / QUINTUPLICATE',
                                'two_copies' => 'Two Copies',
                                'three_copies' => 'Three Copies',
                                'four_five_copies' => 'Four / Five Copies',
                            ])
                            ->required(),
                    ]),

                Forms\Components\Section::make('Print Preferences')
                    ->schema([
                        Forms\Components\Textarea::make('default_print_preferences')
                            ->label('Default Print Preferences (JSON)')
                            ->rows(4)
                            ->hint('Optional: Store advanced print options in JSON format')
                            ->placeholder('{ "copies": 2, "layout": "A4" }'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('attach_pdf_in_email')->boolean(),
                Tables\Columns\IconColumn::make('encrypt_pdf')->boolean(),
                Tables\Columns\TextColumn::make('discount_level')->label('Discount'),
                Tables\Columns\TextColumn::make('tax_mode')->label('Tax Mode'),
                Tables\Columns\TextColumn::make('rounding_option')->label('Rounding'),
                Tables\Columns\IconColumn::make('enable_salesperson')->label('Salesperson')->boolean(),
                Tables\Columns\TextColumn::make('default_markup_percentage')->suffix('%')->sortable(),
            ])
            ->defaultSort('id', 'asc')
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
            
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
            'index' => Pages\ListSalesDocumentPreferences::route('/'),
            // 'create' => Pages\CreateSalesDocumentPreference::route('/create'),
            'edit' => Pages\EditSalesDocumentPreference::route('/{record}/edit'),
        ];
    }
}
