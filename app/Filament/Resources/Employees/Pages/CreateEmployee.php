<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    /**
     * Temporarily holds employment detail data before record creation.
     *
     * @var array
     */
    protected array $employmentDetailData = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Extract employment_detail data and remove it from the main data array
        $employmentDetailData = $data['employment_detail'] ?? [];
        unset($data['employment_detail']);

        // Store employment_detail data in a temporary property for afterCreate
        $this->employmentDetailData = $employmentDetailData;

        return $data;
    }

    protected function afterCreate(): void
    {
        // Create the employmentDetail record if data exists
        if (!empty($this->employmentDetailData)) {
            $this->record->employmentDetail()->create($this->employmentDetailData);
        }

        // Send success notification
        Notification::make()
            ->success()
            ->title('Employee created')
            ->body('The employee has been created successfully.')
            ->send();
    }
}
