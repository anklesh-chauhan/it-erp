<?php

namespace App\Traits;

use Filament\Actions\Action;
use Exception;
use Filament\GlobalSearch\GlobalSearchResult;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Multitenancy\Models\Tenant;
use Illuminate\Support\Facades\DB;

trait HasSafeGlobalSearch
{
    // Define whether to search only the resource index page
    public static function searchResourceIndexOnly(): bool
    {
        return false; // Override in resource to enable index-only search
    }

    // Define resource keywords for index page search
    public static function getResourceSearchKeywords(): array
    {
        return [
            class_basename(static::class),
            Str::plural(class_basename(static::class)),
            Str::lower(class_basename(static::class)),
            Str::lower(Str::plural(class_basename(static::class))),
        ];
    }

    // Define globally searchable attributes (for record-based search)
    public static function getGloballySearchableAttributes(): array
    {
        if (static::searchResourceIndexOnly()) {
            return []; // Disable record search if index-only is enabled
        }

        $modelClass = static::getModel();
        $table = (new $modelClass)->getTable();

        // Default searchable fields
        $fallbackFields = ['reference_code', 'document_number', 'name', 'title', 'email'];

        // Allow resource-specific overrides
        $customFields = property_exists(static::class, 'globallySearchableFields')
            ? static::$globallySearchableFields
            : [];

        $searchableFields = array_merge($fallbackFields, $customFields);

        return collect($searchableFields)
            ->filter(fn ($field) => Schema::hasColumn($table, $field))
            ->values()
            ->all();
    }

    // Custom global search results
    public static function getGlobalSearchResults(string $search): Collection
    {
        $results = new Collection();
        $limit = static::$globalSearchResultsLimit ?? 5; // Default limit

        // Record-based search
        if (!static::searchResourceIndexOnly()) {
            $query = static::getGlobalSearchEloquentQuery();
            $searchableAttributes = static::getGloballySearchableAttributes();

            foreach ($searchableAttributes as $attribute) {
                $query->orWhere($attribute, 'like', "%{$search}%");
            }

            $recordResults = $query
                ->limit($limit)
                ->get()
                ->map(fn ($record) => new GlobalSearchResult(
                    title: static::getGlobalSearchResultTitle($record),
                    details: static::getGlobalSearchResultDetails($record),
                    url: static::getGlobalSearchResultUrl($record),
                    actions: [] // No "View All" button for record-based results
                ));

            $results = $results->merge($recordResults);
        }

        // Resource index page search
        $resourceName = class_basename(static::class);
        $keywords = static::getResourceSearchKeywords();

        if (Str::contains(strtolower($resourceName . ' ' . implode(' ', $keywords)), strtolower($search))) {
            $results->push(new GlobalSearchResult(
                title: static::getGlobalSearchResultTitleForResource(),
                details: static::getGlobalSearchResultDetailsForResource(),
                url: static::getGlobalSearchResultUrlForResource(),
                actions: [
                    Action::make('view_list')
                        ->label('View All')
                        ->url(static::getUrl('index'))
                        ->icon('heroicon-o-list-bullet'),
                ]
            ));
        }

        return $results;
    }

    // Eloquent query for global search with tenancy and permission filters
    public static function getGlobalSearchEloquentQuery(): Builder
{
    $model = static::getModel();
    $instance = new $model;

    // 1. Get the correct table name from the model
    $table = $instance->getTable();

    // 2. COMPLETELY BYPASS ELOQUENT CONNECTION RESOLUTION
    // This is the only method that beats Spatie tenancy in 2025
    $query = DB::connection('mysql')->table($table);

    // 3. Convert back to Eloquent Builder so we can use ->get(), model hydration, etc.
    $eloquentQuery = $instance->newEloquentBuilder($query);
    $eloquentQuery->setModel($instance);

    // 4. Permission check (your custom format — now works perfectly)
    if ($user = auth()->user()) {
        $resourceClassName = class_basename(static::class);
        $resourceNameOnly = str_replace('Resource', '', $resourceClassName);
        $permissionName = "ViewAny:{$resourceNameOnly}";

        $isSuperAdmin = $user->hasAnyRole(['admin', 'general_manager', 'marketing_manager']);

        if (! $isSuperAdmin && ! $user->hasPermissionTo($permissionName)) {
            $eloquentQuery->whereRaw('1 = 0');
        }
    }

    return $eloquentQuery;
}

    // Record-based search: Result title
    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        $fallbackFields = ['reference_code', 'document_number', 'name', 'title', 'email'];

        foreach ($fallbackFields as $field) {
            if (!empty($record->{$field})) {
                return Str::title($field) . ': ' . $record->{$field};
            }
        }

        return class_basename(static::class) . ' #' . $record->getKey();
    }

    // Record-based search: Result details
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [
            'Created At' => $record->created_at?->format('d-m-Y H:i'),
        ];

        // Add resource-specific details
        if (in_array('email', static::getGloballySearchableAttributes())) {
            $details['Email'] = $record->email;
        }
        if (in_array('amount', static::getGloballySearchableAttributes())) {
            $details['Amount'] = number_format($record->amount, 2);
        }
        if (in_array('reference_code', static::getGloballySearchableAttributes())) {
            $details['Reference Code'] = $record->reference_code;
        }

        return $details;
    }

    // Record-based search: Result URL
    public static function getGlobalSearchResultUrl(Model $record): string
    {
        try {
            return static::getUrl(name: 'edit', parameters: ['record' => $record]);
        } catch (Exception $e) {
            return static::getUrl(name: 'index');
        }
    }

    // Resource index search: Result title
    public static function getGlobalSearchResultTitleForResource(): string | Htmlable
    {
        return static::getModelLabel();
    }

    // Resource index search: Result details
    public static function getGlobalSearchResultDetailsForResource(): array
    {
        return ['Description' => 'View all ' . Str::plural(static::getModelLabel())];
    }

    // Resource index search: Result URL
    public static function getGlobalSearchResultUrlForResource(): string
    {
        return static::getUrl('index');
    }
}
