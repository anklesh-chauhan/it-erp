<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalesOrderResource\Pages;
use App\Filament\Resources\SalesOrderResource\RelationManagers;
use App\Models\SalesOrder;
use App\Models\SalesInvoice;
use App\Models\NumberSeries;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Traits\SalesDocumentResourceTrait;
use Illuminate\Support\Str;

class SalesOrderResource extends Resource
{
    use SalesDocumentResourceTrait;

    protected static ?string $model = SalesOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Sales';
    protected static ?int $navigationSort = 20;
    protected static ?string $navigationLabel = 'Sales Orders';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                ...self::getCommonFormFields(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('document_number')
                    ->label('Document No.')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('lead.reference_code')
                    ->label('Lead')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('salesPerson.name')
                    ->label('Sales Person')
                    ->searchable()
                    ->sortable(),

                // Account Master Column
                Tables\Columns\TextColumn::make('accountMaster.name')
                    ->label('Account Master')
                    ->searchable()
                    ->sortable(),

                // Contact Detail Column
                Tables\Columns\TextColumn::make('contactDetail.full_name')
                    ->label('Contact')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('INR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('currency')
                    ->label('Currency')
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_terms')
                    ->label('Payment Terms')
                    ->limit(20),

                Tables\Columns\TextColumn::make('shipping_method')
                    ->label('Shipping Method')
                    ->limit(20),

                Tables\Columns\IconColumn::make('rejected_at')
                    ->label('Rejected')
                    ->boolean(),

                Tables\Columns\IconColumn::make('canceled_at')
                    ->label('Canceled')
                    ->boolean(),

                Tables\Columns\IconColumn::make('sent_at')
                    ->label('Sent')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('sales_person_id')
                    ->label('Sales Person')
                    ->relationship('salesPerson', 'name')
                    ->searchable(),

                Tables\Filters\SelectFilter::make('account_master_id')
                    ->label('Account Master')
                    ->relationship('accountMaster', 'name')
                    ->searchable(),

                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('date', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->whereDate('date', '<=', $data['until']));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('createInvoice')
                    ->label('Create Invoice')
                    ->icon('heroicon-o-document-text')
                    ->requiresConfirmation()
                    ->color('success')
                    ->action(function (\App\Models\SalesOrder $record, $livewire) {
                        // Copy relevant data from Sales Order to Sales Invoice
                        $invoice = SalesInvoice::create([
                            'sales_order_id' => $record->id,
                            'document_number' => NumberSeries::getNextNumber(SalesInvoice::class),
                            'date' => now(),
                            'lead_id' => $record->lead_id,
                            'sales_person_id' => $record->sales_person_id,
                            'contact_detail_id' => $record->contact_detail_id,
                            'account_master_id' => $record->account_master_id,
                            'billing_address_id' => $record->billing_address_id,
                            'shipping_address_id' => $record->shipping_address_id,
                            'currency' => $record->currency,
                            'payment_terms' => $record->payment_terms,
                            'shipping_method' => $record->shipping_method,
                            'total' => $record->total,
                            'status' => 'draft', // or your default status
                        ]);

                        // Copy item lines (if you have a relation like salesOrderItems or similar)
                        foreach ($record->items as $item) {
                            $invoice->items()->create([
                                'item_master_id' => $item->item_master_id,
                                'quantity' => $item->quantity,
                                'price' => $item->price,
                                'discount' => $item->discount,
                                'tax' => $item->tax,
                                'total' => $item->total,
                            ]);
                        }

                        // Redirect to the edit page of the new invoice
                        return redirect(route('filament.admin.resources.sales-invoices.edit', $invoice));
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSalesOrders::route('/'),
            'create' => Pages\CreateSalesOrder::route('/create'),
            'edit' => Pages\EditSalesOrder::route('/{record}/edit'),
        ];
    }
}
