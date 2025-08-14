<?php

namespace App\Filament\Resources\VisitTypes\Pages;

use App\Filament\Resources\VisitTypes\VisitTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateVisitType extends CreateRecord
{
    protected static string $resource = VisitTypeResource::class;
}
