<?php

namespace App\Filament\Resources\TermsTypes\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TermsTypes\TermsTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTermsTypes extends ListRecords
{
    protected static string $resource = TermsTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
