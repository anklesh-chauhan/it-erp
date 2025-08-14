<?php

namespace App\Filament\Resources\IndustryTypes\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\IndustryTypes\IndustryTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIndustryType extends EditRecord
{
    protected static string $resource = IndustryTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
