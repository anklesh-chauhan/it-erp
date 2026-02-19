<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms; // Required for actions to work
use Filament\Forms\Contracts\HasForms;

class VisitMobileActionBar extends Widget implements HasForms, HasActions
{
    use InteractsWithForms, InteractsWithActions;

    protected string $view = 'filament.widgets.visit-mobile-action-bar';

    protected static bool $isLazy = false;

    public function saveAction(): Action
    {
        return Action::make('save') // Internal name
            ->label('Save Visit')
            ->color('primary')
            ->icon('heroicon-m-check')
            ->action(fn () => $this->dispatch('trigger-save-visit'))
            ->extraAttributes([
                'class' => 'w-full justify-center py-3',
            ]);
    }
}


