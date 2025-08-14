<?php

namespace App\Filament\Resources\Deals\Pages;

use App\Filament\Resources\Deals\DealResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDeal extends CreateRecord
{
    protected static string $resource = DealResource::class;
}
