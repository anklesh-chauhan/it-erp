<div
    x-data="{ showMenu: false, x: 0, y: 0 }"
    @contextmenu.prevent="
        showMenu = true;
        x = $event.clientX;
        y = $event.clientY;
    "
    @click.away="showMenu = false"
    class="relative"
>
    <div
        x-show="showMenu"
        x-transition
        class="absolute z-50 bg-white border shadow-md rounded-md p-2 space-y-2"
        :style="'top: ' + y + 'px; left: ' + x + 'px;'"
    >
        <button
            @click="Livewire.emit('editRecord', {{ $record->id }})"
            class="block w-full text-left px-4 py-2 hover:bg-gray-100"
        >
            Edit
        </button>
        <button
            @click="Livewire.emit('deleteRecord', {{ $record->id }})"
            class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-red-500"
        >
            Delete
        </button>
        <button
            @click="Livewire.emit('addFollowUp', {{ $record->id }})"
            class="block w-full text-left px-4 py-2 hover:bg-gray-100"
        >
            Add Follow-up
        </button>
    </div>
</div>
