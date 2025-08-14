<?php

namespace App\Filament\Resources\Taxes\Pages;

use App\Filament\Resources\Taxes\TaxResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTax extends CreateRecord
{
    protected static string $resource = TaxResource::class;
}
