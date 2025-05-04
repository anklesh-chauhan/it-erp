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


trait ContactCompanyAddressTrait
{
    use \App\Traits\ContactDetailsTrait;
    use \App\Traits\CompanyDetailsTrait;
    use \App\Traits\AddressDetailsTrait;

    protected static function resolveModelClass(): string
    {
        return method_exists(static::class, 'getModel') ? static::getModel() : \App\Models\Lead::class;
    }
    // Common form schema
    public static function getCommonFormSchema(): array
    {
        return [
            Forms\Components\Grid::make(3)
                ->schema([
                    ...self::getContactDetailsTraitField(),
                    ...self::getCompanyDetailsTraitField(),
                    ...self::getAddressDetailsTraitField(),
                ]),
        ];
    }
}
