<?php

namespace App\Filament\Resources\IndustryTypeResource\Pages;

use App\Filament\Resources\IndustryTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIndustryType extends EditRecord
{
    protected static string $resource = IndustryTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
