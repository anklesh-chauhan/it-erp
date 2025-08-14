<?php

namespace App\Filament\Resources\EmpDivisions\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\EmpDivisions\EmpDivisionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmpDivision extends EditRecord
{
    protected static string $resource = EmpDivisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
