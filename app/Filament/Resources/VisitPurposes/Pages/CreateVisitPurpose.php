<?php

namespace App\Filament\Resources\VisitPurposes\Pages;

use App\Filament\Resources\VisitPurposes\VisitPurposeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateVisitPurpose extends CreateRecord
{
    protected static string $resource = VisitPurposeResource::class;
}
