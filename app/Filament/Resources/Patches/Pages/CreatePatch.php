<?php

namespace App\Filament\Resources\Patches\Pages;

use App\Filament\Resources\Patches\PatchResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePatch extends CreateRecord
{
    protected static string $resource = PatchResource::class;

    protected function afterSave(): void
    {
        $this->record->companies()->sync(
            $this->data['account_master_ids'] ?? []
        );
    }

    protected function afterCreate(): void
    {
        $companyIds = $this->data['__pending_company_ids'] ?? [];

        if (! empty($companyIds)) {
            $this->record->companies()->syncWithoutDetaching($companyIds);
        }
    }
}
