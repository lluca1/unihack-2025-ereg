@php
    // safety defaults in case the component is rendered without data
    $query   = $query   ?? '';
    $results = $results ?? [];
@endphp

<section class="w-full">
    <div class="max-w-sm space-y-3">

        {{-- LABEL --}}
        <p class="text-[11px] font-semibold tracking-tight text-zinc-100">
            :: scan for expos
        </p>

        {{-- INPUT BLOCK --}}
        <div class="border border-zinc-700 bg-[#050608] rounded-none px-3 py-3 space-y-2">
            <div class="flex items-center gap-2">
                <span class="px-2 py-1 text-[10px] border border-[#38bdf8]/70 bg-[#072635] text-[#bae6fd] rounded-none">
                    [?] scan
                </span>

                <input
                    type="text"
                    wire:model.live.debounce.300ms="query"
                    placeholder="title, #id or user..."
                    class="flex-1 bg-transparent outline-none border-0 focus:ring-0 text-[12px] text-zinc-100 placeholder:text-zinc-600 py-2"
                >
            </div>

            <div class="flex items-center justify-between text-[10px] text-zinc-500">
                <span>hint: start typing (min 2 chars · public only)</span>

                @if($query !== '')
                    <button
                        type="button"
                        wire:click="resetSearch"
                        class="px-2 py-0.5 border border-zinc-700 hover:border-zinc-400 hover:bg-zinc-900 rounded-none text-zinc-300"
                    >
                        clear
                    </button>
                @endif
            </div>
        </div>

        {{-- RESULTS LIST --}}
        @if($query !== '')
            @if(count($results))
                <div class="border border-zinc-700 bg-[#050608] rounded-none text-xs">
                    @foreach($results as $expo)
                        <a
                            href="{{ route('expositions.show', $expo) }}"
                            class="block px-3 py-2 hover:bg-zinc-900/90 transition"
                        >
                            <div class="flex items-center justify-between gap-2">
                                <span class="truncate text-zinc-100 text-[12px]">
                                    {{ $expo->title }}
                                </span>
                                <span class="text-[10px] text-zinc-500">#{{ $expo->id }}</span>
                            </div>

                            <div class="mt-0.5 flex items-center justify-between text-[10px] text-zinc-500">
                                <span class="truncate">
                                    {{ '@'.($expo->user?->name ? \Illuminate\Support\Str::slug($expo->user->name, '_') : 'anonymous') }}
                                </span>
                                <span>{{ $expo->exhibits()->count() }} objs</span>
                            </div>
                        </a>

                        @unless($loop->last)
                            <div class="border-t border-zinc-800"></div>
                        @endunless
                    @endforeach
                </div>
            @else
                <p class="text-[11px] text-zinc-500">
                    no match.
                </p>
            @endif
        @endif

    </div>
</section>
