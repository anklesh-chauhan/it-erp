<?php

namespace App\Filament\Resources\GstPanResource\Pages;

use App\Filament\Resources\GstPanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGstPans extends ListRecords
{
    protected static string $resource = GstPanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
