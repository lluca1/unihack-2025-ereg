{{-- resources/views/home.blade.php --}}
@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>

    {{-- PAGE HEADER --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex flex-col">
                <h2 class="text-xl font-semibold tracking-tight text-white">
                    openxzbt :: home
                </h2>
                <p class="text-xs text-white/40 mt-1">
                    browse public expositions or continue working on your own.
                </p>
            </div>

            @auth
                <a href="{{ route('expositions.index') }}"
                    class="px-4 py-1 border border-[#facc15]/60 bg-[#26220b] text-[#fef3c7] text-xs tracking-tight">
                    [+] CREATE_EXPOSITION
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="px-4 py-1 border border-white/30 bg-[#141414] text-white text-xs hover:bg-[#1e1e1e]">
                    LOGIN TO CREATE
                </a>
            @endauth
        </div>
    </x-slot>


    {{-- MAIN PAGE WRAPPER --}}
    <div class="max-w-7xl mx-auto px-6 py-10 space-y-16">

        {{-- HERO / TOP PANEL --}}
        <section class="border border-white/10 bg-[#0a0a0a] p-6 md:p-8">
            <h1 class="text-3xl md:text-4xl font-semibold tracking-tight text-white mb-2">
                welcome to <span class="text-red-400">openxzbt</span>
            </h1>

            @guest
                <p class="text-sm text-white/60 max-w-xl">
                    you are exploring as <span class="text-red-400 font-semibold">guest</span>.
                    thumbnails & short previews only. log in to create & manage expositions.
                </p>
            @else
                <p class="text-sm text-white/60 max-w-xl">
                    logged in as <span class="text-red-400">{{ auth()->user()->name }}</span>.
                    create, edit and preview your 3d museum spaces.
                </p>
            @endguest

            @php
    $tips = [
        'check your scale.',
        'normals are wrong. they always are.',
        'lighting fixes more than modeling does.',
        'don’t trust viewport shading.',
        'your scene has too many lights.',
        'reduce your textures. you won’t notice.',
        'test it in low light before calling it done.',
        'glass is never as simple as you think.',
        'your camera is probably in the wrong place.',
        'bake it. then bake it again.',
        'overdetailing is not detail.',
        'your shadows are too soft.',
        'stop smoothing everything.',
        'check the silhouette, not the surface.',
        'remove half your polygons. it will look the same.',
        'reflections lie.',
        'check the backfaces. someone will see them.',
        'texture seams always find an audience.',
        'realism starts with roughness.',
        'your color grading is doing the heavy lifting.',
    ];

    $tip = $tips[array_rand($tips)];
@endphp

<div class="mt-4 inline-flex items-center gap-2 px-3 py-1 border border-white/20 bg-[#111] text-[10px] text-white/50">
    tip: {{ $tip }}
</div>



        </section>

        {{-- SCAN FOR EXPOS@ (LIVEWIRE SEARCH) --}}
        <livewire:expo-scan />

        {{-- YOUR EXHIBITIONS (LOGGED IN ONLY) --}}
        @auth
            @php
                $yourExpositions = isset($expositions)
                    ? $expositions->where('user_id', auth()->id())
                    : collect();
            @endphp

            @if($yourExpositions->isNotEmpty())
                <section class="space-y-4">

                    <h3 class="text-lg font-semibold tracking-tight text-white">
                        your expositions
                    </h3>

                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($yourExpositions as $expo)
                            <article class="border border-white/15 bg-[#090909] p-0">

                                {{-- IMAGE --}}
                                @if ($expo->cover_image_path)
                                    <img src="{{ Storage::url($expo->cover_image_path) }}"
                                         class="w-full h-40 object-cover border-b border-white/10">
                                @else
                                    <div class="w-full h-40 flex items-center justify-center text-white/20 border-b border-white/10">
                                        no image
                                    </div>
                                @endif

                                {{-- BODY --}}
                                <div class="p-4 space-y-3">

                                    <h4 class="text-white text-sm font-medium tracking-tight truncate">
                                        {{ $expo->title }}
                                    </h4>

                                    <p class="text-xs text-white/40 leading-relaxed line-clamp-3">
                                        {{ $expo->description ? Str::limit($expo->description, 140) : 'no description provided.' }}
                                    </p>

                                    <div class="flex items-center justify-between text-[10px] text-white/40">
                                        <span>id: <span class="text-white">{{ $expo->id }}</span></span>
                                        <span>{{ $expo->created_at->diffForHumans() }}</span>
                                    </div>

                                    <a href="{{ route('expositions.show', $expo) }}"
                                       class="block text-xs px-3 py-1 border border-[#38bdf8]/40 text-[#bae6fd] bg-[#072635] mt-2">
                                        manage_exposition →
                                    </a>
                                </div>

                            </article>
                        @endforeach
                    </div>

                </section>
            @endif
        @endauth


        {{-- PUBLIC EXPOSITIONS SECTION --}}
        <section class="space-y-4">

            <h3 class="text-lg font-semibold tracking-tight text-white">
                featured public expositions
            </h3>

            <p class="text-xs text-white/40">
                curated selection of latest public builds.
            </p>

            @if(isset($expositions) && $expositions->isNotEmpty())
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($expositions as $expo)
                        <article class="border border-white/15 bg-[#090909] p-0">

                            {{-- IMAGE --}}
                            @if ($expo->cover_image_path)
                                <img src="{{ Storage::url($expo->cover_image_path) }}"
                                     class="w-full h-40 object-cover border-b border-white/10">
                            @else
                                <div class="w-full h-40 flex items-center justify-center text-white/20 border-b border-white/10">
                                    no image
                                </div>
                            @endif

                            {{-- BODY --}}
                            <div class="p-4 space-y-3">

                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm text-white font-medium tracking-tight truncate">
                                        {{ $expo->title }}
                                    </h4>

                                    @if($expo->user)
                                        <span class="text-[10px] text-white/40 truncate">
                                            by {{ $expo->user->name }}
                                        </span>
                                    @endif
                                </div>

                                <p class="text-xs text-white/40 leading-relaxed line-clamp-3">
                                    {{ $expo->description ? Str::limit($expo->description, 140) : 'no summary available.' }}
                                </p>

                                <div class="flex items-center justify-between text-[10px] text-white/40">
                                    <span>id: <span class="text-white">{{ $expo->id }}</span></span>
                                    <span>{{ $expo->created_at->format('d M Y') }}</span>
                                </div>

                                @guest
                                    <a href="{{ route('login') }}"
                                       class="block text-xs px-3 py-1 border border-white/30 bg-[#141414] text-white mt-2">
                                        login_to_view →
                                    </a>
                                @else
                                    <a href="{{ route('expositions.show', $expo) }}"
                                       class="block text-xs px-3 py-1 border border-[#f97373]/70 bg-[#5b1010] text-[#ffecec] mt-2">
                                       view_details →
                                    </a>
                                @endguest

                            </div>

                        </article>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-white/40">
                    no public expositions yet.
                </p>
            @endif

        </section>

    </div>

</x-app-layout>
