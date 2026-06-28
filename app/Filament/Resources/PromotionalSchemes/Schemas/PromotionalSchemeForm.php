<?php

namespace App\Filament\Resources\PromotionalSchemes\Schemas;

use App\Enums\PromotionalBenefitType;
use App\Enums\PromotionalSchemeAppliesTo;
use App\Enums\PromotionalSchemeStatus;
use App\Enums\PromotionalSchemeType;
use App\Models\AccountMaster;
use App\Models\ItemMaster;
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

class PromotionalSchemeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Scheme Details')
                    ->columns(3)
                    ->schema([
                        TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50),

                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Select::make('scheme_type')
                            ->options(PromotionalSchemeType::class)
                            ->required()
                            ->live(),

                        Select::make('status')
                            ->options(PromotionalSchemeStatus::class)
                            ->default(PromotionalSchemeStatus::Draft)
                            ->required(),

                        DatePicker::make('valid_from'),
                        DatePicker::make('valid_to'),

                        TextInput::make('min_order_value')
                            ->label('Minimum Order Value')
                            ->numeric()
                            ->prefix('₹'),

                        Textarea::make('description')
                            ->columnSpanFull(),
                    ])->columnSpanFull(),

                Section::make('Applicability')
                    ->columns(2)
                    ->schema([
                        Select::make('applies_to')
                            ->options(PromotionalSchemeAppliesTo::class)
                            ->default(PromotionalSchemeAppliesTo::Global->value)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set): void {
                                $set('applies_to_id', null);
                            }),

                        Select::make('applies_to_id')
                            ->label('Target')
                            ->options(function (Get $get): array {
                                return match (self::appliesToValue($get('applies_to'))) {
                                    PromotionalSchemeAppliesTo::Customer->value => AccountMaster::query()
                                        ->orderBy('name')
                                        ->pluck('name', 'id')
                                        ->all(),
                                    PromotionalSchemeAppliesTo::Territory->value => Territory::query()
                                        ->orderBy('name')
                                        ->pluck('name', 'id')
                                        ->all(),
                                    PromotionalSchemeAppliesTo::Item->value => ItemMaster::query()
                                        ->whereNull('parent_id')
                                        ->orderBy('item_name')
                                        ->pluck('item_name', 'id')
                                        ->all(),
                                    default => [],
                                };
                            })
                            ->searchable()
                            ->visible(fn (Get $get): bool => self::hasSpecificTarget($get('applies_to')))
                            ->required(fn (Get $get): bool => self::hasSpecificTarget($get('applies_to'))),
                    ])->columnSpanFull(),

                Section::make('Benefits')
                    ->description('Structured promotional benefits beyond line-level discounts.')
                    ->schema([
                        Repeater::make('benefits')
                            ->relationship()
                            ->columns(4)
                            ->schema([
                                Select::make('benefit_type')
                                    ->options(PromotionalBenefitType::class)
                                    ->required(),

                                Select::make('item_master_id')
                                    ->label('Item')
                                    ->options(fn (): array => ItemMaster::query()
                                        ->whereNull('parent_id')
                                        ->orderBy('item_name')
                                        ->pluck('item_name', 'id')
                                        ->all())
                                    ->searchable(),

                                TextInput::make('buy_quantity')
                                    ->numeric()
                                    ->minValue(0),

                                TextInput::make('get_quantity')
                                    ->numeric()
                                    ->minValue(0),

                                TextInput::make('discount_value')
                                    ->numeric()
                                    ->minValue(0),

                                TextInput::make('min_quantity')
                                    ->numeric()
                                    ->minValue(0),

                                TextInput::make('max_quantity')
                                    ->numeric()
                                    ->minValue(0),

                                Textarea::make('remarks')
                                    ->rows(1)
                                    ->columnSpanFull(),
                            ])
                            ->columnSpanFull(),
                    ])->columnSpanFull(),
            ]);
    }

    private static function appliesToValue(mixed $appliesTo): ?string
    {
        if ($appliesTo instanceof PromotionalSchemeAppliesTo) {
            return $appliesTo->value;
        }

        return is_string($appliesTo) ? $appliesTo : null;
    }

    private static function hasSpecificTarget(mixed $appliesTo): bool
    {
        $appliesTo = self::appliesToValue($appliesTo);

        return $appliesTo !== null && $appliesTo !== PromotionalSchemeAppliesTo::Global->value;
    }
}
