<?php

namespace App\Filament\Resources\Leads\Pages;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Models\LeadCustomField;
use Filament\Resources\Pages\Page;
use Filament\Forms;
use App\Filament\Resources\Leads\LeadResource;
use Filament\Notifications\Notification;

class CustomFields extends Page
{
    protected static string $resource = LeadResource::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-collection';
    protected string $view = 'filament.resources.lead-resource.pages.custom-fields';

    public ?array $customFields = [];

    protected function getFormSchema(): array
    {
        return [
            Repeater::make('custom_fields')
                ->statePath('customFields') // Ensure data binds properly
                ->schema([
                    TextInput::make('label')->required(),
                    Select::make('type')
                        ->options([
                            'text' => 'Text',
                            'number' => 'Number',
                            'date' => 'Date',
                            'email' => 'Email',
                        ])
                        ->required(),
                    TextInput::make('name')->required(),
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
