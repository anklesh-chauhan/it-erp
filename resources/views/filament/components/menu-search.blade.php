<div x-data="{ search: '', open: false }" class="relative">
    <input
        x-model="search"
        x-on:focus="open = true"
        x-on:blur="setTimeout(() => open = false, 200)"
        type="text"
        placeholder="Search Menu..."
        class="block w-full px-3 py-2 border rounded-md focus:ring focus:ring-primary-300"
    >

    <div x-show="open && search.length > 0" class="absolute z-10 w-full mt-2 bg-white border rounded-md shadow-lg">
        <ul>
            @foreach (\App\Providers\Filament\MenuProvider::getMenuItems() as $item)
                <li
                    x-show="'{{ strtolower($item['label']) }}'.includes(search.toLowerCase())"
                    class="px-3 py-2 hover:bg-gray-100 cursor-pointer"
                    x-on:click="window.location = '{{ route($item['route']) }}'"
                >
                    {{ $item['label'] }}
                </li>
            @endforeach
        </ul>
    </div>
</div>
