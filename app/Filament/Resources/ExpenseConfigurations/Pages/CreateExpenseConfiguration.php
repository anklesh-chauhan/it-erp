<?php

namespace App\Filament\Resources\ExpenseConfigurations\Pages;

use App\Filament\Resources\ExpenseConfigurations\ExpenseConfigurationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateExpenseConfiguration extends CreateRecord
{
    protected static string $resource = ExpenseConfigurationResource::class;
}
