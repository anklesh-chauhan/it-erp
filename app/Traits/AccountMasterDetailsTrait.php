<?php

namespace App\Traits;

use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;

trait AccountMasterDetailsTrait
{
    /**
     * Get common form fields for AccountMaster.
     *
     * @return array
     */
    public static function getAccountMasterDetailsTraitField(): array
    {
        return [
            Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\Select::make('account_master_id')
                            ->relationship('accountMaster', 'name', function ($query, callable $get) {
                                if ($contactId = $get('contact_detail_id')) {
                                    $contact = \App\Models\ContactDetail::with('accountMaster')->find($contactId);
                                    return $query->where('id', $contact?->account_master_id);
                                }
                                return $query;
                            })
                            ->searchable()
                            ->nullable()
                            ->preload()
                            ->live()
                            ->extraAttributes(fn (callable $get) => $get('account_master_id') ? ['class' => 'hide-create-button'] : [])
                            ->createOptionForm(fn (callable $get) => $get('account_master_id')
                                ? [
                                    Forms\Components\Placeholder::make('info')
                                        ->label('Info')
                                        ->content('The selected contact already belongs to an account master. Creating a new account master is not allowed.')
                                    ]
                                : [
                                    Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->label('Account Master Name'),

                                        Forms\Components\TextInput::make('email')
                                            ->email()
                                            ->nullable()
                                            ->label('Account Master Email'),

                                        Forms\Components\TextInput::make('website')
                                            ->url()
                                            ->nullable()
                                            ->label('Website'),

                                        Forms\Components\TextInput::make('phone_number')
                                            ->label('Phone Number')
                                            ->nullable(),

                                        Forms\Components\Select::make('industry_type_id')
                                            ->relationship('industryType', 'name')
                                            ->searchable()
                                            ->nullable()
                                            ->label('Industry Type')
                                            ->preload(),

                                        Forms\Components\TextInput::make('no_of_employees')
                                            ->maxLength(255)
                                            ->nullable(),
                                    ])
                                ])
                            ->createOptionUsing(function (array $data, callable $set, callable $get) {
                                $accountMaster = \App\Models\AccountMaster::create($data);

                                if ($contactId = $get('contact_id')) {
                                    \App\Models\ContactDetail::where('id', $contactId)
                                        ->update(['account_master_id' => $accountMaster->id]);
                                }

                                $set('account_master_id', $accountMaster->id);
                                return $accountMaster->id;
                            })
                            ->createOptionAction(fn (Forms\Components\Actions\Action $action) =>
                                $action->hidden(fn (callable $get) => $get('account_master_id') !== null) // Hide "Create" button when a contact is selected
                            )
                            ->suffixAction(
                                Action::make('editAccountMaster')
                                    ->icon('heroicon-o-pencil')
                                    ->modalHeading('Edit Account Master')
                                    ->modalSubmitActionLabel('Update Account Master')
                                    ->form(fn (callable $get) => [
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->default(\App\Models\AccountMaster::find($get('account_master_id'))?->name)
                                                    ->required()
                                                    ->label('Account Master Name'),

                                                Forms\Components\TextInput::make('email')
                                                    ->email()
                                                    ->default(\App\Models\AccountMaster::find($get('account_master_id'))?->email)
                                                    ->nullable()
                                                    ->label('Account Master Email'),

                                                Forms\Components\TextInput::make('website')
                                                    ->url()
                                                    ->default(\App\Models\AccountMaster::find($get('account_master_id'))?->website)
                                                    ->nullable()
                                                    ->label('Website'),

                                                Forms\Components\TextInput::make('phone_number')
                                                    ->default(\App\Models\AccountMaster::find($get('account_master_id'))?->phone_number)
                                                    ->nullable()
                                                    ->label('Phone Number'),

                                                Forms\Components\Select::make('industry_type_id')
                                                    ->relationship('industryType', 'name')
                                                    ->searchable()
                                                    ->preload()
                                                    ->default(fn () => \App\Models\AccountMaster::find($get('account_master_id'))?->industry_type_id),

                                                Forms\Components\TextInput::make('no_of_employees')
                                                    ->default(\App\Models\AccountMaster::find($get('account_master_id'))?->no_of_employees)
                                                    ->maxLength(255)
                                                    ->label('Number of Employees'),
                                            ]),
                                    ])
                                    ->action(function (array $data, callable $get) {
                                        $accountMaster = \App\Models\AccountMaster::find($get('account_master_id'));

                                        if ($accountMaster) {
                                            $accountMaster->update([
                                                'name' => $data['name'] ?? $accountMaster->name,
                                                'email' => $data['email'] ?? $accountMaster->email,
                                                'website' => $data['website'] ?? $accountMaster->website,
                                                'phone_number' => $data['phone_number'] ?? $accountMaster->phone_number,
                                                'industry_type_id' => $data['industry_type_id'] ?? $accountMaster->industry_type_id,
                                                'no_of_employees' => $data['no_of_employees'] ?? $accountMaster->no_of_employees,
                                            ]);

                                            Notification::make()
                                                ->title('Account Master Updated')
                                                ->success()
                                                ->send();
                                        }
                                    })
                                    ->requiresConfirmation()
                                    ->visible(fn (callable $get) => $get('account_master_id'))
                            )
                            // After state update for account_master_id
                            ->afterStateUpdated(function (callable $set, $state) {
                                if ($state) {
                                    $contact = \App\Models\ContactDetail::where('account_master_id', $state)->first();
                                    $set('contact_detail_id', $contact?->id);
                                    $set('show_account_master_info', $state);
                                }
                            })
                            ->afterStateHydrated(function (callable $set, $state) {
                                if ($state) {
                                    $contact = \App\Models\ContactDetail::where('account_master_id', $state)->first();
                                    $set('contact_detail_id', $contact?->id);
                                    $set('show_account_master_info', $state);
                                }
                            })
                            ->getOptionLabelUsing(fn ($value) =>
                                \App\Models\AccountMaster::find($value)?->name ?? 'Unknown Account Master'
                            ),

                        Forms\Components\Placeholder::make('Account Details')
                            ->hidden(fn (callable $get) => !$get('account_master_id'))
                            ->label('Account Details')
                            ->content(function (callable $get) {
                                $accountMaster = \App\Models\AccountMaster::find($get('account_master_id'));

                                $accountDetails = $accountMaster
                                    ? "🏢 {$accountMaster->name}
                                    📧 {$accountMaster->email}
                                    🌐 {$accountMaster->website}"
                                    : 'No account details available.';

                                return "{$accountDetails}";
                            }),

                    ]),
        ];
    }
}
