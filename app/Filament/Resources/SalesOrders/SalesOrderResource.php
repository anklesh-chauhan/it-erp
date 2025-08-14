<?php

namespace App\Filament\Resources\SalesOrders;

use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\SalesOrders\Pages\ListSalesOrders;
use App\Filament\Resources\SalesOrders\Pages\CreateSalesOrder;
use App\Filament\Resources\SalesOrders\Pages\EditSalesOrder;
use App\Filament\Resources\SalesOrderResource\Pages;
use App\Filament\Resources\SalesOrderResource\RelationManagers;
use App\Models\SalesOrder;
use App\Models\SalesInvoice;
use App\Models\NumberSeries;
use Filament\Forms;
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

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Sales';
    protected static ?int $navigationSort = 20;
    protected static ?string $navigationLabel = 'Sales Orders';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                ...self::getCommonFormFields(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('document_number')
                    ->label('Document No.')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('date')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('lead.reference_code')
                    ->label('Lead')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('salesPerson.name')
                    ->label('Sales Person')
                    ->searchable()
                    ->sortable(),

                // Account Master Column
                TextColumn::make('accountMaster.name')
                    ->label('Account Master')
                    ->searchable()
                    ->sortable(),

                // Contact Detail Column
                TextColumn::make('contactDetail.full_name')
                    ->label('Contact')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('total')
                    ->label('Total')
                    ->money('INR')
                    ->sortable(),

                TextColumn::make('currency')
                    ->label('Currency')
                    ->sortable(),

                TextColumn::make('payment_terms')
                    ->label('Payment Terms')
                    ->limit(20),

                TextColumn::make('shipping_method')
                    ->label('Shipping Method')
                    ->limit(20),

                IconColumn::make('rejected_at')
                    ->label('Rejected')
                    ->boolean(),

                IconColumn::make('canceled_at')
                    ->label('Canceled')
                    ->boolean(),

                IconColumn::make('sent_at')
                    ->label('Sent')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('sales_person_id')
                    ->label('Sales Person')
                    ->relationship('salesPerson', 'name')
                    ->searchable(),

                SelectFilter::make('account_master_id')
                    ->label('Account Master')
                    ->relationship('accountMaster', 'name')
                    ->searchable(),

                Filter::make('date')
                    ->schema([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('date', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->whereDate('date', '<=', $data['until']));
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('createInvoice')
                    ->label('Create Invoice')
                    ->icon('heroicon-o-document-text')
                    ->requiresConfirmation()
                    ->color('success')
                    ->action(function (SalesOrder $record, $livewire) {
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
            'index' => ListSalesOrders::route('/'),
            'create' => CreateSalesOrder::route('/create'),
            'edit' => EditSalesOrder::route('/{record}/edit'),
        ];
    }
}
