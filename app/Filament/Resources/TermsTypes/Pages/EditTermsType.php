<?php

namespace App\Filament\Resources\TermsTypeResource\Pages;

use App\Filament\Resources\TermsTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTermsType extends EditRecord
{
    protected static string $resource = TermsTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
