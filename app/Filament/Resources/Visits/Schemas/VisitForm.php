<?php

namespace App\Filament\Resources\Visits\Schemas;

use App\Helpers\SalesDocumentQuickCreate;
use App\Models\Quote;
use App\Models\SalesOrder;
use App\Models\Visit;
use App\Models\VisitPreference;
use App\Services\Visit\DcrService;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class VisitForm
{
    public static function configure(Schema $schema): Schema
    {
        $prefs = VisitPreference::current();

        return $schema
            ->components([
                Hidden::make('sales_dcr_id')
                    ->default(function (?Visit $record): int {
                        $date = $record?->visit_date?->format('Y-m-d') ?? now()->toDateString();

                        return (new DcrService)->getOrCreateForDate($date)->id;
                    }),
                Tabs::make('VisitTabs')
                    ->tabs([
                        // --- TAB 1: VISIT DETAILS ---
                        Tabs\Tab::make('Visit')
                            ->schema([
                                Group::make()
                                    ->schema([
                                        Group::make()
                                            ->schema([
                                                Forms\Components\TimePicker::make('start_time')
                                                    ->label('Check-in')
                                                    ->seconds(false)
                                                    ->visible(fn () => VisitPreference::current()->allow_manual_time_edit)
                                                    ->disabled(fn ($record) => ! VisitPreference::current()->allow_manual_time_edit || $record->isCompleted()),

                                                Forms\Components\TimePicker::make('end_time')
                                                    ->label('Check-out')
                                                    ->seconds(false)
                                                    ->visible(fn () => VisitPreference::current()->allow_manual_time_edit)
                                                    ->disabled(fn ($record) => ! VisitPreference::current()->allow_manual_time_edit || $record->isCompleted()),
                                            ])->columns(2),

                                        Forms\Components\Select::make('visit_outcome_id')
                                            ->label('Outcome')
                                            ->options(
                                                \App\Models\VisitOutcome::query()
                                                    ->orderBy('label')
                                                    ->pluck('label', 'id')
                                            )
                                            ->live(debounce: 5000)
                                            ->searchable()
                                            ->visible(fn () => $prefs->isFieldVisible('outcome'))
                                            ->required(fn () => $prefs->isFieldRequired('outcome'))
                                            ->preload(),

                                        Forms\Components\Textarea::make('remarks')
                                            ->live(debounce: 5000)
                                            ->visible(fn () => $prefs->isFieldVisible('remarks'))
                                            ->required(fn () => $prefs->isFieldRequired('remarks'))
                                            ->columnSpanFull(),
                                    ]),

                                Forms\Components\Select::make('visitPurposes')
                                    ->label('Purposes')
                                    ->relationship('visitPurposes', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->searchable()
                                    ->live(debounce: 5000)
                                    ->visible(fn () => $prefs->isFieldVisible('purpose'))
                                    ->required(fn () => $prefs->isFieldRequired('purpose'))
                                    ->native(false),

                                DatePicker::make('next_follow_up_date')
                                    ->label('Next Follow-Up Date')
                                    ->visible(fn () => $prefs->isFieldVisible('next_follow_up_date'))
                                    ->required(fn () => $prefs->isFieldRequired('next_follow_up_date')),

                                Toggle::make('is_joint_work')
                                    ->label('Is Joint Work?')
                                    ->reactive()
                                    ->afterStateUpdated(fn (callable $set, $state) => ! $state ? $set('jointUsers', []) : null),
                                Forms\Components\Select::make('jointUsers')
                                    ->hiddenLabel()
                                    ->relationship('jointUsers', 'name')
                                    ->multiple()
                                    ->live(debounce: 5000)
                                    ->searchable()
                                    ->preload(),

                                Group::make()
                                    ->schema([
                                        TextEntry::make('visit_type')
                                            ->getStateUsing(fn ($record) => $record->visit_type
                                                ? Str::of(class_basename($record->visit_type))->snake()->replace('_', ' ')->title()
                                                : '—'
                                            )
                                            ->label('Type')
                                            ->inlineLabel(fn () => ! request()->header('User-Agent') || ! str_contains(request()->header('User-Agent'), 'Mobile')),

                                        TextEntry::make('patch_id')
                                            ->label('Patch')
                                            ->getStateUsing(fn ($record) => $record->patch?->name ?? '—')
                                            ->inlineLabel(fn () => ! request()->header('User-Agent') || ! str_contains(request()->header('User-Agent'), 'Mobile')),
                                        TextEntry::make('visit_date')
                                            ->label('Visit Date')
                                            ->getStateUsing(fn ($record) => $record->visit_date?->format('d M Y') ?? '—')
                                            ->inlineLabel(fn () => ! request()->header('User-Agent') || ! str_contains(request()->header('User-Agent'), 'Mobile')),
                                    ])->columns([
                                        'default' => 3, // 📱 This forces 2 columns on mobile/extra small screens
                                        'sm' => 3,
                                        'lg' => 3,
                                    ]),

                            ]),

                        // --- TAB 2: FEEDBACK ---
                        Tabs\Tab::make('Feedback')
                            ->schema([
                                Repeater::make('feedbacks')
                                    ->hiddenLabel()
                                    ->relationship()
                                    ->schema([
                                        // Using a ViewField or a simple Text component for the question
                                        TextEntry::make('question_text')
                                            ->hiddenLabel()
                                            ->state(fn ($record) => $record?->question?->question),

                                        Forms\Components\Radio::make('answer')
                                            ->options([
                                                1 => '1',
                                                2 => '2',
                                                3 => '3',
                                                4 => '4',
                                                5 => '5',
                                            ])
                                            ->inline()
                                            ->columns(5)
                                            ->live(debounce: 5000)
                                            ->hiddenLabel()
                                            ->extraAttributes(['class' => 'text-xs']),
                                    ])
                                    ->addable(false)
                                    ->deletable(false),
                            ]),

                        // --- TAB 3: CUSTOMER ---
                        Tabs\Tab::make('Customer')
                            ->schema([
                                Group::make()
                                    ->schema([
                                        ViewField::make('account_summary')
                                            ->view('filament.visits.account-summary'),
                                    ])
                                    ->visible(fn ($record) => filled($record?->primaryCompany())),
                            ]),

                        // --- TAB 4: EXPENSES ---
                        Tabs\Tab::make('Expenses')
                            ->schema([
                                ViewField::make('expenses_summary')
                                    ->view('filament.visits.expenses-summary'),
                            ]),

                        // --- TAB 5: ATTACHMENTS ---
                        Tabs\Tab::make('Attachments')
                            ->schema([

                                Repeater::make('media')
                                    ->hiddenLabel()
                                    ->relationship()
                                    ->schema([
                                        FileUpload::make('path')
                                            ->label('Visit Photo')
                                            ->image()
                                            ->directory('visits/photos')
                                            ->disk('public')
                                            ->maxSize(2048)
                                            ->imageResizeTargetWidth(800)
                                            ->imageResizeTargetHeight(800)
                                            ->openable()
                                            ->downloadable()
                                            ->required()
                                            ->live(), // only for UI refresh

                                        Select::make('tags')
                                            ->relationship('tags', 'name')
                                            ->multiple()
                                            ->searchable()
                                            ->preload(),

                                        TextEntry::make('processing_status')
                                            ->state(fn ($record) => $record?->processing_status ?? 'Pending')
                                            ->visible(fn ($record) => filled($record)),

                                        Hidden::make('original_name'),
                                        Hidden::make('mime_type'),
                                        Hidden::make('size'),
                                        Hidden::make('latitude'),
                                        Hidden::make('longitude'),
                                    ])
                                    ->columns(2),

                            ]),

                            Tabs\Tab::make('Sales Documents')
                            ->schema([

                                Actions::make([

                                    Action::make('create_quote')
                                        ->label('Create Quote')
                                        ->icon('heroicon-o-document-text')
                                        ->color('success')
                                        ->visible(fn (?Visit $record) => filled($record))
                                        ->action(function (Visit $record) {

                                            $quote = SalesDocumentQuickCreate::createFromVisit(
                                                $record,
                                                Quote::class,
                                            );

                                            redirect(
                                                \App\Filament\Resources\Quotes\QuoteResource::getUrl(
                                                    'edit',
                                                    ['record' => $quote]
                                                )
                                            );
                                        }),

                                    Action::make('create_sales_order')
                                        ->label('Create Sales Order')
                                        ->icon('heroicon-o-shopping-cart')
                                        ->color('warning')
                                        ->action(function (Visit $record) {

                                            $salesOrder = SalesDocumentQuickCreate::createFromVisit(
                                                $record,
                                                SalesOrder::class,
                                            );

                                            redirect(
                                                \App\Filament\Resources\SalesOrders\SalesOrderResource::getUrl(
                                                    'edit',
                                                    ['record' => $salesOrder]
                                                )
                                            );
                                        }),

                                ])->columnSpanFull(),

                                Grid::make(2)
                                    ->schema([

                                        Section::make('Quotes')
                                            ->schema([
                                                TextEntry::make('quote_count')
                                                    ->label('Total Quotes')
                                                    ->state(fn ($record) =>
                                                        $record?->quoteSummary()['count'] ?? 0
                                                    ),

                                                TextEntry::make('quote_amount')
                                                    ->label('Quote Amount')
                                                    ->state(fn ($record) =>
                                                        $record?->quoteSummary()['amount'] ?? 0
                                                    )
                                                    ->money('INR'),

                                                TextEntry::make('quote_items')
                                                    ->label('Items')
                                                    ->state(fn ($record) =>
                                                        $record?->quoteSummary()['items'] ?? 0
                                                    ),
                                            ]),

                                        Section::make('Sales Orders')
                                            ->schema([
                                                TextEntry::make('so_count')
                                                    ->label('Total Orders')
                                                    ->state(fn ($record) =>
                                                        $record?->salesOrderSummary()['count'] ?? 0
                                                    ),

                                                TextEntry::make('so_amount')
                                                    ->label('Order Amount')
                                                    ->state(fn ($record) =>
                                                        $record?->salesOrderSummary()['amount'] ?? 0
                                                    )
                                                    ->money('INR'),

                                                TextEntry::make('so_items')
                                                    ->label('Items')
                                                    ->state(fn ($record) =>
                                                        $record?->salesOrderSummary()['items'] ?? 0
                                                    ),
                                            ]),
                                    ]),
                                ]),
                            ])
                    ->columnSpanFull(),
            ]);
    }
}
