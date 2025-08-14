<?php

namespace App\Filament\Resources\Attachments\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Attachments\AttachmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttachments extends ListRecords
{
    protected static string $resource = AttachmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
