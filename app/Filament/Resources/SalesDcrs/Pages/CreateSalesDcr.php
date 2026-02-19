<?php

namespace App\Filament\Resources\SalesDcrs\Pages;

use App\Filament\Resources\SalesDcrs\SalesDcrResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSalesDcr extends CreateRecord
{
    protected static string $resource = SalesDcrResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // We don't need to store this in a property anymore,
        // we can just handle everything in afterCreate
        return $data;
    }


    protected function afterCreate(): void
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
            $visit->feedbacks()->create([
                'visit_feedback_question_id' => $questionId,
                'answer' => $values['answer'] ?? null,
                'remarks' => $values['remarks'] ?? null,
            ]);
        }
    }
}


}
