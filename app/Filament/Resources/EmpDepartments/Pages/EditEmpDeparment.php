<?php

namespace App\Filament\Resources\EmpDepartmentResource\Pages;

use App\Filament\Resources\EmpDepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmpDeparment extends EditRecord
{
    protected static string $resource = EmpDepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
