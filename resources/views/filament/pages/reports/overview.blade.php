<x-filament-panels::page>
    <div class="space-y-8">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Operational registers grouped by pack. Counts follow record visibility.
            Open a card to filter and export.
        </p>

        @foreach ($this->getCatalogGroups() as $group)
            <x-filament::section :heading="$group['label']" :description="$group['description']" :icon="$group['icon']">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($group['reports'] as $report)
                        @if ($report['url'])
                            <a
                                href="{{ $report['url'] }}"
                                class="flex h-full flex-col gap-3 rounded-xl bg-white p-4 ring-1 ring-gray-950/5 transition hover:ring-primary-500 dark:bg-white/5 dark:ring-white/10 dark:hover:ring-primary-400"
                            >
                                <div class="flex items-start justify-between gap-3">
                                    <span class="flex size-10 items-center justify-center rounded-lg bg-primary-50 text-primary-600 dark:bg-primary-400/10 dark:text-primary-400">
                                        <x-filament::icon :icon="$report['icon']" class="size-5" />
                                    </span>
                                    <span class="text-sm font-medium text-primary-600 dark:text-primary-400">
                                        Open
                                    </span>
                                </div>

                                <div class="space-y-1">
                                    <h3 class="text-sm font-semibold text-gray-950 dark:text-white">
                                        {{ $report['label'] }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $report['description'] }}
                                    </p>
                                </div>
                            </a>
                        @else
                            <div class="flex h-full flex-col gap-3 rounded-xl bg-white p-4 ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10">
                                <span class="flex size-10 items-center justify-center rounded-lg bg-gray-50 text-gray-400 dark:bg-white/5">
                                    <x-filament::icon :icon="$report['icon']" class="size-5" />
                                </span>
                                <div class="space-y-1">
                                    <h3 class="text-sm font-semibold text-gray-950 dark:text-white">
                                        {{ $report['label'] }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $report['description'] }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </x-filament::section>
        @endforeach
    </div>
</x-filament-panels::page>
