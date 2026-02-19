<?php

namespace App\Filament\Resources\Visits\Schemas;

use App\Models\VisitPreference;
use App\Services\ImageWatermarkService;
use Filament\Actions\Action;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Illuminate\Support\Facades\Auth;

class VisitForm
{

    public static function configure(Schema $schema): Schema
    {
        $prefs = VisitPreference::current();

        return $schema
            ->components([
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
                                                    ->disabled(fn ($record) =>! VisitPreference::current()->allow_manual_time_edit || $record->isCompleted()),

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
                                    ->afterStateUpdated(fn (callable $set, $state) => !$state ? $set('jointUsers', []) : null),
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

                        // --- TAB 4: ATTACHMENTS ---
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

                                // Forms\Components\Hidden::make('image_latitude'),
                                // Forms\Components\Hidden::make('image_longitude'),

                                // Forms\Components\FileUpload::make('attachments')
                                    // ->label('Visit Photos')
                                    // ->image()
                                    // ->multiple()
                                    // ->directory('visits/photos')
                                    // ->disk('public')
                                    // ->reorderable()
                                    // ->appendFiles()
                                    // ->maxSize(2048)
                                    // ->openable()
                                    // ->downloadable()
                                    // ->imageResizeTargetWidth(800)
                                    // ->imageResizeTargetHeight(800)

                                    // // 🔹 1. Capture GPS on Upload
                                    // ->afterStateUpdated(function ($state, Component $component, callable $get) {
                                    //     if (blank($state)) return;

                                    //     // Dispatch to JS for browser GPS
                                    //     $component->getLivewire()->dispatch('capture-image-location');

                                    //     // 🔹 2. Process Watermark for the most recent file
                                    //     // We wrap this in a tiny delay or check to ensure file exists
                                    //     $files = (array) $state;
                                    //     $lastFile = end($files);

                                    //     if ($lastFile) {
                                    //         ImageWatermarkService::apply(
                                    //             storage_path('app/public/' . $lastFile),
                                    //             [
                                    //                 'latitude'  => $get('image_latitude'),
                                    //                 'longitude' => $get('image_longitude'),
                                    //                 'timestamp' => now()->format('d M Y H:i'),
                                    //             ]
                                    //         );
                                    //     }
                                    // })
                                    // ->live(), // Ensures coordinates are synced back to the server
                            ]),
                    ]),
            ]);
    }
}
