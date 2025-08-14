<?php

namespace App\Filament\Resources\GstPans\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\GstPans\GstPanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGstPans extends ListRecords
{
    protected static string $resource = GstPanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
