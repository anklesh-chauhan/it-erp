<?php

namespace App\Traits;

use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;
use App\Models\User;
use App\Models\ContactDetail;
use App\Models\Company;
use App\Models\Address;
use App\Models\Status;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\Lead;
use App\Models\CityPinCode;
use App\Models\LeadCustomField;
use App\Models\NumberSeries;
use App\Models\ItemMaster;


trait HasCustomerInteractionFields
{
    use ContactDetailsTrait;
    use AccountMasterDetailsTrait;

    protected static function resolveModelClass(): string
    {
        return method_exists(static::class, 'getModel') ? static::getModel() : Lead::class;
    }
    // Common form schema
    public static function getCommonFormSchema(): array
    {
        return [
            Grid::make(4)
                ->schema([
                    TextInput::make('reference_code')
                        ->label('Reference Code')
                        ->default(fn () => NumberSeries::getNextNumber(static::resolveModelClass()))
                        ->disabled()
                        ->dehydrated(false),
                    DatePicker::make('transaction_date')
                        ->label('Transaction Date')
                        ->default(now()->toDateString())
                        ->required(),
                    Select::make('owner_id')
                        ->relationship('owner', 'name')
                        ->default(fn () => Auth::id())
                        ->required()
                        ->label('Owner'),

                    Select::make('status_id')
                        ->label('Status')
                        ->options(function () {
                            return static::$statusModel::pluck('name', 'id')->toArray();
                        })
                        ->searchable()
                        ->required(),

                    Hidden::make('status_type')
                        ->default(static::$statusModel),
                ]),

                // ✅ Contact Details
                ...self::getContactDetailsTraitField(),

                // // ✅ Company Details
                ...self::getAccountMasterDetailsTraitField(),
        ];
    }

    // Common table columns
    public static function getCommonTableColumns(): array
    {
        return [
            TextColumn::make('owner.name')
                ->label('Owner')
                ->searchable()
                ->sortable(),
            TextColumn::make('status.name')
                ->label('Status')
                ->sortable(),
            TextColumn::make('transaction_date')
                ->date()
                ->sortable(),
        ];
    }

    // Common model relationships
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function contact()
    {
        return $this->belongsTo(ContactDetail::class, 'contact_id');
    }

    public function contactDetail()
    {
        return $this->belongsTo(ContactDetail::class, 'contact_id');
    }


    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id'); // Abstract status relationship
    }

    public function getDisplayNameAttribute()
    {
        if ($this->company) {
            return $this->company->name; // Show company name if available
        } elseif ($this->contactDetail) {
            return $this->contactDetail->full_name; // Otherwise, show contact name
        }
        return 'N/A'; // Default if neither is available
    }


}
