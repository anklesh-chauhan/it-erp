<div class="fixed bottom-0 inset-x-0 z-40 bg-white dark:bg-gray-900 border-t p-3 lg:hidden shadow-[0_-4px_10px_rgba(0,0,0,0.05)]">
    <div class="flex justify-center w-full">
        {{-- Render the specific action --}}
        {{ $this->saveAction }}
    </div>

    {{-- Important: This handles any modals if your action has a form/confirmation --}}
    <x-filament-actions::modals />
</div>
