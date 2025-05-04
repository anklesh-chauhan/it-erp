<?php

namespace App\Filament\Resources\TermsTypeResource\Pages;

use App\Filament\Resources\TermsTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTermsTypes extends ListRecords
{
    protected static string $resource = TermsTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
