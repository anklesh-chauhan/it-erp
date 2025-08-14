<?php

namespace App\Filament\Resources\GstPans\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\GstPans\GstPanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGstPan extends EditRecord
{
    protected static string $resource = GstPanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
