<?php

namespace App\Filament\Resources\AccountMasters\Pages;

use App\Filament\Resources\AccountMasters\AccountMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAccountMaster extends CreateRecord
{
    protected static string $resource = AccountMasterResource::class;
}
