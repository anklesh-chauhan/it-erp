<?php

namespace App\Filament\Resources\LeadActivityResource\Pages;

use App\Filament\Resources\LeadActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeadActivity extends EditRecord
{
    protected static string $resource = LeadActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
