<?php

namespace App\Filament\Resources\CustomerPrices\Tables;

use App\Filament\Actions\ApprovalAction;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomerPricesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Customer
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                // Item (shows parent + variant if applicable)
                TextColumn::make('item.item_name')
                    ->label('Item / Variant')
                    ->formatStateUsing(function ($record) {
                        $item = $record->item;
                        if ($item?->parent) {
                            // show "Parent - Variant"
                            return "{$item->parent->item_name} – {$item->variant_name}";
                        }
                        return $item?->item_name ?? '-';
                    })
                    ->searchable()
                    ->sortable(),

                // Price
                TextColumn::make('price')
                    ->label('Price')
                    ->money('INR')
                    ->sortable(),

                // Discount
                TextColumn::make('discount')
                    ->label('Discount')
                    ->suffix('%')
                    ->sortable(),

                // Created & Updated timestamps
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
}
