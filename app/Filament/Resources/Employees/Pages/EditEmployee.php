<?php

namespace App\Filament\Resources\Employees\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\CreateAction;
use App\Filament\Resources\Employees\EmployeeResource;
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

    public function getHeading(): string
    {
        /** @var Model $record */
        $record = $this->getRecord();

        // Assuming your Employee model has 'name' and 'employee_id' columns
        $first_name = $record->first_name;
        $last_name = $record->last_name;
        $employeeId = $record->employee_id;

        // Return your custom heading
        return "{$first_name} {$last_name} (ID: {$employeeId})";
    }

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
            ViewAction::make(),
            RestoreAction::make(),
            CreateAction::make(),
        ];
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

    public function getContentTabLabel(): ?string
    {
        /** @var Model $record */
        $record = $this->getRecord();

        // Assuming your Employee model has a column named 'employee_id'
        $employeeId = $record->employee_id;

        // Construct the label
        return "Employee Details";
    }
}
