<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Models\LeadCustomField;
use Filament\Resources\Pages\Page;
use Filament\Forms;
use App\Filament\Resources\LeadResource;
use Filament\Notifications\Notification;

class CustomFields extends Page
{
    protected static string $resource = LeadResource::class;
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static string $view = 'filament.resources.lead-resource.pages.custom-fields';

    public ?array $customFields = [];

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Repeater::make('custom_fields')
                ->statePath('customFields') // Ensure data binds properly
                ->schema([
                    Forms\Components\TextInput::make('label')->required(),
                    Forms\Components\Select::make('type')
                        ->options([
                            'text' => 'Text',
                            'number' => 'Number',
                            'date' => 'Date',
                            'email' => 'Email',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('name')->required(),
                ])
                ->addActionLabel('Add New Field')
                ->columns(2)

        ];
    }

    public function submit()
    {
        foreach ($this->customFields as $field) {
            LeadCustomField::create([
                'label' => $field['label'],
                'type' => $field['type'],
                'name' => $field['name'],
            ]);
        }

        Notification::make()
            ->title('Custom fields added successfully!')
            ->success()
            ->send();

        $this->redirect('/admin/leads');
    }
}
