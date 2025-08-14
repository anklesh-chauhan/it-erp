<?php

namespace App\Filament\Resources\EmpGrades\Pages;

use App\Filament\Resources\EmpGrades\EmpGradeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmpGrade extends CreateRecord
{
    protected static string $resource = EmpGradeResource::class;
}
