<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'openxzbt') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>

    <body class="bg-[#050608] text-zinc-100 min-h-screen antialiased">
        <div class="min-h-screen flex flex-col">

            {{-- =============================================================
                CYBERPUNK HEADER FOR LOGIN / REGISTER ONLY
            ============================================================= --}}
            <header class="fixed top-0 left-0 w-full bg-black/95 border-b border-white/15 z-50">
                <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">

                    {{-- LEFT --}}
                    <div class="flex items-center gap-3">
                        <span class="text-lg text-white tracking-tight font-semibold">openxzbt</span>
                        <span class="text-[11px] text-white/40">:: auth</span>
                    </div>

                    @php
                        $navRed = function ($route) {
                            return request()->routeIs($route)
                                ? 'border-[#f87171]/80 bg-[#3b0d0d] text-[#fecaca]'
                                : 'border-white/30 text-white/60 hover:text-white';
                        };
                    @endphp

                    {{-- CENTER NAV --}}
                    <nav class="hidden md:flex items-center gap-2 text-xs">

                        {{-- HOME --}}
                        <a href="{{ route('home') }}"
                           class="px-3 py-1 border tracking-tight rounded-none transition {{ $navRed('home') }}">
                            [*] HOME
                        </a>

                        {{-- SIGN UP --}}
                        <a href="{{ route('register') }}"
                           class="px-3 py-1 border tracking-tight rounded-none transition
                                  {{ request()->routeIs('register')
                                        ? 'border-[#22c55e]/70 bg-[#052713] text-[#bbf7d0]'
                                        : 'border-white/30 text-white/60 hover:text-white' }}">
                            [+] SIGN_UP
                        </a>

                    </nav>

                    {{-- RIGHT ICON --}}
                    <div class="h-10 w-10 border border-white/30 bg-[#111] text-white/40 text-[10px] flex items-center justify-center rounded-none">
                        :|
                    </div>

                </div>
            </header>

            {{-- MAIN CONTENT --}}
            <main class="flex-1 pt-28 pb-12">
                {{ $slot }}
            </main>

        </div>

        @stack('scripts')
        @livewireScripts
    </body>
</html>
