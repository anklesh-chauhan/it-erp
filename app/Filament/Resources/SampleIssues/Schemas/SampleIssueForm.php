<?php

namespace App\Filament\Resources\SampleIssues\Schemas;

use App\Enums\SampleIssueStatus;
use App\Enums\SampleRequestStatus;
use App\Models\LocationMaster;
use App\Models\NumberSeries;
use App\Models\SampleIssue;
use App\Models\SampleRequest;
use App\Models\SampleRequestLine;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class SampleIssueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Issue Details')
                    ->columns(4)
                    ->schema([
                        TextInput::make('document_number')
                            ->label('Issue Number')
                            ->default(fn (): string => NumberSeries::getNextNumber(SampleIssue::class))
                            ->disabled()
                            ->dehydrated(),

                        DatePicker::make('issue_date')
                            ->default(now()->toDateString())
                            ->required(),

                        Select::make('status')
                            ->options(SampleIssueStatus::class)
                            ->default(SampleIssueStatus::Draft)
                            ->disabled()
                            ->dehydrated(),

                        Select::make('sample_request_id')
                            ->label('Approved Request')
                            ->options(fn (): array => SampleRequest::query()
                                ->whereIn('status', [
                                    SampleRequestStatus::Approved,
                                    SampleRequestStatus::PartiallyIssued,
                                ])
                                ->orderByDesc('request_date')
                                ->pluck('document_number', 'id')
                                ->all())
                            ->default(fn (): ?int => request()->integer('sample_request_id') ?: null)
                            ->searchable()
                            ->live()
                            ->required()
                            ->afterStateUpdated(function (?string $state, Set $set): void {
                                if (! $state) {
                                    return;
                                }

                                $request = SampleRequest::query()->with('lines.item')->find($state);

                                if ($request === null) {
                                    return;
                                }

                                $set('to_location_id', $request->location_master_id);
                                $set('lines', $request->lines
                                    ->filter(fn (SampleRequestLine $line): bool => $line->remainingApprovedQuantity() > 0)
                                    ->map(fn (SampleRequestLine $line): array => [
                                        'sample_request_line_id' => $line->id,
                                        'item_master_id' => $line->item_master_id,
                                        'quantity' => $line->remainingApprovedQuantity(),
                                        'unit_cost' => $line->item?->purchase_price,
                                    ])
                                    ->values()
                                    ->all());
                            }),

                        Select::make('from_location_id')
                            ->label('Warehouse / Source')
                            ->options(fn (): array => self::locationOptions())
                            ->searchable()
                            ->required()
                            ->different('to_location_id'),

                        Select::make('to_location_id')
                            ->label('Representative Location')
                            ->options(fn (): array => self::locationOptions())
                            ->searchable()
                            ->required()
                            ->different('from_location_id'),

                        Textarea::make('notes')->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Approved Items')
                    ->schema([
                        Repeater::make('lines')
                            ->relationship()
                            ->columns(5)
                            ->schema([
                                Select::make('sample_request_line_id')
                                    ->label('Request Line')
                                    ->options(function (Get $get): array {
                                        $requestId = $get('../../sample_request_id');

                                        if (! $requestId) {
                                            return [];
                                        }

                                        return SampleRequestLine::query()
                                            ->where('sample_request_id', $requestId)
                                            ->with('item')
                                            ->get()
                                            ->filter(fn (SampleRequestLine $line): bool => $line->remainingApprovedQuantity() > 0)
                                            ->mapWithKeys(fn (SampleRequestLine $line): array => [
                                                $line->id => ($line->item?->item_name ?? 'Item').' (Remaining: '.$line->remainingApprovedQuantity().')',
                                            ])
                                            ->all();
                                    })
                                    ->searchable()
                                    ->live()
                                    ->required()
                                    ->columnSpan(2)
                                    ->afterStateUpdated(function (?string $state, Set $set): void {
                                        $requestLine = $state ? SampleRequestLine::query()->with('item')->find($state) : null;

                                        if ($requestLine === null) {
                                            return;
                                        }

                                        $set('item_master_id', $requestLine->item_master_id);
                                        $set('quantity', $requestLine->remainingApprovedQuantity());
                                        $set('unit_cost', $requestLine->item?->purchase_price);
                                    }),

                                Select::make('item_master_id')
                                    ->relationship('item', 'item_name')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->minValue(0.001)
                                    ->step(0.001)
                                    ->required(),

                                TextInput::make('unit_cost')
                                    ->numeric(),

                                Textarea::make('remarks')->rows(1)->columnSpanFull(),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    protected static function locationOptions(): array
    {
        return LocationMaster::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->all();
    }
}
