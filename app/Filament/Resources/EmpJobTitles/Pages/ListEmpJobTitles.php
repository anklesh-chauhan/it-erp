<?php

namespace App\Filament\Resources\EmpJobTitles\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\EmpJobTitles\EmpJobTitleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmpJobTitles extends ListRecords
{
    protected static string $resource = EmpJobTitleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
