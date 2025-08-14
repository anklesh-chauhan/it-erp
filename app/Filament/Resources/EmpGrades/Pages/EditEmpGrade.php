<?php

namespace App\Filament\Resources\EmpGrades\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\EmpGrades\EmpGradeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmpGrade extends EditRecord
{
    protected static string $resource = EmpGradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
