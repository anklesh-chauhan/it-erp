<?php

namespace App\Filament\Resources\LeadCustomFields\Pages;

use App\Filament\Resources\LeadCustomFields\LeadCustomFieldResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLeadCustomField extends CreateRecord
{
    protected static string $resource = LeadCustomFieldResource::class;
}
