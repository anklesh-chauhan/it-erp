<?php

namespace App\Filament\Clusters\HR;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use Filament\Pages\Enums\SubNavigationPosition;

class OrganizationStructureCluster extends Cluster
{
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $clusterBreadcrumb = 'Organization Structure';

    protected static string |\UnitEnum| null $navigationGroup = 'HR';

    protected static ?string $navigationLabel = 'Organization Structure';

    protected static ?int $navigationSort = 10;
}
