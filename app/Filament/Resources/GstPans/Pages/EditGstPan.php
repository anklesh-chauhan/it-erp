<?php

namespace App\Filament\Resources\GstPanResource\Pages;

use App\Filament\Resources\GstPanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGstPan extends EditRecord
{
    protected static string $resource = GstPanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
