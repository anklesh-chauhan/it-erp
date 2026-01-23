<?php

namespace App\Filament\Clusters\GlobalConfiguration;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use Filament\Pages\Enums\SubNavigationPosition;

class OrganizationAndCompanyCluster extends Cluster
{
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $clusterBreadcrumb = 'Organization & Company';

    protected static string |\UnitEnum| null $navigationGroup = 'Global Configuration';

    protected static ?string $navigationLabel = 'Organization & Company';

    protected static ?int $navigationSort = 10;
}
