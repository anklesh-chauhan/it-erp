<?php

namespace App\Filament\Resources\EmpDepartments\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\EmpDepartments\EmpDepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmpDeparments extends ListRecords
{
    protected static string $resource = EmpDepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
