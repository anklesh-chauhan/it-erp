<?php

namespace App\Filament\Resources\SalesTourPlans\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use App\Models\Patch;
use App\Models\User;
use App\Models\SalesTourPlan;
use Dompdf\FrameDecorator\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class SalesTourPlanForm
{

    private static function resolveNextDate(Get $get): string
    {
        // Get all current repeater items
        $items = $get('../../details') ?? [];

        $validDates = collect($items)
            ->pluck('date')
            ->filter()
            ->map(fn($d) => @Carbon::parse($d))
            ->filter()
            ->sort()
            ->values();

        // If there are valid existing dates, add +1 day from the last one
        if ($validDates->isNotEmpty()) {
            return $validDates->last()->addDay()->format('Y-m-d');
        }

        // Otherwise, use the selected month (from parent select)
        $month = $get('../../month');

        // 👇 Force start from the FIRST day of the selected month, not "now"
        if ($month && preg_match('/^\d{4}-\d{2}$/', $month)) {
            return Carbon::createFromFormat('Y-m', $month)->startOfMonth()->format('Y-m-d');
        }

        // Fallback (no month selected yet)
        return now()->startOfMonth()->format('Y-m-d');
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Select::make('user_id')
                        ->label('Sales Employee')
                        ->relationship('user', 'name')
                        ->default(fn() => Auth::id())
                        ->required(),

                    Select::make('month')
                        ->label('Month')
                        ->options(function (callable $get) {
                            $userId = $get('user_id') ?? Auth::id();

                            // Get months already created by this user
                            $existingMonths = SalesTourPlan::where('user_id', $userId)
                                ->pluck('month')
                                ->toArray();

                            // Build available months
                            $months = [];
                            $start = now()->startOfMonth();

                            for ($i = 0; $i < 12; $i++) {
                                $monthKey = $start->copy()->addMonths($i)->format('Y-m');

                                // Skip if already created
                                if (in_array($monthKey, $existingMonths)) {
                                    continue;
                                }

                                $monthLabel = $start->copy()->addMonths($i)->format('F Y');
                                $months[$monthKey] = $monthLabel;
                            }

                            return $months;
                        })
                        ->default(function (callable $get) {
                            $userId = $get('user_id') ?? Auth::id();

                            // Get months already created
                            $existingMonths = SalesTourPlan::where('user_id', $userId)
                                ->pluck('month')
                                ->toArray();

                            // Identify the first free month starting from NOW
                            $start = now()->startOfMonth();

                            for ($i = 0; $i < 12; $i++) {
                                $monthKey = $start->copy()->addMonths($i)->format('Y-m');

                                if (!in_array($monthKey, $existingMonths)) {
                                    return $monthKey;  // <-- FIRST AVAILABLE MONTH
                                }
                            }

                            return now()->format('Y-m'); // fallback
                        })
                        ->required()
                        ->reactive() // 👈 this makes downstream components re-evaluate
                        ->afterStateUpdated(function ($state, callable $set) {
                            // Reset repeater when month changes (optional but clean)
                            $set('details', []);
                        })
                        ->searchable(),


                    Select::make('status')
                        ->options([
                            'draft' => 'Draft',
                            'submitted' => 'Submitted',
                            'approved' => 'Approved',
                            'rejected' => 'Rejected',
                        ])
                        ->default('draft')
                        ->disabled(),
                ])->columns(3)->columnSpanFull(),

                Section::make('Tour Plan Details')
                    ->schema([
                    // Repeater for Sales Tour Plan Details
                    Repeater::make('details')
                        ->relationship('details')
                        ->label('Daily Plan')
                        ->minItems(1)
                        ->required()
                        ->compact()
                        ->table([
                            TableColumn::make('Date'),
                            TableColumn::make('Territory '),
                            TableColumn::make('Patches'),
                            TableColumn::make('Purpose'),
                            TableColumn::make('Joint With'),
                            TableColumn::make('Remarks'),
                        ])
                        ->schema([
                            Hidden::make('id'),

                            DatePicker::make('date')
                                ->label('Date')
                                ->required()
                                ->native(false)
                                ->displayFormat('j M, (D)')
                                ->default(fn(Get $get) => self::resolveNextDate($get))
                                ->dehydrateStateUsing(function ($state, Get $get) {
                                    // Normalize or rebuild the date before save
                                    try {
                                        if ($state && is_string($state)) {
                                            return Carbon::parse($state)->format('Y-m-d');
                                        }

                                        // If empty or invalid, derive it based on month
                                        $month = $get('../../month');
                                        if ($month && preg_match('/^\d{4}-\d{2}$/', $month)) {
                                            return Carbon::createFromFormat('Y-m', $month)->startOfMonth()->format('Y-m-d');
                                        }

                                        return now()->format('Y-m-d');
                                    } catch (\Throwable $e) {
                                        return now()->format('Y-m-d');
                                    }
                                })
                                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                    // Get the repeater data from the same level
                                    $rows = $get('../') ?? []; // ✅ Filament 4.1 way to access repeater parent

                                    // Normalize all dates to 'Y-m-d'
                                    $normalized = collect($rows)
                                        ->pluck('date')
                                        ->filter()
                                        ->map(fn($d) => \Illuminate\Support\Carbon::parse($d)->format('Y-m-d'))
                                        ->toArray();

                                    // Normalize current date
                                    $current = null;
                                    try {
                                        $current = \Illuminate\Support\Carbon::parse($state)->format('Y-m-d');
                                    } catch (\Throwable $e) {}

                                    // Check for duplicates
                                    if ($current && collect($normalized)->filter(fn($d) => $d === $current)->count() > 1) {
                                        $set('date', null);

                                        \Filament\Notifications\Notification::make()
                                            ->title('Duplicate date not allowed')
                                            ->body("The date {$current} is already used in another row.")
                                            ->danger()
                                            ->send();
                                    }
                                })
                                ->reactive(),

                            Select::make('territory_id')
                                ->label('Territory')
                                ->relationship('territory', 'name')
                                ->searchable()
                                ->preload()
                                ->reactive(),

                            Select::make('patch_ids')
                                ->label('Patches')
                                ->multiple()
                                ->options(fn(Get $get) =>
                                    Patch::query()
                                        ->when($get('territory_id'), fn($q) => $q->where('territory_id', $get('territory_id')))
                                        ->pluck('name', 'id')
                                )
                                ->searchable()
                                ->preload(),

                            TextInput::make('purpose')
                                ->label('Purpose of Visit'),

                            Select::make('joint_with')
                                ->label('Joint With')
                                ->multiple()
                                ->options(User::pluck('name', 'id')->toArray())
                                ->searchable()
                                ->preload(),

                            Textarea::make('remarks')
                                ->label('Remarks')
                                ->rows(2),
                        ])

                        ->afterStateHydrated(function (Repeater $component, $state, $record) {
                            if (!$record || $record->details->isEmpty()) {
                                return;
                            }

                            $items = $record->details()
                                ->orderBy('date')
                                ->get()
                                ->map(fn($d) => [
                                    'id' => $d->id,
                                    'date' => $d->date?->format('Y-m-d'),
                                    'territory_id' => $d->territory_id,
                                    'patch_ids' => $d->patch_ids,
                                    'purpose' => $d->purpose,
                                    'joint_with' => $d->joint_with,
                                    'remarks' => $d->remarks,
                                ])
                                ->toArray();

                            $component->state($items);
                        })

                        ->mutateRelationshipDataBeforeCreateUsing(function (array $data, callable $get): array {
                            $monthStr = $get('../../month');

                            // If the date is just a number (1, 2, 3...), build a full date from the month
                            if (is_numeric($data['date'])) {
                                try {
                                    if ($monthStr && \Carbon\Carbon::hasFormat($monthStr, 'Y-m')) {
                                        $data['date'] = \Carbon\Carbon::createFromFormat('Y-m-d', "{$monthStr}-" . str_pad($data['date'], 2, '0', STR_PAD_LEFT))
                                            ->format('Y-m-d');
                                    } else {
                                        $data['date'] = now()->format('Y-m-d');
                                    }
                                } catch (\Throwable $e) {
                                    $data['date'] = now()->format('Y-m-d');
                                }
                            } else {
                                // Otherwise, parse normally
                                try {
                                    $data['date'] = \Carbon\Carbon::parse($data['date'])->format('Y-m-d');
                                } catch (\Throwable $e) {
                                    $data['date'] = now()->format('Y-m-d');
                                }
                            }

                            return $data;
                        })

                        ->mutateRelationshipDataBeforeSaveUsing(function (array $data, Get $get): array {
                            $data['patch_ids'] = is_array($data['patch_ids'] ?? null) ? $data['patch_ids'] : [];
                            $data['joint_with'] = is_array($data['joint_with'] ?? null) ? $data['joint_with'] : [];
                            return $data;
                        })

                        ->columnSpanFull(),
                    ])->columnSpanFull(),

                Textarea::make('manager_remarks')
                        ->label('Manager Remarks')
                        ->columnSpanFull(),
            ]);
    }
}
