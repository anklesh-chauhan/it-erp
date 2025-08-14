<?php

namespace App\Traits;

use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use App\Models\ContactDetail;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use App\Models\Company;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Tables;
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
            Grid::make(2)
                    ->schema([
                        Select::make('company_id')
                            ->relationship('company', 'name', function ($query, callable $get) {
                                if ($contactId = $get('contact_detail_id')) {
                                    $contact = ContactDetail::with('company')->find($contactId);
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
                                    Placeholder::make('info')
                                        ->label('Info')
                                        ->content('The selected contact already belongs to a company. Creating a new company is not allowed.')
                                    ]
                                : [
                                Grid::make(2)
                                ->schema([
                                    TextInput::make('name')
                                        ->required()
                                        ->label('Company Name'),

                                    TextInput::make('email')
                                        ->email()
                                        ->nullable()
                                        ->label('Company Email'),

                                    TextInput::make('website')
                                        ->url()
                                        ->nullable()
                                        ->label('Website'),

                                    Select::make('industry_type_id')
                                        ->relationship('industryType', 'name')
                                        ->searchable()
                                        ->nullable()
                                        ->label('Industry Type')
                                        ->preload(),

                                    TextInput::make('no_of_employees')
                                        ->maxLength(255)->nullable(),

                                    Textarea::make('description')
                                        ->nullable()
                                        ->label('Company Description'),
                                ])
                            ])
                            ->createOptionUsing(function (array $data, callable $set, callable $get) {
                                $company = Company::create($data);

                                if ($contactId = $get('contact_id')) {
                                    ContactDetail::where('id', $contactId)
                                        ->update(['company_id' => $company->id]);
                                }

                                $set('company_id', $company->id);
                                return $company->id;
                            })
                            ->createOptionAction(fn (Action $action) =>
                                    $action->hidden(fn (callable $get) => $get('company_id') !== null) // ✅ Hide "Create" button when a contact is selected
                                )
                            ->suffixAction(
                                Action::make('editCompany')
                                    ->icon('heroicon-o-pencil')
                                    ->modalHeading('Edit Company')
                                    ->modalSubmitActionLabel('Update Company')
                                    ->schema(fn (callable $get) => [
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('name')
                                                    ->default(Company::find($get('company_id'))?->name)
                                                    ->required()
                                                    ->label('Company Name'),

                                                TextInput::make('email')
                                                    ->email()
                                                    ->default(Company::find($get('company_id'))?->email)
                                                    ->nullable()
                                                    ->label('Company Email'),

                                                TextInput::make('website')
                                                    ->url()
                                                    ->default(Company::find($get('company_id'))?->website)
                                                    ->nullable()
                                                    ->label('Website'),

                                                Select::make('industry_type_id')
                                                    ->relationship('industryType', 'name')
                                                    ->searchable()
                                                    ->preload()
                                                    ->default(fn () => Company::find($get('company_id'))?->industry_type_id),

                                                TextInput::make('no_of_employees')
                                                    ->default(Company::find($get('company_id'))?->no_of_employees)
                                                    ->maxLength(255)
                                                    ->label('Number of Employees'),

                                                Textarea::make('description')
                                                    ->default(Company::find($get('company_id'))?->description)
                                                    ->nullable()
                                                    ->label('Company Description'),
                                            ]),
                                    ])
                                    ->action(function (array $data, callable $get) {
                                        $company = Company::find($get('company_id'));

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
                                    $contact = ContactDetail::where('company_id', $state)->first();

                                    // Update the state with the related contact
                                    $set('contact_detail_id', $contact?->id);
                                    $set('show_company_info', $state);
                                }
                            })
                            ->afterStateHydrated(function (callable $set, $state) {
                                if ($state) {
                                    // Fetch the related contact for the selected company
                                    $contact = ContactDetail::where('company_id', $state)->first();

                                    // Update the state with the related contact
                                    $set('contact_detail_id', $contact?->id);
                                    $set('show_company_info', $state);
                                }
                            })
                            ->getOptionLabelUsing(fn ($value) =>
                                    Company::find($value)?->name ?? 'Unknown Company'
                                ),

                        Placeholder::make('Company Details')
                            ->hidden(fn (callable $get) => !$get('company_id'))
                            ->content(function (callable $get) {
                                $contact = ContactDetail::find($get('contact_detail_id'));
                                $company = $contact?->company ?? Company::find($get('company_id'));

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
