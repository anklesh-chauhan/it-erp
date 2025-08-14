<?php

namespace App\Filament\Resources\LeadStatuses\Pages;

use App\Filament\Resources\LeadStatuses\LeadStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLeadStatus extends CreateRecord
{
    protected static string $resource = LeadStatusResource::class;
}
