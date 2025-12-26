<?php

namespace App\Traits;

use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use App\Models\TypeMaster;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use App\Models\AccountMaster;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\ContactDetail;
use App\Models\CityPinCode;
use App\Models\Company;
use Filament\Actions\Concerns\HasForm;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use App\Models\ItemMaster;
use App\Models\NumberSeries;
use Illuminate\Support\Facades\Auth;
use Filament\Schemas\Components\Utilities\Get;

trait CreateAccountMasterTrait
{
    /**
     * Get common form fields for SalesDocument.
     *
     * @return array
     */
    public static function getCreateAccountMasterTraitFields(): array
    {
        return [
            Grid::make(3)
                    ->schema([
                        Select::make('owner_id')
                            ->relationship('owner', 'name')
                            ->default(fn () => Auth::id())
                            ->required()
                            ->label('Owner'),

                        Select::make('parent_type_id')
                            ->label('Account Type')
                            ->searchable()
                            ->options(
                                TypeMaster::whereNull('parent_id')
                                    ->where('typeable_type', AccountMaster::class)
                                    ->pluck('name', 'id')
                            )
                            ->required()
                            ->live()
                            ->afterStateHydrated(function (callable $set, callable $get) {

                                $typeMasterId = $get('type_master_id');

                                if (! $typeMasterId) {
                                    return;
                                }

                                $type = TypeMaster::find($typeMasterId);

                                // If subtype → set its parent
                                if ($type?->parent_id) {
                                    $set('parent_type_id', $type->parent_id);
                                } else {
                                    // Parent-only type
                                    $set('parent_type_id', $typeMasterId);
                                }
                            })
                            ->afterStateUpdated(function (callable $set, callable $get, $state) {

                                $hasChildren = TypeMaster::where('parent_id', $state)->exists();

                                if (! $hasChildren) {
                                    $set('type_master_id', $state);

                                    $next = NumberSeries::getNextNumber(AccountMaster::class, $state);
                                    $set('account_code', $next);
                                } else {
                                    $set('type_master_id', null);
                                    $set('account_code', null);
                                }
                            }),

                        Select::make('type_master_id')
                            ->label('Account Sub Type')
                            ->searchable()
                            ->options(fn (Get $get) =>
                                TypeMaster::where('parent_id', $get('parent_type_id'))
                                    ->pluck('name', 'id')
                            )
                            ->visible(fn (Get $get) =>
                                filled($get('parent_type_id')) &&
                                TypeMaster::where('parent_id', $get('parent_type_id'))->exists()
                            )
                            ->required(fn (Get $get) =>
                                TypeMaster::where('parent_id', $get('parent_type_id'))->exists()
                            )
                            ->live()
                            ->afterStateUpdated(function (callable $set, $state) {
                                // 🔥 SUB TYPE SELECTED → USE SUB TYPE FOR NUMBER SERIES
                                $next = NumberSeries::getNextNumber(AccountMaster::class, $state);
                                $set('account_code', $next);
                            }),

                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                            TextInput::make('account_code')
                            ->disabled() // Prevent manual edits
                            ->maxLength(255)
                            ->live() // Make it reactive to reflect updates
                            ->dehydrated(false) // Prevent sending initial default to server
                            ->default(''), // Initial empty value (will be overridden by type_master_id update)

                        TextInput::make('phone_number')
                            ->tel()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('secondary_email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('website')
                            ->url()
                            ->maxLength(255),
                        TextInput::make('no_of_employees')
                            ->maxLength(255),
                        TextInput::make('twitter')
                            ->url()
                            ->maxLength(255),
                        TextInput::make('linked_in')
                            ->url()
                            ->maxLength(255),
                        TextInput::make('annual_revenue')
                            ->maxLength(255),
                        TextInput::make('sic_code')
                            ->maxLength(255),
                        TextInput::make('ticker_symbol')
                            ->maxLength(255),
                        Textarea::make('description')
                            ->columnSpanFull(),
                        Select::make('industry_type_id')
                            ->relationship('industryType', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('region_id')
                            ->relationship('region', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('ref_dealer_contact')
                            ->label('Ref Contact')
                            ->relationship('dealerName', 'id') // Use 'id' here for relationship
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->FullName),
                        TextInput::make('commission')
                            ->numeric()
                            ->suffix('%'),
                        TextInput::make('alias')
                            ->maxLength(255),
                        Select::make('parent_id')
                            ->relationship('parent', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('rating_type_id')
                            ->relationship('ratingType', 'name')
                            ->searchable()
                            ->preload(),

                    ])->columnSpanFull(),
        ];

    }
}
