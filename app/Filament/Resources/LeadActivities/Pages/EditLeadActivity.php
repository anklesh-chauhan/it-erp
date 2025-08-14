<?php

namespace App\Filament\Resources\LeadActivities\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\LeadActivities\LeadActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeadActivity extends EditRecord
{
    protected static string $resource = LeadActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
