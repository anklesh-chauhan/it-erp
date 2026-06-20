<?php

namespace App\Filament\Resources\Visits\Tables;

use App\Models\Visit;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VisitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->withExists('sgipDistribution'))
            ->columns([
                Tables\Columns\TextColumn::make('document_number')->searchable(),
                Tables\Columns\TextColumn::make('visit_date')->date(),
                Tables\Columns\TextColumn::make('employee.name')->label('Employee'),
                Tables\Columns\TextColumn::make('patch.name'),
                Tables\Columns\TextColumn::make('visit_status')->badge(),
                Tables\Columns\TextColumn::make('approval_status')->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('approval_status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),

                Action::make('view_sgip_details')
                    ->label('SGIP Details')
                    ->icon(Heroicon::OutlinedGift)
                    ->color('info')
                    ->modalHeading(fn (Visit $record): string => "Sample / Gift / Input Details for {$record->document_number}")
                    ->modalWidth('4xl')
                    ->slideOver()
                    ->visible(fn (Visit $record): bool => (bool) $record->sgip_distribution_exists)
                    ->schema([
                        Section::make('Distribution Summary')
                            ->columns(3)
                            ->compact()
                            ->schema([
                                TextEntry::make('sgipDistribution.doctor.name')
                                    ->label('Doctor')
                                    ->placeholder('-'),

                                TextEntry::make('sgipDistribution.visit_date')
                                    ->label('Visit Date')
                                    ->date()
                                    ->placeholder('-'),

                                TextEntry::make('sgipDistribution.approval_status')
                                    ->label('Approval Status')
                                    ->badge()
                                    ->placeholder('-'),

                                TextEntry::make('sgipDistribution.user.employee.full_name')
                                    ->label('Sales Employee')
                                    ->placeholder('-'),

                                TextEntry::make('sgipDistribution.territory.name')
                                    ->label('Territory')
                                    ->placeholder('-'),

                                TextEntry::make('sgipDistribution.total_value')
                                    ->label('Total Value')
                                    ->money('INR')
                                    ->placeholder('-'),
                            ]),

                        RepeatableEntry::make('sgipDistribution.items')
                            ->label('Samples / Gifts / Inputs')
                            ->schema([
                                Section::make(fn ($record): string => $record->item?->item_name ?? 'Item')
                                    ->columns(3)
                                    ->compact()
                                    ->schema([
                                        TextEntry::make('item.item_code')
                                            ->label('Item Code')
                                            ->placeholder('-'),

                                        TextEntry::make('item.item_name')
                                            ->label('Item Name')
                                            ->weight('medium')
                                            ->placeholder('-'),

                                        TextEntry::make('item.sku')
                                            ->label('SKU')
                                            ->placeholder('-'),

                                        TextEntry::make('item.brand.name')
                                            ->label('Brand')
                                            ->placeholder('-'),

                                        TextEntry::make('item.category.name')
                                            ->label('Category')
                                            ->placeholder('-'),

                                        TextEntry::make('item.unitOfMeasurement.name')
                                            ->label('UOM')
                                            ->placeholder('-'),

                                        TextEntry::make('quantity')
                                            ->label('Quantity')
                                            ->numeric()
                                            ->placeholder('-'),

                                        TextEntry::make('unit_value')
                                            ->label('Unit Value')
                                            ->money('INR')
                                            ->placeholder('-'),

                                        TextEntry::make('total_value')
                                            ->label('Total Value')
                                            ->money('INR')
                                            ->placeholder('-'),
                                    ]),
                            ])
                            ->contained()
                            ->columns(1),
                    ]),

                Action::make('complete')
                    ->label('Complete')
                    ->visible(fn (Visit $record) => $record->visit_status !== 'completed')
                    ->action(fn (Visit $record) => $record->update(['visit_status' => 'completed'])
                    ),

                Action::make('send_for_approval')
                    ->visible(fn (Visit $record) => $record->visit_status === 'completed')
                    ->action(fn (Visit $record) => $record->update(['approval_status' => 'pending'])
                    ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
