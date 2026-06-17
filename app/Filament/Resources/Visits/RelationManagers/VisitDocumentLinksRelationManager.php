<?php

namespace App\Filament\Resources\Visits\RelationManagers;

use App\Filament\Resources\Visits\VisitResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VisitDocumentLinksRelationManager extends RelationManager
{
    protected static string $relationship = 'visitDocumentLinks';

    public function table(Table $table): Table
    {
        return $table

            ->columns([

                TextColumn::make('documentable_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(
                        fn (string $state) => class_basename($state)
                    ),

                TextColumn::make('documentable.document_number')
                    ->label('Document No')
                    ->searchable(),

                TextColumn::make('documentable.status')
                    ->badge(),

                TextColumn::make('documentable.total')
                    ->label('Amount')
                    ->money('INR'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime(),
            ])

            ->recordActions([
                Action::make('open')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(function ($record) {

                        $document = $record->documentable;

                        return match (get_class($document)) {

                            \App\Models\Quote::class =>
                                route(
                                    'filament.admin.resources.quotes.edit',
                                    $document
                                ),

                            \App\Models\SalesOrder::class =>
                                route(
                                    'filament.admin.resources.sales-orders.edit',
                                    $document
                                ),

                            default => '#',
                        };
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

}
