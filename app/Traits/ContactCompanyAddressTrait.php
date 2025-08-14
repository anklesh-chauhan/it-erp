<?php

namespace App\Traits;

use Filament\Schemas\Components\Grid;
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
    use ContactDetailsTrait;
    use CompanyDetailsTrait;
    use AddressDetailsTrait;

    protected static function resolveModelClass(): string
    {
        return method_exists(static::class, 'getModel') ? static::getModel() : Lead::class;
    }
    // Common form schema
    public static function getCommonFormSchema(): array
    {
        return [
            Grid::make(3)
                ->schema([
                    ...self::getContactDetailsTraitField(),
                    ...self::getCompanyDetailsTraitField(),
                    ...self::getAddressDetailsTraitField(),
                ]),
        ];
    }
}
