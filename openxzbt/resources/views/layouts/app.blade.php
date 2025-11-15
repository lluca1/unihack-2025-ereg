<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'openxzbt') }}</title>

        <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;600&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="bg-[#050608] text-zinc-100 font-['JetBrains_Mono'] antialiased min-h-screen">
        <div class="min-h-screen flex flex-col">
            <header class="fixed top-0 left-0 w-full bg-black/95 border-b border-white/15 z-50">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">

        {{-- LEFT SIDE --}}
        <div class="flex items-center gap-3">
            <span class="text-lg text-white tracking-tight font-semibold">openxzbt</span>

            <span class="text-[11px] text-white/40">
                :: 
                @if(request()->routeIs('login'))
                    auth_login
                @elseif(request()->routeIs('register'))
                    auth_register
                @elseif(request()->routeIs('home'))
                    home
                @else
                    3d_exposition_index
                @endif
            </span>
        </div>

        @php
            // Active vs inactive nav style helper
            $nav = function ($route) {
                return request()->routeIs($route)
                    ? 'border-[#facc15]/90 bg-[#26220b] text-[#fef3c7]'
                    : 'border-white/30 text-white/60 hover:text-white';
            };

            $navRed = function ($route) {
                return request()->routeIs($route)
                    ? 'border-[#f87171]/80 bg-[#3b0d0d] text-[#fecaca]'
                    : 'border-white/30 text-white/60 hover:text-white';
            };
        @endphp

        {{-- ============================================================
            NAVIGATION LOGIC:
            If on login/register → show HOME + SIGN UP only
          ============================================================ --}}
        @if(request()->routeIs('login') || request()->routeIs('register'))
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

        @else
        {{-- ============================================================
            NORMAL NAVIGATION FOR THE REST OF THE SITE
          ============================================================ --}}
            <nav class="hidden md:flex items-center gap-2 text-xs">

                {{-- HOME --}}
                <a href="{{ route('home') }}"
                   class="px-3 py-1 border tracking-tight rounded-none transition {{ $navRed('home') }}">
                    [*] HOME
                </a>

                {{-- CREATE EXPOSITION ALWAYS VISIBLE --}}
                <a href="{{ auth()->check() ? route('dashboard') : route('login') }}"
                   class="px-3 py-1 border tracking-tight rounded-none transition {{ $nav('dashboard') }}">
                    [+] CREATE_EXPOSITION
                </a>

                {{-- PROFILE (only logged in) --}}
                @auth
                <a href="{{ route('profile') }}"
                   class="px-3 py-1 border tracking-tight rounded-none transition {{ $nav('profile') }}">
                    [@] PROFILE
                </a>
                @endauth

            </nav>
        @endif


        {{-- ============================================================
            RIGHT SIDE: LOGIN / LOGOUT / GRAVATAR
          ============================================================ --}}
        <div class="flex items-center gap-3 text-xs">

            @auth
                {{-- USER PANEL --}}
                <div class="hidden md:flex flex-col text-right leading-tight">
                    <span class="text-white/70">{{ auth()->user()->name }}</span>
                    <span class="text-white/40">
                        {{ '@'.(auth()->user()->username ?? \Illuminate\Support\Str::slug(auth()->user()->name, '_')) }}
                    </span>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                    @csrf
                    <button type="submit"
                        class="px-4 py-1 text-xs text-white border border-white/30 bg-[#141414] hover:bg-[#1e1e1e] rounded-none">
                        LOGOUT
                    </button>
                </form>

            @else
                {{-- GUEST MODE --}}
                @if(!request()->routeIs('login') && !request()->routeIs('register'))
                    <a href="{{ route('login') }}"
                       class="px-4 py-1 text-xs text-white border border-white/30 bg-[#141414] hover:bg-[#1e1e1e] rounded-none">
                        LOGIN
                    </a>
                @endif
            @endauth

            <div class="h-10 w-10 border border-white/30 bg-[#111] text-white/40 text-[10px] flex items-center justify-center rounded-none">
                :|
            </div>

        </div>

    </div>
</header>



            <main class="flex-1 pt-28 pb-12">
                {{ $slot }}
            </main>

            <footer class="border-t border-zinc-800 py-5 text-[11px] text-zinc-400">
                <div class="max-w-6xl mx-auto px-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div class="border border-[#f97373]/70 bg-[#5b1010] text-[#ffecec] px-3 py-2 rounded-none max-w-xs">
                        <div class="flex items-center justify-between text-[10px]">
                            <span class="font-semibold text-[12px]">openxzbt</span>
                            <span>exposition_console</span>
                        </div>
                        <p class="mt-1">
                            this exposition record only stores metadata. the real rooms live in 3D.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-2 text-white/70">
                        <span class="px-3 py-1 border border-[#38bdf8]/70 bg-[#072635] rounded-none">endpoint: /expositions</span>
                        <span class="px-3 py-1 border border-[#22c55e]/70 bg-[#052713] rounded-none">mode: exposition_config</span>
                        <span class="px-3 py-1 border border-[#facc15]/70 bg-[#26220b] rounded-none">status: experimental</span>
                    </div>
                </div>
            </footer>
        </div>

        @stack('modals')
        @stack('scripts')
        @livewireScripts
    </body>
</html>
