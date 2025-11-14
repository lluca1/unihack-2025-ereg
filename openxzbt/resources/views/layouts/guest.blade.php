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
                    <div class="flex items-center gap-3">
                        <span class="text-lg text-white tracking-tight font-semibold">openxzbt</span>
                        <span class="text-[11px] text-white/40">:: 3d_exposition_index</span>
                    </div>

                    <nav class="hidden md:flex items-center gap-2 text-xs">
                        <a href="{{ route('login') }}" class="px-3 py-1 border border-[#facc15]/80 bg-[#26220b] text-[#fef3c7] tracking-tight rounded-none">
                            LOGIN
                        </a>
                    </nav>

                    <div class="h-10 w-10 border border-white/30 bg-[#111] text-white/40 text-[10px] flex items-center justify-center rounded-none">
                        :|
                    </div>
                </div>
            </header>

            <main class="flex-1 pt-32 pb-16">
                <div class="w-full max-w-md mx-auto px-6">
                    <div class="border border-zinc-800 bg-black/40 shadow-xl px-6 py-8 rounded-none">
                        {{ $slot }}
                    </div>
                </div>
            </main>

            <footer class="border-t border-zinc-800 py-5 text-[11px] text-zinc-400">
                <div class="max-w-6xl mx-auto px-6 flex flex-wrap gap-2 items-center justify-between">
                    <span>log in to plan new 3D expositions.</span>
                    <span class="px-3 py-1 border border-[#22c55e]/70 bg-[#052713] rounded-none">status: guest</span>
                </div>
            </footer>
        </div>

        @stack('scripts')
        @livewireScripts
    </body>
</html>
