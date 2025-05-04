<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuoteResource\Pages;
use App\Filament\Resources\QuoteResource\RelationManagers;
use App\Models\Quote;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Traits\SalesDocumentResourceTrait;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class QuoteResource extends Resource
{
    use SalesDocumentResourceTrait;

    protected static ?string $model = Quote::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Sales';
    protected static ?int $navigationSort = 10;

    protected static function resolveModelClass(): string
    {
        return method_exists(static::class, 'getModel') ? static::getModel() : \App\Models\Quote::class;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Common fields for all sales documents
                ...self::getCommonFormFields(),

                Forms\Components\DatePicker::make('expiration_date'),
                Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'sent' => 'Sent',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                        'canceled' => 'Canceled',
                    ])
                    ->default('draft') // Set the default value
                    ->required()
                    ->label('Status'),
                Forms\Components\DatePicker::make('accepted_at'),

            ]);

            dd($form->getComponents());
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

                Tables\Columns\TextColumn::make('company.name')
                    ->label('Company')
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

                Tables\Columns\TextColumn::make('salesPerson.name')
                    ->label('Sales Person')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('sent_at')
                    ->label('Sent')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                // 📅 Date Range Filter
                Filter::make('date')
                    ->label('Document Date Range')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('date', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->whereDate('date', '<=', $data['until']));
                    }),

                // 🏢 Company Filter
                SelectFilter::make('company_id')
                    ->label('Company')
                    ->relationship('company', 'name')
                    ->searchable()
                    ->preload(),

                // 👤 Contact Person Filter
                SelectFilter::make('contact_detail_id')
                    ->label('Contact')
                    ->relationship('contactDetail', 'first_name')
                    ->searchable()
                    ->preload(),

                // 💼 Sales Person Filter
                SelectFilter::make('sales_person_id')
                    ->label('Sales Person')
                    ->relationship('salesPerson', 'name')
                    ->searchable()
                    ->preload(),

                // 📌 Status Filter
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'sent' => 'Sent',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),

                // ✅ Sent Boolean Filter
                Filter::make('sent_at')
                    ->label('Sent')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('sent_at'))
                    ->toggle(),

                // 💰 Total Amount Range Filter
                Filter::make('total')
                    ->label('Total Amount Range')
                    ->form([
                        Forms\Components\TextInput::make('min')->numeric()->placeholder('Min'),
                        Forms\Components\TextInput::make('max')->numeric()->placeholder('Max'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['min'], fn ($q) => $q->where('total', '>=', $data['min']))
                            ->when($data['max'], fn ($q) => $q->where('total', '<=', $data['max']));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListQuotes::route('/'),
            'create' => Pages\CreateQuote::route('/create'),
            'edit' => Pages\EditQuote::route('/{record}/edit'),
        ];
    }
}
