@props([
    'header' => 'Sidebar',
    'menuItems' => [],
])

<div class="sidebar-wrapper">
    <!-- Toggle Button (Always Visible) -->
    <button id="toggle-sidebar" class="toggle-button collapsed">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path id="toggle-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>

    <!-- Sidebar Content (Collapsible) -->
    <div id="custom-sidebar" class="filament-sidebar custom-sidebar collapsed">
        <div class="sidebar-header">
            <h2 class="text-lg font-semibold text-gray-800">{{ $header }}</h2>
        </div>
        <div id="sidebar-content" class="sidebar-content">
            <ul class="space-y-3">
                @foreach ($menuItems as $item)
                    <li>
                        <a href="{{ $item['url'] }}" class="flex items-center space-x-2 text-gray-700 hover:text-primary-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path>
                            </svg>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
