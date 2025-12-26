<?php

namespace App\Filament\Resources\Patches\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Patches\PatchResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Model;

class EditPatch extends EditRecord
{
    protected static string $resource = PatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $this->record->companies()->sync(
            $this->data['account_master_ids'] ?? []
        );
    }

    // public function hasCombinedRelationManagerTabsWithContent(): bool
    // {
    //     return true;
    // }

    public static function getTabComponent(Model $ownerRecord, string $pageClass): Tab
    {
        return Tab::make('Assigned Customers')
            ->badge($ownerRecord->posts()->count())
            ->badgeColor('info')
            ->badgeTooltip('The number of customers in this patch')
            ->icon('heroicon-m-document-text');
    }

}
