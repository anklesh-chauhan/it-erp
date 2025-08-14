<?php

namespace App\Filament\Resources\EmpGradeResource\Pages;

use App\Filament\Resources\EmpGradeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmpGrades extends ListRecords
{
    protected static string $resource = EmpGradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
