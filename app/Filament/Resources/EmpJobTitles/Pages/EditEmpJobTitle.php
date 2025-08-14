<?php

namespace App\Filament\Resources\EmpJobTitles\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\EmpJobTitles\EmpJobTitleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmpJobTitle extends EditRecord
{
    protected static string $resource = EmpJobTitleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
