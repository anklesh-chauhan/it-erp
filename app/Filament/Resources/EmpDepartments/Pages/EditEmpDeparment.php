<?php

namespace App\Filament\Resources\EmpDepartments\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\EmpDepartments\EmpDepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmpDeparment extends EditRecord
{
    protected static string $resource = EmpDepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
