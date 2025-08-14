<?php

namespace App\Filament\Resources\LeadSources\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\LeadSources\LeadSourceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeadSource extends EditRecord
{
    protected static string $resource = LeadSourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
