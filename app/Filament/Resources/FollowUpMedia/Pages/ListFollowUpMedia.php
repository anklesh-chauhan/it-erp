<?php

namespace App\Filament\Resources\FollowUpMedia\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\FollowUpMedia\FollowUpMediaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListFollowUpMedia extends ListRecords
{
    protected static string $resource = FollowUpMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('info')
                ->icon('heroicon-o-information-circle')
                ->label('')
                ->tooltip('What is Follow-up Media?')
                ->modalHeading('Follow-up Media')
                ->modalDescription('Follow-up Media defines how a follow-up interaction was conducted.')
                ->modalContent(fn () => view('filament.help.follow-up-media'))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Close'),
            CreateAction::make(),
        ];
    }
}
