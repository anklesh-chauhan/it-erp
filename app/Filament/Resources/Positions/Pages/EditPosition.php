<?php

namespace App\Filament\Resources\PositionResource\Pages;

use App\Filament\Resources\PositionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\EmploymentDetail;
use App\Models\Position;

class EditPosition extends EditRecord
{
    protected static string $resource = PositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('Assign Employees')
                ->action(function () {
                    $assignedIds = $this->form->getState()['assigned_employee_ids'] ?? [];
                    $this->record->employmentDetails()->sync($assignedIds);
                    $this->notify('success', 'Employees assigned successfully.');
                })
                ->requiresConfirmation()
                ->icon('heroicon-o-user-group'),
        ];
    }
}
