<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use App\Models\Employee;

class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;

    /**
     * Holds employment detail data temporarily between form mutation and afterSave.
     *
     * @var array
     */
    protected array $employmentDetailData = [];

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Extract employment_detail data
        $employmentDetailData = $data['employment_detail'] ?? [];
        unset($data['employment_detail']);

        // Store employment_detail data for afterSave
        $this->employmentDetailData = $employmentDetailData;

        return $data;
    }

    protected function afterSave(): void
    {
        // Update or create the employmentDetail record
        if (!empty($this->employmentDetailData)) {
            $this->record->employmentDetail()->updateOrCreate(
                ['employee_id' => $this->record->id],
                $this->employmentDetailData
            );
        }

        // Send success notification
        Notification::make()
            ->success()
            ->title('Employee updated')
            ->body('The employee has been updated successfully.')
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
            Actions\CreateAction::make(),
        ];
    }
}
