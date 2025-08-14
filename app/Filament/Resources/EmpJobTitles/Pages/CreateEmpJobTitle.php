<?php

namespace App\Filament\Resources\EmpJobTitles\Pages;

use App\Filament\Resources\EmpJobTitles\EmpJobTitleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmpJobTitle extends CreateRecord
{
    protected static string $resource = EmpJobTitleResource::class;
}
