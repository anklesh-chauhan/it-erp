<?php

namespace App\Filament\Resources\LeadSources\Pages;

use App\Filament\Resources\LeadSources\LeadSourceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLeadSource extends CreateRecord
{
    protected static string $resource = LeadSourceResource::class;
}
