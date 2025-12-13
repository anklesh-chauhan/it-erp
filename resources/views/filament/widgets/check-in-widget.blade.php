<div class="flex items-center gap-4 px-4">
    @if (!$record)
        <x-filament::button wire:click="checkIn" color="success" size="xs">
            👋 Check In
        </x-filament::button>

    @elseif (!$record->check_out)
        <div class="flex items-center gap-3">
            <div class="text-xs font-semibold text-green-600 dark:text-green-400">
                🟢 {{ $record->check_in->format('h:i A') }}
            </div>
            <x-filament::button wire:click="checkOut" color="danger" size="xs">
                🚪 Check Out
            </x-filament::button>
        </div>

    @else
        <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 text-right">
            <div>🟦 Done</div>
            <div>
                {{ $record->check_in->format('h:i') }} - {{ $record->check_out->format('h:i') }}
            </div>
        </div>
    @endif
</div>
