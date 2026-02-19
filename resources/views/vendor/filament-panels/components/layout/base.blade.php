@props([
    'livewire' => null,
])

@php
    $renderHookScopes = $livewire?->getRenderHookScopes();
@endphp

<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ __('filament-panels::layout.direction') ?? 'ltr' }}"
    @class([
        'fi',
        'dark' => filament()->hasDarkModeForced(),
    ])
>
    <head>
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::HEAD_START, scopes: $renderHookScopes) }}

        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />


        @if ($favicon = filament()->getFavicon())
            <link rel="icon" href="{{ $favicon }}" />
        @endif

        @php
            $title = trim(strip_tags($livewire?->getTitle() ?? ''));
            $brandName = trim(strip_tags(filament()->getBrandName()));
        @endphp

        <title>
            {{ filled($title) ? "{$title} - " : null }} {{ $brandName }}
        </title>

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::STYLES_BEFORE, scopes: $renderHookScopes) }}

        <style>
            [x-cloak=''],
            [x-cloak='x-cloak'],
            [x-cloak='1'] {
                display: none !important;
            }

            [x-cloak='inline-flex'] {
                display: inline-flex !important;
            }

            @media (max-width: 1023px) {
                [x-cloak='-lg'] {
                    display: none !important;
                }
            }

            @media (min-width: 1024px) {
                [x-cloak='lg'] {
                    display: none !important;
                }
            }
        </style>

        @filamentStyles

        {{ filament()->getTheme()->getHtml() }}
        {{ filament()->getFontHtml() }}
        {{ filament()->getMonoFontHtml() }}
        {{ filament()->getSerifFontHtml() }}

        <style>
            :root {
                --font-family: '{!! filament()->getFontFamily() !!}';
                --mono-font-family: '{!! filament()->getMonoFontFamily() !!}';
                --serif-font-family: '{!! filament()->getSerifFontFamily() !!}';
                --sidebar-width: {{ filament()->getSidebarWidth() }};
                --collapsed-sidebar-width: {{ filament()->getCollapsedSidebarWidth() }};
                --default-theme-mode: {{ filament()->getDefaultThemeMode()->value }};
            }
        </style>

        @stack('styles')

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::STYLES_AFTER, scopes: $renderHookScopes) }}

        @if (! filament()->hasDarkMode())
            <script>
                localStorage.setItem('theme', 'light')
            </script>
        @elseif (filament()->hasDarkModeForced())
            <script>
                localStorage.setItem('theme', 'dark')
            </script>
        @else
            <script>
                const loadDarkMode = () => {
                    window.theme = localStorage.getItem('theme') ?? @js(filament()->getDefaultThemeMode()->value)

                    if (
                        window.theme === 'dark' ||
                        (window.theme === 'system' &&
                            window.matchMedia('(prefers-color-scheme: dark)')
                                .matches)
                    ) {
                        document.documentElement.classList.add('dark')
                    }
                }

                loadDarkMode()

                document.addEventListener('livewire:navigated', loadDarkMode)
            </script>
        @endif

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::HEAD_END, scopes: $renderHookScopes) }}
    </head>

    <body
        {{
            $attributes
                ->merge($livewire?->getExtraBodyAttributes() ?? [], escape: false)
                ->class([
                    'fi-body',
                    'fi-panel-' . filament()->getId(),
                ])
        }}
    >
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::BODY_START, scopes: $renderHookScopes) }}

        {{ $slot }}

        @livewire(Filament\Livewire\Notifications::class)

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SCRIPTS_BEFORE, scopes: $renderHookScopes) }}

        @filamentScripts(withCore: true)

        @if (filament()->hasBroadcasting() && config('filament.broadcasting.echo'))
            <script data-navigate-once>
                window.Echo = new window.EchoFactory(@js(config('filament.broadcasting.echo')))

                window.dispatchEvent(new CustomEvent('EchoLoaded'))
            </script>
        @endif

        @if (filament()->hasDarkMode() && (! filament()->hasDarkModeForced()))
            <script>
                loadDarkMode()
            </script>
        @endif

        @stack('scripts')

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SCRIPTS_AFTER, scopes: $renderHookScopes) }}

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::BODY_END, scopes: $renderHookScopes) }}

        {{-- Geolocation Script --}}
        <script>
            document.addEventListener('alpine:init', () => {
                console.log("📍 Geolocation script loaded!");

                // For Punch In
                window.startPunchIn = function ($wire) {
                    if (!navigator.geolocation) {
                        alert("Geolocation is not supported by your browser.");
                        return;
                    }

                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const lat = position.coords.latitude;
                            const lon = position.coords.longitude;

                            console.log("📌 Punch In - Location acquired:", lat, lon);

                            $wire.call('doPunchIn', {
                                latitude: lat,
                                longitude: lon
                            });
                        },
                        (error) => {
                            console.error("Geolocation error:", error);
                            alert("Location access denied. Please allow location permission and try again.");
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 15000,
                            maximumAge: 0
                        }
                    );
                };

                // For Punch Out
                window.startPunchOut = function ($wire) {
                    if (!navigator.geolocation) {
                        alert("Geolocation is not supported by your browser.");
                        return;
                    }

                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const lat = position.coords.latitude;
                            const lon = position.coords.longitude;

                            console.log("📌 Punch Out - Location acquired:", lat, lon);

                            $wire.call('doPunchOut', {
                                latitude: lat,
                                longitude: lon
                            });
                        },
                        (error) => {
                            console.error("Geolocation error:", error);
                            alert("Location access denied. Please allow location permission and try again.");
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 15000,
                            maximumAge: 0
                        }
                    );
                };
            });
        </script>
        {{-- End Geolocation Script --}}

        {{-- Livewire Geolocation Listeners --}}
        <script>
            document.addEventListener('livewire:init', () => {
                const handlePunch = (eventName, methodName) => {
                    Livewire.on(eventName, (event) => {
                        // Livewire 3 event data is usually wrapped in an array/object
                        const data = Array.isArray(event) ? event[0] : event;
                        const componentId = data.componentId;

                        if (!navigator.geolocation) {
                            alert("Geolocation is not supported by your browser.");
                            return;
                        }

                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                Livewire.find(componentId).call(
                                    methodName,
                                    position.coords.latitude,
                                    position.coords.longitude
                                );
                            },
                            (error) => alert("Location access denied. Please enable GPS."),
                            { enableHighAccuracy: true, timeout: 10000 }
                        );
                    });
                };

                handlePunch('trigger-punch-in', 'doPunchIn');
                handlePunch('trigger-punch-out', 'doPunchOut');
            });
        </script>
        {{-- End Livewire Geolocation Listeners --}}

        {{-- Image Geolocation Capture Script --}}
        <script>
            document.addEventListener('capture-image-location', () => {
                if (!navigator.geolocation) return;

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        Livewire.dispatch('set-image-location', {
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude,
                        });

                        console.log(
                            '🖼️ Image GPS:',
                            position.coords.latitude,
                            position.coords.longitude
                        );
                    },
                    () => {},
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            });
        </script>
        {{-- End Image Geolocation Capture Script --}}
    </body>
</html>
