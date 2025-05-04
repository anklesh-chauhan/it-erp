<div class="sidebar-wrapper">
    <!-- Toggle Button (Always Visible) -->
    <button id="toggle-sidebar" class="toggle-button">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path id="toggle-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    <!-- Sidebar Content (Collapsible) -->
    <div id="custom-sidebar" class="filament-sidebar custom-sidebar">
        <div class="sidebar-header">
            <h2 class="text-lg font-semibold text-gray-800">City Pin Codes</h2>
        </div>
        <div id="sidebar-content" class="sidebar-content">
            <ul class="space-y-3">
                <li>
                    <a href="{{ url('/admin/city-pin-codes') }}" class="flex items-center space-x-2 text-gray-700 hover:text-primary-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A2 2 0 012 15.382V5.618a2 2 0 011.553-1.894L9 1m0 19l6-3m-6 3V1m6 16.382a2 2 0 001.553-1.894V5.618a2 2 0 00-1.553-1.894L9 1m6 16l5.447-2.724A2 2 0 0022 12.382V5.618a2 2 0 00-1.553-1.894L15 1"></path>
                        </svg>
                        <span>List Pin Codes</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/admin/city-pin-codes/create') }}" class="flex items-center space-x-2 text-gray-700 hover:text-primary-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Add Pin Code</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
