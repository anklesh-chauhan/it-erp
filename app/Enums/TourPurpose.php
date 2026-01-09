<?php

namespace App\Enums;

enum TourPurpose: string
{
    case CustomerVisit  = 'customer_visit';
    case FollowUp       = 'follow_up';
    case NewLead        = 'new_lead';
    case Collection     = 'collection';
    case Demo           = 'demo';
    case Service        = 'service';
    case Meeting        = 'meeting';

    case FieldWork      = 'field_work';
    case CME            = 'cme';
    case AdminWork      = 'admin_work';
    case Camp           = 'camp';
    case Training       = 'training';
    case Conference     = 'conference';
    case CrossFunction  = 'cross_function';
    case Transit        = 'transit';

    case Other          = 'other';

    /* ---------------- Label ---------------- */
    public function label(): string
    {
        return match ($this) {
            self::CustomerVisit => 'Customer Visit',
            self::FollowUp      => 'Follow-up',
            self::NewLead       => 'New Lead',
            self::Collection    => 'Collection',
            self::Demo          => 'Demo',
            self::Service       => 'Service',
            self::Meeting       => 'Meeting',

            self::FieldWork     => 'Field Work',
            self::CME           => 'CME',
            self::AdminWork     => 'Admin Work',
            self::Camp          => 'Camp',
            self::Training      => 'Training',
            self::Conference    => 'Conference',
            self::CrossFunction => 'Cross Function',
            self::Transit       => 'Transit',

            self::Other         => 'Other',
        };
    }

    /* ---------------- Options for Filament ---------------- */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->label()])
            ->toArray();
    }

    /* ---------------- Optional: Grouping (UX bonus) ---------------- */
    public static function groupedOptions(): array
    {
        return [
            'Sales Activities' => [
                self::CustomerVisit->value => self::CustomerVisit->label(),
                self::FollowUp->value      => self::FollowUp->label(),
                self::NewLead->value       => self::NewLead->label(),
                self::Collection->value    => self::Collection->label(),
                self::Demo->value          => self::Demo->label(),
                self::Service->value       => self::Service->label(),
                self::Meeting->value       => self::Meeting->label(),
            ],

            'Field / Admin Activities' => [
                self::FieldWork->value     => self::FieldWork->label(),
                self::CME->value           => self::CME->label(),
                self::AdminWork->value     => self::AdminWork->label(),
                self::Camp->value          => self::Camp->label(),
                self::Training->value      => self::Training->label(),
                self::Conference->value    => self::Conference->label(),
                self::CrossFunction->value => self::CrossFunction->label(),
                self::Transit->value       => self::Transit->label(),
            ],

            'Other' => [
                self::Other->value => self::Other->label(),
            ],
        ];
    }
}
