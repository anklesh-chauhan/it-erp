<?php

namespace App\Filament\Resources\SampleRequests\Schemas;

use App\Enums\MarketingCampaignStatus;
use App\Enums\SampleRequestStatus;
use App\Models\Employee;
use App\Models\ItemMaster;
use App\Models\LocationMaster;
use App\Models\MarketingCampaign;
use App\Models\NumberSeries;
use App\Models\SampleRequest;
use App\Models\Territory;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class SampleRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Request Details')
                    ->columns(4)
                    ->schema([
                        TextInput::make('document_number')
                            ->label('Request Number')
                            ->default(fn (): string => NumberSeries::getNextNumber(SampleRequest::class))
                            ->disabled()
                            ->dehydrated(),

                        DatePicker::make('request_date')
                            ->default(now()->toDateString())
                            ->required(),

                        Select::make('status')
                            ->options(SampleRequestStatus::class)
                            ->default(SampleRequestStatus::Draft)
                            ->disabled()
                            ->dehydrated(),

                        Select::make('employee_id')
                            ->label('Representative')
                            ->options(fn (): array => Employee::query()
                                ->where('is_active', true)
                                ->orderBy('first_name')
                                ->get()
                                ->mapWithKeys(fn (Employee $employee): array => [$employee->id => $employee->full_name])
                                ->all())
                            ->searchable()
                            ->required(),

                        Select::make('territory_id')
                            ->options(fn (): array => Territory::query()->orderBy('name')->pluck('name', 'id')->all())
                            ->searchable(),

                        Select::make('location_master_id')
                            ->label('Destination Location')
                            ->options(fn (): array => LocationMaster::query()
                                ->where('is_active', true)
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->all())
                            ->searchable()
                            ->required(),

                        Select::make('campaign_id')
                            ->label('Marketing Campaign')
                            ->options(fn (): array => MarketingCampaign::query()
                                ->where('status', MarketingCampaignStatus::Active)
                                ->orderByDesc('start_date')
                                ->pluck('name', 'id')
                                ->all())
                            ->searchable(),

                        Textarea::make('purpose')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Requested Samples')
                    ->schema([
                        Repeater::make('lines')
                            ->relationship()
                            ->columns(5)
                            ->schema([
                                Select::make('item_master_id')
                                    ->label('Item Type')
                                    ->options(fn (): array => ItemMaster::query()
                                        ->whereNotNull('item_type')
                                        ->whereNull('parent_id')
                                        ->orderBy('item_name')
                                        ->pluck('item_name', 'id')
                                        ->all())
                                    ->searchable()
                                    ->required()
                                    ->columnSpan(2),

                                TextInput::make('quantity_requested')
                                    ->label('Requested')
                                    ->numeric()
                                    ->minValue(0.001)
                                    ->step(0.001)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (?string $state, Set $set, Get $get): void {
                                        if ((float) $get('quantity_approved') > (float) $state) {
                                            $set('quantity_approved', $state);
                                        }
                                    }),

                                TextInput::make('quantity_approved')
                                    ->label('Approved')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.001)
                                    ->lte('quantity_requested')
                                    ->default(0),

                                TextInput::make('quantity_issued')
                                    ->label('Issued')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->default(0),

                                Textarea::make('remarks')
                                    ->rows(1)
                                    ->columnSpanFull(),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
