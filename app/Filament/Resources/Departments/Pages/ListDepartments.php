<?php

namespace App\Filament\Resources\Departments\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Departments\DepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDepartments extends ListRecords
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
