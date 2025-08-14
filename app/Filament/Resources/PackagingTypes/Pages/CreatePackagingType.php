<?php

namespace App\Filament\Resources\PackagingTypes\Pages;

use App\Filament\Resources\PackagingTypes\PackagingTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePackagingType extends CreateRecord
{
    protected static string $resource = PackagingTypeResource::class;
}
