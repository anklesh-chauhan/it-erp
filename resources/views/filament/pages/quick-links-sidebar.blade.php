<x-filament-panels::page>
    <x-filament::page>
    <div class="p-6 space-y-4">
        <h1 class="text-2xl font-bold text-primary-600">Quick Links</h1>

        <div class="space-y-2">
            <a href="{{ route('filament.admin.resources.leads.index') }}" class="block text-blue-500 hover:underline">
                ➤ Leads
            </a>
            <a href="{{ route('filament.admin.resources.follow-ups.index') }}" class="block text-blue-500 hover:underline">
                ➤ Follow-ups
            </a>
            {{-- <a href="{{ route('filament.admin.resources.tasks.index') }}" class="block text-blue-500 hover:underline">
                ➤ Tasks
            </a>
            <a href="{{ route('filament.admin.resources.payments.index') }}" class="block text-blue-500 hover:underline">
                ➤ Payments
            </a> --}}
        </div>
    </div>
</x-filament::page>


</x-filament-panels::page>
