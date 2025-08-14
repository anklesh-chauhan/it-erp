<?php

namespace App\Filament\Resources\TermsTypes\Pages;

use App\Filament\Resources\TermsTypes\TermsTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTermsType extends CreateRecord
{
    protected static string $resource = TermsTypeResource::class;
}
