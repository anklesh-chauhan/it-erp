<?php

namespace App\Filament\Resources\ShiftMasters\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Group;
use Carbon\Carbon;

class ShiftMasterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Shift Configuration')
                    ->tabs([
                        /* ================= TAB 1 ================= */
                        Tabs\Tab::make('Basic Details')
                            ->schema([
                                Section::make('Basic Details')
                                    ->schema([
                                        TextInput::make('code')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(20),

                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(100),

                                        Select::make('shift_type')
                                            ->options([
                                                'fixed' => 'Fixed',
                                                'rotational' => 'Rotational',
                                            ])
                                            ->default('fixed')
                                            ->searchable()
                                            ->required(),

                                        Select::make('week_off_type')
                                            ->options([
                                                'fixed' => 'Fixed',
                                                'rotational' => 'Rotational',
                                                'none' => 'None',
                                            ])
                                            ->default('none')
                                            ->searchable()
                                            ->required(),
                                    ])
                                    ->columns(4),

                                Section::make('Shift Timings')
                                    ->schema([
                                        TimePicker::make('start_time')
                                            ->seconds(false)
                                            ->format('H:i')
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(function (Get $get, Set $set) {
                                                self::calculateShiftAndHalves($get, $set);
                                            }),

                                        TimePicker::make('end_time')
                                            ->seconds(false)
                                            ->format('H:i')
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(function (Get $get, Set $set) {
                                                self::calculateShiftAndHalves($get, $set);
                                            }),

                                        TimePicker::make('first_half_start_at')
                                            ->seconds(false)
                                            ->format('H:i')
                                            ->dehydrated(),
                                        TimePicker::make('first_half_end_at')
                                            ->seconds(false)
                                            ->format('H:i')
                                            ->dehydrated(),

                                        TimePicker::make('second_half_start_at')
                                            ->seconds(false)
                                            ->format('H:i')
                                            ->dehydrated(),
                                        TimePicker::make('second_half_end_at')
                                            ->seconds(false)
                                            ->format('H:i')
                                            ->dehydrated(),

                                        TextInput::make('shift_duration_hours')
                                            ->numeric()
                                            ->dehydrated(), // important: saves value to DB

                                        TextInput::make('overtime_start_minutes')
                                            ->label('Overtime Start after Minutes')
                                            ->numeric()
                                            ->default(0),
                                    ])
                                    ->columns(4),

                                Section::make('Flags')
                                    ->schema([
                                        Toggle::make('is_first_in_last_out_punch')
                                            ->label('First in Last Out Punch'),
                                        Toggle::make('is_night_shift')
                                            ->label('Night Shift?'),
                                        Toggle::make('is_flexible')
                                            ->label('Flaxible?'),
                                        Toggle::make('is_system')
                                            ->disabled()
                                            ->helperText('System shifts cannot be edited'),
                                    ])
                                    ->columns(4),

                                Section::make('Break & Meal Settings')
                                    ->schema([
                                        Toggle::make('is_lunch_time_flexible'),
                                        TextInput::make('lunch_break_minutes')->numeric(),
                                        TimePicker::make('lunch_start_time'),
                                        TimePicker::make('lunch_end_time'),

                                        Toggle::make('is_dinner_time_flexible'),
                                        TextInput::make('dinner_break_minutes')->numeric(),
                                        TimePicker::make('dinner_start_time'),
                                        TimePicker::make('dinner_end_time'),

                                        Toggle::make('is_break_time_flexible'),
                                        TextInput::make('break_minutes')->numeric(),
                                        TimePicker::make('break_start_time'),
                                        TimePicker::make('break_end_time'),
                                    ])
                                    ->columns(4),

                                    ])->columns(1),
                        /* ================= TAB 2 ================= */
                        Tabs\Tab::make('General Setup')
                            ->schema([
                                Section::make('General Setup')
                                    ->schema([
                                        Section::make('Working Hours Calculation')
                                            ->schema([
                                                Toggle::make('reduce_lunch_break_minutes_from_working_hours')
                                                    ->label('Reduce Lunch Break'),

                                                Toggle::make('reduce_dinner_break_minutes_from_working_hours')
                                                    ->label('Reduce Dinner Break'),

                                                Toggle::make('reduce_break_minutes_from_working_hours')
                                                    ->label('Reduce Other Breaks'),
                                            ])
                                            ->columns(2),

                                        Section::make('Auto Conversions')
                                            ->schema([
                                                Toggle::make('auto_convert_wop_to_co_plus')
                                                    ->label('Auto Convert WOP to CO+'),

                                                Toggle::make('auto_convert_php_to_co_plus')
                                                    ->label('Auto Convert PHP to CO+'),
                                            ])
                                            ->columns(2),

                                        Section::make('Permissions')
                                            ->schema([
                                                Toggle::make('calculate_compensation'),
                                                Toggle::make('is_allow_auto_shift'),
                                                Toggle::make('is_allow_half_day_leave'),
                                                Toggle::make('is_allow_shift_change_request'),
                                            ])
                                            ->columns(2),
                                    ])->columns(1),
                            ])->columns(1),

                        /* ================= TAB 3 ================= */
                        Tabs\Tab::make('Time Slab')
                            ->schema([
                                Section::make('Time Slabs Configuration')
                                    ->description('Define minute-based rules for late, compensation and OT rounding')
                                    ->schema([
                                        Repeater::make('timeSlabSetups')
                                            ->relationship()
                                            ->schema([
                                                Select::make('time_slab_type')
                                                    ->label('Slab Type')
                                                    ->options([
                                                        'late_in' => 'Late In',
                                                        'late_out' => 'Late Out',
                                                        'compensation_hours' => 'Compensation Hours',
                                                        'round_off_ot_hours' => 'Round Off OT Hours',
                                                    ])
                                                    ->reactive()
                                                    ->required(),

                                                TextInput::make('from_minute')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->label(fn (Get $get) => match ($get('time_slab_type')) {
                                                        'late_in', 'late_out' => 'From (Minutes)',
                                                        'compensation_hours' => 'Additional From (Minutes)',
                                                        'round_off_ot_hours' => 'From (Minutes)',
                                                        default => 'From (Minutes)',
                                                    })
                                                    ->required(),

                                                TextInput::make('to_minute')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->label(fn (Get $get) => match ($get('time_slab_type')) {
                                                        'late_in', 'late_out' => 'To (Minutes)',
                                                        'compensation_hours' => 'Additional To (Minutes)',
                                                        'round_off_ot_hours' => 'To (Minutes)',
                                                        default => 'To (Minutes)',
                                                    })
                                                    ->required(),

                                                TextInput::make('diff_calc')
                                                    ->numeric()
                                                    ->label(fn (Get $get) => match ($get('time_slab_type')) {
                                                        'late_in', 'late_out' => 'Late Mark Count',
                                                        'compensation_hours' => 'Compensation Hours',
                                                        'round_off_ot_hours' => 'Rounded OT Hours',
                                                        default => 'Calculate Value',
                                                    })
                                                    ->helperText('Value to be considered for calculation'),
                                            ])
                                            ->columns(4)
                                            ->defaultItems(0)
                                            ->reorderable()
                                            ->collapsible(),
                                    ]),
                            ]),
                        /* ================= TAB 4 ================= */
                        Tabs\Tab::make('Late Mark Rules')
                            ->schema([
                                Group::make()
                                    ->relationship('lateMarkSetup')
                                    ->schema([

                                        Section::make('Activation')
                                            ->schema([
                                                Toggle::make('is_active')
                                                    ->label('Enable Late Mark Policy'),
                                            ]),

                                        Section::make('Grace Period (Minutes)')
                                            ->schema([
                                                TextInput::make('late_in_grace_minutes')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->label('Late In Grace (Minutes)'),

                                                TextInput::make('early_out_grace_minutes')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->label('Early Out Grace (Minutes)'),
                                            ])
                                            ->columns(2),

                                        Section::make('Monthly Thresholds')
                                            ->schema([
                                                TextInput::make('total_late_in_early_out_mark_threshold_minutes_in_month')
                                                    ->numeric()
                                                    ->label('Total Grace Minutes in Month'),

                                                TextInput::make('total_late_in_early_out_mark_no_of_times_in_month')
                                                    ->numeric()
                                                    ->label('Max Late Mark Count in Month'),
                                            ])
                                            ->columns(2),

                                        Section::make('Late Mark Rules')
                                            ->schema([
                                                Toggle::make('is_save_late_minutes_as_late_mark')
                                                    ->label('Convert Late Minutes to Late Marks'),

                                                Toggle::make('is_calculate_on_weekly_off_and_paid_holiday')
                                                    ->label('Calculate on Weekly Off / Paid Holiday'),

                                                Toggle::make('is_mark_abs_once_late_mark_grace_crossed_in_a_month')
                                                    ->label('Mark Absent When Grace Crossed'),
                                            ])
                                            ->columns(2),

                                        Section::make('Exclusions')
                                            ->schema([
                                                Toggle::make('is_avoid_latemark_on_half_day_absent')
                                                    ->label('Ignore Late Mark on Half Day Absent'),

                                                Toggle::make('is_avoid_latemark_on_full_day_absent')
                                                    ->label('Ignore Late Mark on Full Day Absent'),
                                            ])
                                            ->columns(2),

                                        Section::make('Conversion Rates')
                                            ->description(
                                                'Conversion rate is calculated based on either Grace Late Marks Count, Late Marks Count, or both.'
                                            )
                                            ->schema([
                                                TextInput::make('conversion_rate_grace_late_mark_count')
                                                    ->numeric()
                                                    ->label('Grace Late Marks Count'),

                                                TextInput::make('conversion_rate_no_of_late_mark_count')
                                                    ->numeric()
                                                    ->label('Late Marks Count'),

                                                TextInput::make('conversion_rate_no_of_day_absent')
                                                    ->numeric()
                                                    ->label('Absent Days'),
                                            ])
                                            ->columns(3),
                                    ]),
                            ]),

                        /* ================= TAB 5 ================= */
                        Tabs\Tab::make('Day Work Rules')
                            ->schema([
                                Group::make()
                                    ->relationship('dayWorkSetup')
                                    ->schema([

                                        Section::make('Activation')
                                            ->schema([
                                                Toggle::make('is_active')
                                                    ->label('Enable Day Work Rules'),
                                            ]),

                                        Section::make('Half Day Cutoff Rules (Minutes)')
                                            ->schema([
                                                TextInput::make('first_half_late_in_cutoff_minutes')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->label('First Half Late In Cutoff'),

                                                TextInput::make('first_half_early_out_cutoff_minutes')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->label('First Half Early Out Cutoff'),

                                                TextInput::make('second_half_late_in_cutoff_minutes')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->label('Second Half Late In Cutoff'),

                                                TextInput::make('second_half_early_out_cutoff_minutes')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->label('Second Half Early Out Cutoff'),
                                            ])
                                            ->columns(2),

                                        Section::make('Early / Late Work Calculation')
                                            ->schema([
                                                Toggle::make('add_early_in_minutes')
                                                    ->label('Add Early In Minutes'),

                                                Toggle::make('add_late_out_minutes')
                                                    ->label('Add Late Out Minutes'),
                                            ])
                                            ->columns(2),

                                        Section::make('Daily Limits (Minutes)')
                                            ->schema([
                                                TextInput::make('daily_early_in_limit_minutes')
                                                    ->numeric()
                                                    ->label('Daily Early In Limit'),

                                                TextInput::make('daily_late_out_limit_minutes')
                                                    ->numeric()
                                                    ->label('Daily Late Out Limit'),
                                            ])
                                            ->columns(2),

                                        Section::make('Monthly Grace Limits')
                                            ->schema([
                                                TextInput::make('monthly_early_in_grace_no_of_times')
                                                    ->numeric()
                                                    ->label('Monthly Early In Grace Count'),

                                                TextInput::make('monthly_late_out_grace_no_of_times')
                                                    ->numeric()
                                                    ->label('Monthly Late Out Grace Count'),
                                            ])
                                            ->columns(2),
                                    ]),
                            ]),

                        /* ================= TAB 6 ================= */
                        Tabs\Tab::make('Over Time Policy')
                            ->schema([
                                Group::make()
                                    ->relationship('overTimeSetup')
                                    ->schema([

                                        Section::make('Activation')
                                            ->schema([
                                                Toggle::make('is_active')
                                                    ->label('Enable Over Time'),
                                            ]),

                                        Section::make('OT Eligibility')
                                            ->schema([
                                                Toggle::make('is_weekly_off_paid_holiday_as_ot')
                                                    ->label('Weekly Off / Paid Holiday as OT'),

                                                TextInput::make('minimum_ot_hours_required_per_day')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->label('Minimum OT Hours Per Day'),
                                            ])
                                            ->columns(2),

                                        Section::make('OT Calculation')
                                            ->schema([
                                                Select::make('ot_calculation_basis')
                                                    ->options([
                                                        'fixed_hours' => 'Fixed Hours',
                                                        'actual_hours' => 'Actual Working Hours',
                                                    ])
                                                    ->label('OT Calculation Basis'),

                                                Select::make('ot_rounding_method')
                                                    ->options([
                                                        'none' => 'No Rounding',
                                                        'based_on_slab' => 'Based on Slab',
                                                        'up_to_nearest_15_minutes' => 'Nearest 15 Minutes',
                                                        'up_to_nearest_30_minutes' => 'Nearest 30 Minutes',
                                                        'up_to_nearest_hour' => 'Nearest Hour',
                                                    ])
                                                    ->label('OT Rounding Method'),
                                            ])
                                            ->columns(2),

                                        Section::make('Limits')
                                            ->schema([
                                                TextInput::make('maximum_ot_hours_allowed_per_day')
                                                    ->numeric()
                                                    ->label('Max OT Hours Per Day'),

                                                TextInput::make('maximum_ot_hours_per_month')
                                                    ->numeric()
                                                    ->label('Max OT Hours Per Month'),
                                            ])
                                            ->columns(2),

                                        Section::make('Special Rules')
                                            ->schema([
                                                Toggle::make('consider_working_hours_as_ot_if_less_than_half_day_hours')
                                                    ->label('Treat Working Hours as OT if Less Than Half Day'),

                                                Select::make('monthly_total_ot_round_off_method')
                                                    ->options([
                                                        'none' => 'No Rounding',
                                                        'based_on_slab' => 'Based on Slab',
                                                        'up_to_nearest_15_minutes' => 'Nearest 15 Minutes',
                                                        'up_to_nearest_30_minutes' => 'Nearest 30 Minutes',
                                                        'up_to_nearest_hour' => 'Nearest Hour',
                                                    ])
                                                    ->label('Monthly OT Round Off Method'),
                                            ])
                                            ->columns(2),

                                        Section::make('Approval')
                                            ->schema([
                                                Toggle::make('is_approval_required')
                                                    ->label('Approval Required for OT'),
                                            ]),
                                    ]),
                            ]),

                        /* ================= TAB 7 ================= */
                        Tabs\Tab::make('Comp Off Rules')
                            ->schema([
                                Group::make()
                                    ->relationship('compOffSetup')
                                    ->schema([

                                        Section::make('Activation')
                                            ->schema([
                                                Toggle::make('is_active')
                                                    ->label('Enable Comp Off'),
                                            ]),

                                        Section::make('Eligibility Rules')
                                            ->schema([
                                                Toggle::make('is_weekly_off_paid_holiday_as_comp_off')
                                                    ->label('Weekly Off / Paid Holiday as Comp Off'),

                                                TextInput::make('minimum_comp_off_hours_required_per_day')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->label('Minimum Comp Off Hours Required Per Day'),
                                            ])
                                            ->columns(2),

                                        Section::make('Conversion Rules')
                                            ->schema([
                                                TextInput::make('conversion_daily_ot_hours')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->label('Daily OT Hours for Conversion'),

                                                TextInput::make('conversion_co_plus_credit_days')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->label('CO+ Credit Days'),
                                            ])
                                            ->columns(2),
                                    ]),
                            ]),
                    ])->columnSpanFull(),
                ]);
    }

    protected static function calculateShiftAndHalves(Get $get, Set $set): void
    {
        $start = $get('start_time');
        $end   = $get('end_time');

        if (! $start || ! $end) {
            return;
        }

        $startTime = Carbon::createFromFormat('H:i', $start);
        $endTime   = Carbon::createFromFormat('H:i', $end);

        // Night shift handling
        if ($endTime->lessThanOrEqualTo($startTime)) {
            $endTime->addDay();
            $set('is_night_shift', true);
        } else {
            $set('is_night_shift', false);
        }

        // Total duration
        $totalMinutes = $startTime->diffInMinutes($endTime);
        $totalHours   = round($totalMinutes / 60, 2);
        $set('shift_duration_hours', $totalHours);

        // Half duration
        $halfMinutes = intdiv($totalMinutes, 2);

        $firstHalfStart  = $startTime->copy();
        $firstHalfEnd    = $startTime->copy()->addMinutes($halfMinutes);

        $secondHalfStart = $firstHalfEnd->copy();
        $secondHalfEnd   = $endTime->copy();

        // Persist times
        $set('first_half_start_at',  $firstHalfStart->format('H:i'));
        $set('first_half_end_at',    $firstHalfEnd->format('H:i'));
        $set('second_half_start_at', $secondHalfStart->format('H:i'));
        $set('second_half_end_at',   $secondHalfEnd->format('H:i'));
    }

}
