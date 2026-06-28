<?php

namespace App\Filament\Pages;

use App\Enums\ItemType;
use App\Models\SgipDistributionItem;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class DoctorSampleLedger extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|UnitEnum|null $navigationGroup = 'Marketing & Field Sales';

    protected static ?string $navigationLabel = 'Doctor Sample Ledger';

    protected static ?string $title = 'Doctor Sample Ledger';

    protected static ?int $navigationSort = 7;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';

    protected string $view = 'filament.pages.doctor-sample-ledger';

    protected function getTableQuery(): Builder
    {
        return SgipDistributionItem::query()
            ->with([
                'distribution.doctor',
                'distribution.user.employee',
                'distribution.territory',
                'distribution.marketingCampaign',
                'distribution.violations',
                'item',
            ])
            ->whereHas(
                'item',
                fn (Builder $query): Builder => $query->whereIn('item_type', ItemType::cases()),
            )
            ->whereHas(
                'distribution',
                fn (Builder $query): Builder => $query->whereIn('approval_status', ['submitted', 'approved']),
            );
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('distribution.visit_date')
                    ->label('Visit Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('distribution.doctor.name')
                    ->label('Doctor (HCP)')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('distribution.user.employee.full_name')
                    ->label('Representative')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('distribution.territory.name')
                    ->label('Territory')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('distribution.marketingCampaign.name')
                    ->label('Campaign')
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('item.item_type')
                    ->label('Item Type')
                    ->badge()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('item.item_name')
                    ->label('Item')
                    ->searchable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->numeric(decimalPlaces: 3)
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_value')
                    ->money('INR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('distribution.approval_status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'info' => 'submitted',
                        'success' => 'approved',
                    ]),

                Tables\Columns\IconColumn::make('has_violations')
                    ->label('Compliance')
                    ->boolean()
                    ->getStateUsing(fn (SgipDistributionItem $record): bool => $record->distribution?->violations->isNotEmpty() ?? false)
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success'),

                Tables\Columns\TextColumn::make('distribution.inventory_posted_at')
                    ->label('Stock Posted')
                    ->dateTime()
                    ->placeholder('Pending')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('distribution.visit_date', 'desc')
            ->filters([
                Filter::make('visit_date')
                    ->schema([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn (Builder $q, $date): Builder => $q->whereHas(
                                'distribution',
                                fn (Builder $d): Builder => $d->whereDate('visit_date', '>=', $date)
                            ))
                            ->when($data['until'], fn (Builder $q, $date): Builder => $q->whereHas(
                                'distribution',
                                fn (Builder $d): Builder => $d->whereDate('visit_date', '<=', $date)
                            ));
                    }),

                SelectFilter::make('marketing_campaign')
                    ->relationship('distribution.marketingCampaign', 'name'),

                SelectFilter::make('territory')
                    ->relationship('distribution.territory', 'name'),

                Filter::make('violations_only')
                    ->label('Compliance Violations Only')
                    ->query(fn (Builder $query): Builder => $query->whereHas('distribution.violations')),
            ]);
    }
}
