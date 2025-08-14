<?php

namespace App\Filament\Resources\Attachments\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Attachments\AttachmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttachment extends EditRecord
{
    protected static string $resource = AttachmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
