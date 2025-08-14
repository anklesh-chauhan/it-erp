<?php

namespace App\Filament\Resources\VisitTypes\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\VisitTypes\VisitTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVisitType extends EditRecord
{
    protected static string $resource = VisitTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
