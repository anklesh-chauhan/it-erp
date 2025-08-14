<?php

namespace App\Filament\Resources\LeadStatuses\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\LeadStatuses\LeadStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeadStatus extends EditRecord
{
    protected static string $resource = LeadStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
