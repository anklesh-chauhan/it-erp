<?php

namespace App\Filament\Resources\ContactDetails\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\ContactDetails\ContactDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContactDetail extends EditRecord
{
    protected static string $resource = ContactDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
