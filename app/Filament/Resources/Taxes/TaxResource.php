<?php

namespace App\Filament\Resources;

use App\Models\Tax;
use Filament\Forms;
use Filament\Tables;
use App\Models\TaxComponent;
use Filament\Resources\Resource;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
// use Filament\Resources\Table; // Remove this incorrect import
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Radio;
use Filament\Tables\Columns\BadgeColumn;
use App\Filament\Resources\TaxResource\Pages;

class TaxResource extends Resource
{
    protected static ?string $model = Tax::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?string $navigationGroup = 'Tax Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(100),

                TextInput::make('total_rate')
                    ->label('Total Tax Rate (%)')
                    ->numeric()
                    ->required()
                    ->step(0.01)
                    ->suffix('%'),

                Toggle::make('is_active')->default(true),

                Repeater::make('components')
                    ->label('Tax Components')
                    ->relationship('components')
                    ->schema([
                        Select::make('type')
                            ->options([
                                'CGST' => 'CGST',
                                'SGST' => 'SGST',
                                'IGST' => 'IGST',
                                'CESS' => 'CESS',
                                'VAT' => 'VAT',
                                'EXCISE' => 'EXCISE',
                                'CUSTOM' => 'CUSTOM',
                            ])
                            ->required(),

                        TextInput::make('rate')
                            ->numeric()
                            ->required()
                            ->step(0.01)
                            ->suffix('%'),
                    ])
                    ->defaultItems(1)
                    ->columns(3)
                    ->columnSpanFull()
                    ->reorderable()
                    ->addActionLabel('Add Component'),
            ]);
    }
    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('total_rate')->label('Rate (%)')->sortable(),
                TextColumn::make('supply_type')->label('Type'),
                TextColumn::make('is_active')
                    ->badge('Active', 'Inactive')
                    ->colors(['success' => true, 'danger' => false]),
                TextColumn::make('is_default')
                    ->badge('Default', 'Not Default')
                    ->colors(['primary' => true]),
            ])
            ->defaultSort('name')
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return []; // No separate relation managers needed
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTaxes::route('/'),
            'create' => Pages\CreateTax::route('/create'),
            'edit' => Pages\EditTax::route('/{record}/edit'),
        ];
    }
}
