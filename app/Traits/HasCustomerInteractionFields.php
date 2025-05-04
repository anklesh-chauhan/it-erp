<?php

namespace App\Traits;

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
    use \App\Traits\ContactDetailsTrait;
    use \App\Traits\CompanyDetailsTrait;

    protected static function resolveModelClass(): string
    {
        return method_exists(static::class, 'getModel') ? static::getModel() : \App\Models\Lead::class;
    }
    // Common form schema
    public static function getCommonFormSchema(): array
    {
        return [
            Forms\Components\Grid::make(4)
                ->schema([
                    Forms\Components\TextInput::make('reference_code')
                        ->label('Reference Code')
                        ->default(fn () => \App\Models\NumberSeries::getNextNumber(static::resolveModelClass()))
                        ->disabled()
                        ->dehydrated(false),
                    Forms\Components\DatePicker::make('transaction_date')
                        ->label('Transaction Date')
                        ->default(now()->toDateString())
                        ->required(),
                    Forms\Components\Select::make('owner_id')
                        ->relationship('owner', 'name')
                        ->default(fn () => Auth::id())
                        ->required()
                        ->label('Owner'),

                    Forms\Components\Select::make('status_id')
                        ->label('Status')
                        ->options(function () {
                            return static::$statusModel::pluck('name', 'id')->toArray();
                        })
                        ->searchable()
                        ->required(),

                    Forms\Components\Hidden::make('status_type')
                        ->default(static::$statusModel),
                ]),

                // ✅ Contact Details
                ...self::getContactDetailsTraitField(),

                // ✅ Company Details
                ...self::getCompanyDetailsTraitField(),
        ];
    }

    // Common table columns
    public static function getCommonTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('owner.name')
                ->label('Owner')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('status.name')
                ->label('Status')
                ->sortable(),
            Tables\Columns\TextColumn::make('transaction_date')
                ->date()
                ->sortable(),
        ];
    }

    // Common model relationships
    public function owner()
    {
        return $this->belongsTo(\App\Models\User::class, 'owner_id');
    }

    public function contact()
    {
        return $this->belongsTo(\App\Models\ContactDetail::class, 'contact_id');
    }

    public function contactDetail()
    {
        return $this->belongsTo(\App\Models\ContactDetail::class, 'contact_id');
    }


    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class);
    }

    public function address()
    {
        return $this->belongsTo(\App\Models\Address::class);
    }

    public function status()
    {
        return $this->belongsTo(\App\Models\Status::class, 'status_id'); // Abstract status relationship
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
