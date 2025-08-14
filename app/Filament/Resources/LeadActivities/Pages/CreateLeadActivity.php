<?php

namespace App\Filament\Resources\LeadActivities\Pages;

use App\Filament\Resources\LeadActivities\LeadActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLeadActivity extends CreateRecord
{
    protected static string $resource = LeadActivityResource::class;
}
