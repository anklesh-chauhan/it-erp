<?php

namespace App\Filament\Resources\Patches\Pages;

use App\Filament\Resources\Patches\PatchResource;
use Filament\Actions\DeleteAction;
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

    // public function hasCombinedRelationManagerTabsWithContent(): bool
    // {
    //     return true;
    // }

    public static function getTabComponent(Model $ownerRecord, string $pageClass): Tab
    {
        return Tab::make('Assigned Customers')
            ->badge($ownerRecord->companies()->count())
            ->badgeColor('info')
            ->badgeTooltip('The number of customers in this patch')
            ->icon('heroicon-m-document-text');
    }
}
