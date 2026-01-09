<?php

namespace App\Filament\Resources\SgipLimits\Pages;

use App\Filament\Resources\SgipLimits\SgipLimitResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSgipLimit extends EditRecord
{
    protected static string $resource = SgipLimitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
