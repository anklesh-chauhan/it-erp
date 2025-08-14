<?php

namespace App\Filament\Resources\EmpGrades\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\EmpGrades\EmpGradeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmpGrades extends ListRecords
{
    protected static string $resource = EmpGradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
