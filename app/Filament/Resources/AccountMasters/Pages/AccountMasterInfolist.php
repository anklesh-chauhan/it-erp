<?php

namespace App\Filament\Resources\AccountMasters\Pages;

use App\Filament\Resources\AccountMasters\AccountMasterResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
Use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;

class AccountMasterInfolist extends ViewRecord
{
    protected static string $resource = AccountMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Account Information')
                    ->schema([
                        TextEntry::make('name')->label('Account Name'),
                        TextEntry::make('account_code')->label('Account Code'),

                        TextEntry::make('account_sub_type')
                            ->label('Type')
                            ->state(fn ($record) => $record->typeMaster?->name ?? '-'),

                        TextEntry::make('alias')->placeholder('-'),
                        TextEntry::make('ratingType.name')->label('Rating')->placeholder('-'),
                    ])
                    ->columns(3),

                Section::make('Company Contact Information')
                    ->schema([
                       TextEntry::make('phone_number')
                            ->hiddenLabel()
                            ->icon('heroicon-m-phone')
                            ->color('primary')
                            ->url(fn ($state) => $state ? "tel:{$state}" : null)
                            ->placeholder('No phone provided'),

                        TextEntry::make('website')
                            ->hiddenLabel()
                            ->icon('heroicon-m-globe-alt') // Added icon for consistency
                            ->color('primary')
                            ->formatStateUsing(fn () => 'Visit Website')
                            ->url(fn ($state) => $state)
                            ->openUrlInNewTab()
                            ->placeholder('No website'),

                        TextEntry::make('email')
                            ->hiddenLabel()
                            ->icon('heroicon-m-envelope') // Updated icon name for v3/v4
                            ->color('primary')
                            ->url(fn ($state) => $state ? "mailto:{$state}" : null) // Fixed protocol
                            ->placeholder('No email'),

                        TextEntry::make('secondary_email')
                            ->hiddenLabel()
                            ->icon('heroicon-m-envelope')
                            ->color('primary')
                            ->url(fn ($state) => $state ? "mailto:{$state}" : null) // Fixed protocol
                            ->placeholder('No secondary email'),
                    ])
                    ->columns(2),

                Section::make('Addresses')
                    ->schema([
                        RepeatableEntry::make('addresses')
                            ->schema([
                                // 🔹 Address Type as heading
                                TextEntry::make('address_type_heading')
                                    ->hiddenLabel()
                                    ->weight('bold')
                                    ->size('lg')
                                    ->state(fn ($record) =>
                                        $record->addressType?->name ?? 'Address'
                                    ),

                                // 🔹 Full address as body
                                TextEntry::make('full_address')
                                    ->hiddenLabel()
                                    ->color('gray')
                                    ->state(fn ($record) =>
                                        collect([
                                            $record->street,
                                            $record->area_town,
                                            optional($record->city)->name,
                                            optional($record->state)->name,
                                            $record->pin_code,
                                        ])
                                        ->filter()
                                        ->implode(', ')
                                    ),

                            ])
                            ->contained()   // 👈 gives card-like look
                            ->columns(1),   // 👈 vertical stack
                    ])->columns(1),

                Section::make('Contacts Persons')
                    ->schema([
                        RepeatableEntry::make('contactDetails')
                            ->hiddenLabel()
                            ->schema([

                                // 🔹 Contact Name
                                TextEntry::make('full_name')
                                    ->hiddenLabel()
                                    ->state(fn ($record) =>
                                        collect([
                                            $record->full_name,
                                            $record->designation?->name ?? '',
                                        ])->filter()->implode(', ')
                                    ),

                                // 🔹 Phone
                                TextEntry::make('mobile_number')
                                    ->hiddenLabel()
                                    ->color('primary')
                                    ->icon('heroicon-o-phone')
                                    ->url(fn ($state) => $state ? "tel:{$state}" : null),

                                // 🔹 Email
                                TextEntry::make('email')
                                    ->hiddenLabel()
                                    ->color('primary')
                                    ->icon('heroicon-o-envelope')
                                    ->url(fn ($state) => $state ? "mailto:{$state}" : null),

                            ])
                            ->contained()   // card-style UI
                            ->columns(1),   // stacked layout
                    ]),
            ]);
    }


}
