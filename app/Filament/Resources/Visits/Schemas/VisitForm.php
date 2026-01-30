<?php

namespace App\Filament\Resources\Visits\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\Repeater;

class VisitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Visit Details')->schema([
                    Group::make()
                    ->schema([
                        Group::make()
                            ->schema([
                                Forms\Components\DatePicker::make('visit_date')->required(),
                                Forms\Components\TimePicker::make('start_time'),
                                Forms\Components\TimePicker::make('end_time'),
                            ])->columns(3),

                        Group::make()
                            ->schema([
                                Forms\Components\Select::make('visit_type')
                                    ->options([
                                        'planned'   => 'Planned',
                                        'unplanned' => 'Unplanned',
                                    ])
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->required(),

                                Forms\Components\Select::make('sales_tour_plan_id')
                                    ->relationship('salesTourPlan', 'id')
                                    ->disabled()
                                    ->dehydrated(false),
                            ])->columns(2),
                    ])->columnSpanFull(),
                ])->columns(2),

                Section::make('Territory & Patch')->schema([
                    Forms\Components\Select::make('territory_id')
                        ->relationship('territory', 'name')
                        ->required()
                        ->disabled()
                        ->dehydrated(false),

                    Forms\Components\Select::make('patch_id')
                        ->relationship(
                            'patch',
                            'name',
                            fn (Builder $query, $get) =>
                                $query->where('territory_id', $get('territory_id'))
                        )
                        ->required()
                        ->disabled()
                        ->dehydrated(false),
                ])->columns(2),

                Forms\Components\Textarea::make('remarks')->columnSpanFull(),

                Section::make('Customer Details')
                    ->schema([
                        Group::make()
                            ->schema([
                                TextEntry::make('customer_name')
                                    ->label('Customer')
                                    ->state(fn ($record) =>
                                        $record?->primaryCompany()?->name ?? '-'
                                    ),

                                TextEntry::make('customer_phone')
                                    ->label('Phone')
                                    ->state(fn ($record) =>
                                        $record?->primaryCompany()?->phone_number ?? '-'
                                    ),

                                TextEntry::make('customer_email')
                                    ->label('Email')
                                    ->state(fn ($record) =>
                                        $record?->primaryCompany()?->email ?? '-'
                                    ),
                            ])->columns(3),

                        Repeater::make('customer_addresses')
                            ->schema([
                                TextEntry::make('address_line')
                                    ->label('Address')
                                    ->state(fn ($state) =>
                                        collect([
                                            $state['street'] ?? null,
                                            $state['area_town'] ?? null,
                                            $state['city'] ?? null,
                                            $state['state'] ?? null,
                                            $state['pin_code'] ?? null,
                                        ])->filter()->implode(', ')
                                    ),

                                TextEntry::make('address_type')
                                    ->label('Type')
                                    ->state(fn ($state) => $state['address_type'] ?? '-'),
                            ])
                            ->columns(2)
                            ->disabled()
                            ->dehydrated(false)
                            ->default(fn ($record) =>
                                $record?->customerAddresses()?->map(fn ($address) => [
                                    'street'  => $address->street,
                                    'area_town'  => $address->area_town,
                                    'city'    => $address->city->name,
                                    'state'   => $address->state->name,
                                    'pin_code' => $address->pin_code,
                                    'address_type'    => $address->address_type,
                                ])->toArray()
                            ),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
