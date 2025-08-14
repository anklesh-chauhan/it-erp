<?php

namespace App\Filament\Resources\EmpDivisionResource\Pages;

use App\Filament\Resources\EmpDivisionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmpDivisions extends ListRecords
{
    protected static string $resource = EmpDivisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
