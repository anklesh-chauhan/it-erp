@if($state['contactId'] ?? false)
    <a href="{{ route('filament.admin.resources.contact-details.edit', $state['contactId']) }}"
       target="_blank"
       class="text-primary-600 hover:underline">
       ✏️ Edit Contact
    </a>
@else
    <span>No contact selected.</span>
@endif
