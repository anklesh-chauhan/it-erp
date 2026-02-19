<?php

namespace App\Filament\Resources\SalesDcrs\Pages;

use App\Filament\Resources\SalesDcrs\SalesDcrResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSalesDcr extends EditRecord
{
    protected static string $resource = SalesDcrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function afterSave(): void
{
    $feedbackPayload = request()->input('feedback', []);
    if (empty($feedbackPayload)) {
        return;
    }

    foreach ($this->record->visits as $visit) {
        $uuid = $visit->form_uuid;

        if (! isset($feedbackPayload[$uuid])) {
            continue;
        }

        foreach ($feedbackPayload[$uuid] as $questionId => $values) {
            $visit->feedbacks()->updateOrCreate(
                ['visit_feedback_question_id' => $questionId],
                [
                    'answer' => $values['answer'] ?? null,
                    'remarks' => $values['remarks'] ?? null,
                ]
            );
        }
    }
}


}
