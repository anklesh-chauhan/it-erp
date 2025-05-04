<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <x-filament::card>
            <a href="{{ route('filament.admin.resources.lead-sources.index') }}"
               class="block text-primary-600 hover:underline">
                Lead Source
            </a>
        </x-filament::card>

        <x-filament::card>
            <a href="{{ route('filament.admin.resources.lead-statuses.index') }}"
               class="block text-primary-600 hover:underline">
                Lead Status
            </a>
        </x-filament::card>

        <x-filament::card>
            <a href="{{ route('filament.admin.resources.rating-types.index') }}"
               class="block text-primary-600 hover:underline">
                Lead Rating Types
            </a>
        </x-filament::card>

        <x-filament::card>
            <a href="{{ route('filament.admin.resources.lead-custom-fields.index') }}"
               class="block text-primary-600 hover:underline">
                Lead Custom Fields
            </a>
        </x-filament::card>


    </div>
</x-filament-panels::page>
