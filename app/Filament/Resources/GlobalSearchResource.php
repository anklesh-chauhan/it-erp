<?php

namespace App\Filament\Resources;

use Filament\GlobalSearch\GlobalSearchResult;
use Illuminate\Support\Collection;

class GlobalSearchResource
{
    public static function getGlobalSearchResults(string $search): array
    {
        $results = [];

        // ✅ Get search results from resources
        foreach (self::getSearchableResources() as $resource) {
            $results = array_merge($results, $resource::getGlobalSearchResults($search));
        }

        // ✅ Add module index pages if search matches module name
        $modules = [
            'Leads' => 'filament.admin.resources.leads.index',
            'Companies' => 'filament.admin.resources.company-masters.index',
            'Contacts' => 'filament.admin.resources.contacts.index',
            'Items' => 'filament.admin.resources.items.index',
        ];

        foreach ($modules as $name => $routeName) {
            if (strlen($search) >= 3 && stripos($name, $search) !== false) {
                $results[] = new GlobalSearchResult(
                    title: "View All {$name}",
                    url: route($routeName),
                );
            }
        }

        return $results;
    }

    protected static function getSearchableResources(): array
    {
        return [
            \App\Filament\Resources\LeadResource::class,
            \App\Filament\Resources\CompanyMasterResource::class,
            \App\Filament\Resources\ContactDetailResource::class,
            \App\Filament\Resources\ItemMasterResource::class,
        ];
    }
}
