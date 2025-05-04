<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <x-filament::card>
            <a href="{{ route('filament.admin.resources.follow-up-media.index') }}"
               class="block text-primary-600 hover:underline">
                Media
            </a>
        </x-filament::card>

        <x-filament::card>
            <a href="{{ route('filament.admin.resources.follow-up-priorities.index') }}"
               class="block text-primary-600 hover:underline">
                Priorities
            </a>
        </x-filament::card>

        <x-filament::card>
            <a href="{{ route('filament.admin.resources.follow-up-results.index') }}"
               class="block text-primary-600 hover:underline">
                Results
            </a>
        </x-filament::card>

        <x-filament::card>
            <a href="{{ route('filament.admin.resources.follow-up-statuses.index') }}"
               class="block text-primary-600 hover:underline">
                Status
            </a>
        </x-filament::card>


    </div>
</x-filament-panels::page>
