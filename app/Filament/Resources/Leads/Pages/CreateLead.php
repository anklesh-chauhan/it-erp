<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Filament\Resources\LeadResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLead extends CreateRecord
{
    protected static string $resource = LeadResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['custom_fields'])) {
            $data['custom_fields'] = collect($data['custom_fields'])->toArray();
        }

        return $data;
    }
}
