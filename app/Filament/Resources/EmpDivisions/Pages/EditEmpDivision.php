<?php

namespace App\Filament\Resources\EmpDivisionResource\Pages;

use App\Filament\Resources\EmpDivisionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmpDivision extends EditRecord
{
    protected static string $resource = EmpDivisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
