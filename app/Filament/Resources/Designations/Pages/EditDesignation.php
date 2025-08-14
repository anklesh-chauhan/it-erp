<?php

namespace App\Filament\Resources\Designations\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Designations\DesignationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDesignation extends EditRecord
{
    protected static string $resource = DesignationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
