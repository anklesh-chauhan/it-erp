<?php

namespace App\Filament\Resources\EmpJobTitleResource\Pages;

use App\Filament\Resources\EmpJobTitleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmpJobTitle extends EditRecord
{
    protected static string $resource = EmpJobTitleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
