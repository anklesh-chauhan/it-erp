<x-filament-panels::page>

    @if ($todayPlans->isEmpty())
        <x-filament::card class="flex flex-col items-center justify-center p-12 text-center space-y-2">
            <div class="p-3 bg-gray-100 rounded-full dark:bg-gray-800">
                <x-filament::icon icon="heroicon-o-map" class="h-8 w-8 text-gray-500" />
            </div>
            <h2 class="text-lg font-bold tracking-tight">No approved tour planned</h2>
            <p class="text-sm text-gray-500">You don't have any approved tours scheduled for today.</p>
        </x-filament::card>
    @else

        <div class="grid gap-y-6">

            @foreach ($todayPlans as $detail)

                <div class="grid gap-y-2">

                    @foreach ($detail->patches as $patch)
                        <x-filament::section collapsible>
                            <x-slot name="heading">
                                <span class="text-xl font-bold tracking-tight">
                                     Patch: {{ $patch->name }}
                                </span>
                            </x-slot>

                            @if ($patch->companies->isEmpty())
                                <p class="text-sm text-gray-500 italic">No customers mapped to this patch.</p>
                            @else

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-2">

                                    @foreach ($patch->companies as $company)
                                        <x-filament::card class="flex flex-col justify-between space-y-4">
                                            <div class="space-y-2">
                                                @if ($company->typeMaster?->name)
                                                    <x-filament::badge color="primary" size="sm" class="w-fit">
                                                        {{ $company->typeMaster->name }}
                                                    </x-filament::badge>
                                                @endif

                                                <p class="font-semibold text-gray-900 dark:text-white line-clamp-2">
                                                    {{ $company->name }}
                                                </p>

                                                @if ($company->phone_number)
                                                <a href="tel:{{ $company->phone_number }}"
                                                class="text-primary-600 hover:underline">
                                                    {{ $company->phone_number }}
                                                </a>
                                                @endif

                                                @php
                                                    $contact = $company->contactDetails->first();
                                                @endphp

                                                @if ($contact)
                                                    <div class="text-sm text-gray-600 dark:text-gray-300 space-y-1">

                                                        {{-- Contact Name + Designation --}}
                                                        <div class="flex items-center gap-2">
                                                            <x-filament::icon icon="heroicon-m-user" class="w-4 h-4 text-gray-400" />
                                                            <span class="font-medium">
                                                                {{ $contact->full_name }}
                                                            </span>

                                                            @if ($contact->designation)
                                                                <x-filament::badge color="gray" size="xs">
                                                                    {{ $contact->designation->name }}
                                                                </x-filament::badge>
                                                            @endif
                                                        </div>

                                                        {{-- Mobile --}}
                                                        @if ($contact->mobile_number)
                                                            <div class="flex items-center gap-2">
                                                                <x-filament::icon icon="heroicon-m-phone" class="w-4 h-4 text-gray-400" />
                                                                <a href="tel:{{ $contact->mobile_number }}"
                                                                class="text-primary-600 hover:underline">
                                                                    {{ $contact->mobile_number }}
                                                                </a>
                                                            </div>
                                                        @endif

                                                        {{-- Email --}}
                                                        @if ($contact->email)
                                                            <div class="flex items-center gap-2">
                                                                <x-filament::icon icon="heroicon-m-envelope" class="w-4 h-4 text-gray-400" />
                                                                <a href="mailto:{{ $contact->email }}"
                                                                class="text-gray-600 hover:underline text-sm truncate">
                                                                    {{ $contact->email }}
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <p class="text-xs text-gray-400 italic">No contact person</p>
                                                @endif

                                            </div>

                                            <x-filament::button
                                                size="xs"
                                                icon="heroicon-m-play"
                                                wire:click="openVisit({{ $detail->id }}, {{ $detail->sales_tour_plan_id }}, {{ $detail->territory_id }}, {{ $patch->id }}, {{ $company->id }})"
                                                class="w-full"
                                            >
                                                Visit
                                            </x-filament::button>
                                        </x-filament::card>
                                    @endforeach

                                </div>
                            @endif
                        </x-filament::section>
                    @endforeach

                </div>
            @endforeach

        </div>
    @endif

</x-filament-panels::page>
