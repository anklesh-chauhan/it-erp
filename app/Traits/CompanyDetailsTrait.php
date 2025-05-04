<?php

namespace App\Traits;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;

trait CompanyDetailsTrait
{
    /**
     * Get common form fields for SalesDocument.
     *
     * @return array
     */
    public static function getCompanyDetailsTraitField(): array
    {
        return [
            Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\Select::make('company_id')
                            ->relationship('company', 'name', function ($query, callable $get) {
                                if ($contactId = $get('contact_detail_id')) {
                                    $contact = \App\Models\ContactDetail::with('company')->find($contactId);
                                    return $query->where('id', $contact?->company_id);
                                }
                                return $query;
                            })
                            ->searchable()
                            ->nullable()
                            ->preload()
                            ->live()
                            ->extraAttributes(fn (callable $get) => $get('company_id') ? ['class' => 'hide-create-button'] : [])
                            ->createOptionForm(fn (callable $get) => $get('company_id')
                                ? [
                                    Forms\Components\Placeholder::make('info')
                                        ->label('Info')
                                        ->content('The selected contact already belongs to a company. Creating a new company is not allowed.')
                                    ]
                                : [
                                Forms\Components\Grid::make(2)
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->required()
                                        ->label('Company Name'),

                                    Forms\Components\TextInput::make('email')
                                        ->email()
                                        ->nullable()
                                        ->label('Company Email'),

                                    Forms\Components\TextInput::make('website')
                                        ->url()
                                        ->nullable()
                                        ->label('Website'),

                                    Forms\Components\Select::make('industry_type_id')
                                        ->relationship('industryType', 'name')
                                        ->searchable()
                                        ->nullable()
                                        ->label('Industry Type')
                                        ->preload(),

                                    Forms\Components\TextInput::make('no_of_employees')
                                        ->maxLength(255)->nullable(),

                                    Forms\Components\Textarea::make('description')
                                        ->nullable()
                                        ->label('Company Description'),
                                ])
                            ])
                            ->createOptionUsing(function (array $data, callable $set, callable $get) {
                                $company = \App\Models\Company::create($data);

                                if ($contactId = $get('contact_id')) {
                                    \App\Models\ContactDetail::where('id', $contactId)
                                        ->update(['company_id' => $company->id]);
                                }

                                $set('company_id', $company->id);
                                return $company->id;
                            })
                            ->createOptionAction(fn (Forms\Components\Actions\Action $action) =>
                                    $action->hidden(fn (callable $get) => $get('company_id') !== null) // ✅ Hide "Create" button when a contact is selected
                                )
                            ->suffixAction(
                                Action::make('editCompany')
                                    ->icon('heroicon-o-pencil')
                                    ->modalHeading('Edit Company')
                                    ->modalSubmitActionLabel('Update Company')
                                    ->form(fn (callable $get) => [
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->default(\App\Models\Company::find($get('company_id'))?->name)
                                                    ->required()
                                                    ->label('Company Name'),

                                                Forms\Components\TextInput::make('email')
                                                    ->email()
                                                    ->default(\App\Models\Company::find($get('company_id'))?->email)
                                                    ->nullable()
                                                    ->label('Company Email'),

                                                Forms\Components\TextInput::make('website')
                                                    ->url()
                                                    ->default(\App\Models\Company::find($get('company_id'))?->website)
                                                    ->nullable()
                                                    ->label('Website'),

                                                Forms\Components\Select::make('industry_type_id')
                                                    ->relationship('industryType', 'name')
                                                    ->searchable()
                                                    ->preload()
                                                    ->default(fn () => \App\Models\Company::find($get('company_id'))?->industry_type_id),

                                                Forms\Components\TextInput::make('no_of_employees')
                                                    ->default(\App\Models\Company::find($get('company_id'))?->no_of_employees)
                                                    ->maxLength(255)
                                                    ->label('Number of Employees'),

                                                Forms\Components\Textarea::make('description')
                                                    ->default(\App\Models\Company::find($get('company_id'))?->description)
                                                    ->nullable()
                                                    ->label('Company Description'),
                                            ]),
                                    ])
                                    ->action(function (array $data, callable $get) {
                                        $company = \App\Models\Company::find($get('company_id'));

                                        if ($company) {
                                            $company->update([
                                                'name' => $data['name'] ?? $company->name,
                                                'email' => $data['email'] ?? $company->email,
                                                'website' => $data['website'] ?? $company->website,
                                                'industry_type_id' => $data['industry_type_id'] ?? $company->industry_type_id,
                                                'no_of_employees' => $data['no_of_employees'] ?? $company->no_of_employees,
                                                'description' => $data['description'] ?? $company->description,
                                            ]);

                                            Notification::make()
                                                ->title('Company Updated')
                                                ->success()
                                                ->send();
                                        }
                                    })
                                    ->requiresConfirmation()
                                    ->visible(fn (callable $get) => $get('company_id'))
                            )
                            ->afterStateUpdated(function (callable $set, $state) {
                                if ($state) {
                                    // Fetch the related contact for the selected company
                                    $contact = \App\Models\ContactDetail::where('company_id', $state)->first();

                                    // Update the state with the related contact
                                    $set('contact_detail_id', $contact?->id);
                                    $set('show_company_info', $state);
                                }
                            })
                            ->afterStateHydrated(function (callable $set, $state) {
                                if ($state) {
                                    // Fetch the related contact for the selected company
                                    $contact = \App\Models\ContactDetail::where('company_id', $state)->first();

                                    // Update the state with the related contact
                                    $set('contact_detail_id', $contact?->id);
                                    $set('show_company_info', $state);
                                }
                            })
                            ->getOptionLabelUsing(fn ($value) =>
                                    \App\Models\Company::find($value)?->name ?? 'Unknown Company'
                                ),

                        Forms\Components\Placeholder::make('Company Details')
                            ->hidden(fn (callable $get) => !$get('company_id'))
                            ->content(function (callable $get) {
                                $contact = \App\Models\ContactDetail::find($get('contact_detail_id'));
                                $company = $contact?->company ?? \App\Models\Company::find($get('company_id'));

                                $companyDetails = $company
                                    ? "🏢 {$company->name}
                                    📧 {$company->email}
                                    🌐 {$company->website}"
                                    : 'No company details available.';

                                return "{$companyDetails}";
                            }),
                    ]),

        ];
    }
}
