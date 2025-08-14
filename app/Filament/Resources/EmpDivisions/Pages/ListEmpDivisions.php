<?php

namespace App\Filament\Resources\EmpDivisions\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\EmpDivisions\EmpDivisionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmpDivisions extends ListRecords
{
    protected static string $resource = EmpDivisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
