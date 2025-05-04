<?php

namespace App\Traits;

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
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use App\Models\ItemMaster;
use App\Models\NumberSeries;
use Illuminate\Support\Facades\Auth;

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
            Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Select::make('owner_id')
                    ->relationship('owner', 'name')
                    ->default(fn () => Auth::id())
                    ->required()
                    ->label('Owner'),
                Forms\Components\Select::make('type_master_id')
                    ->label('Account Type')
                    ->options(
                        \App\Models\TypeMaster::query()
                            ->where('typeable_type', \App\Models\AccountMaster::class)
                            ->pluck('name', 'id')
                    )
                    ->required()
                    ->searchable()
                    ->nullable() // Allow null if no type is selected
                    ->helperText('Leave blank for default sequence.')
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function (callable $set, $state, $component) {
                        // Get the current state of type_master_id
                        $typeMasterId = $state;
                        // Fetch the next number based on the selected type_master_id
                        $nextNumber = NumberSeries::getNextNumber(AccountMaster::class, $typeMasterId);
                        // Set the account_code field with the new number
                        $set('account_code', $nextNumber);
                    }),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\TextInput::make('account_code')
                    ->disabled() // Prevent manual edits
                    ->maxLength(255)
                    ->live() // Make it reactive to reflect updates
                    ->dehydrated(false) // Prevent sending initial default to server
                    ->default(''), // Initial empty value (will be overridden by type_master_id update)


                        Forms\Components\TextInput::make('phone_number')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('secondary_email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('website')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('no_of_employees')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('twitter')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('linked_in')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('annual_revenue')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('sic_code')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('ticker_symbol')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('industry_type_id')
                            ->relationship('industryType', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('region_id')
                            ->relationship('region', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('ref_dealer_contact')
                            ->relationship('dealerName', 'id') // Use 'id' here for relationship
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->FullName),
                        Forms\Components\TextInput::make('commission')
                            ->numeric()
                            ->suffix('%'),
                        Forms\Components\TextInput::make('alias')
                            ->maxLength(255),
                        Forms\Components\Select::make('parent_id')
                            ->relationship('parent', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('rating_type_id')
                            ->relationship('ratingType', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('account_ownership_id')
                            ->relationship('accountOwnership', 'name')
                            ->searchable()
                            ->preload(),
                    ]),
        ];

    }
}
