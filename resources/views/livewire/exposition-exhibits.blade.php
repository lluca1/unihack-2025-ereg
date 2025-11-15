<section class="max-w-6xl mx-auto px-6 pb-20 space-y-10">
    <div class="border border-zinc-800 bg-[#050608] rounded-none p-6 flex flex-col gap-3">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-[11px] text-zinc-500">{{ $exposition->is_public ? 'public exposition' : 'private' }} · {{ $exposition->exhibits->count() }} exhibits</p>
                <h1 class="text-3xl font-semibold tracking-tight">{{ $exposition->title }}</h1>
                <p class="text-[12px] text-zinc-400 mt-1">{{ $exposition->description ?: 'no description — add context to set the mood.' }}</p>
            </div>
            <a href="{{ route('expositions.index') }}" class="self-start md:self-auto px-3 py-1 border border-[#38bdf8]/80 bg-[#072635] text-[#bae6fd] text-xs tracking-tight rounded-none">
                &larr; back to all expositions
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 text-xs">
        <div class="border border-zinc-700 bg-[#050608] rounded-none p-4 space-y-4">
            @if ($isOwner)
                @php($themeLabels = [-1=>'default',0=>'classic',1=>'medieval',2=>'scifi'])
                <div class="space-y-2">
                    <h2 class="text-[12px] font-semibold tracking-tight text-zinc-100">exposition theme preset</h2>
                    <p class="text-[11px] text-zinc-500">current: <span class="text-zinc-300">{{ $themeLabels[$exposition->preset_theme] ?? 'default' }} ({{ $exposition->preset_theme ?? -1 }})</span></p>
                    <div class="flex flex-wrap gap-2 text-[11px]">
                        <button type="button" wire:click="setPresetTheme(-1)" class="px-3 py-1 border rounded-none {{ ($exposition->preset_theme ?? -1) === -1 ? 'border-zinc-400 bg-zinc-800/50 text-zinc-200' : 'border-white/20 text-white/50 hover:text-white' }}">
                            default (-1)
                        </button>
                        <button type="button" wire:click="setPresetTheme(0)" class="px-3 py-1 border rounded-none {{ $exposition->preset_theme === 0 ? 'border-zinc-300 bg-zinc-800/50 text-zinc-200' : 'border-white/20 text-white/50 hover:text-white' }}">
                            0 classic
                        </button>
                        <button type="button" wire:click="setPresetTheme(1)" class="px-3 py-1 border rounded-none {{ $exposition->preset_theme === 1 ? 'border-zinc-300 bg-zinc-800/50 text-zinc-200' : 'border-white/20 text-white/50 hover:text-white' }}">
                            1 medieval
                        </button>
                        <button type="button" wire:click="setPresetTheme(2)" class="px-3 py-1 border rounded-none {{ $exposition->preset_theme === 2 ? 'border-zinc-300 bg-zinc-800/50 text-zinc-200' : 'border-white/20 text-white/50 hover:text-white' }}">
                            2 scifi
                        </button>
                    </div>
                </div>

                <x-expositions.upload-form />
            @else
                <h2 class="text-[12px] font-semibold tracking-tight text-zinc-100">read-only mode</h2>
                <p class="text-[11px] text-zinc-500">
                    you can browse these exhibits, but only <span class="text-zinc-300">{{ '@'.($exposition->user?->name ? \Illuminate\Support\Str::slug($exposition->user->name, '_') : 'its_curator') }}</span>
                    can upload new assets here.
                </p>
            @endif
        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-[12px] font-semibold tracking-tight text-zinc-100">exhibits in this exposition</h2>
                <span class="text-[10px] text-zinc-500">{{ count($exhibits) }} total</span>
            </div>

            <div class="space-y-4">
                @forelse ($exhibits as $exhibit)
                    <article class="border border-zinc-700 bg-[#050608] rounded-none p-4 space-y-2" wire:key="exhibit-{{ $exhibit->id }}">
                        <div class="flex items-center justify-between text-[10px] text-zinc-500">
                            <span>uploaded {{ $exhibit->created_at->diffForHumans() }}</span>
                            <span>bundle: OBJ + MTL</span>
                        </div>
                        <h3 class="text-[14px] text-zinc-100 font-semibold tracking-tight">{{ $exhibit->title }}</h3>
                        <p class="text-[11px] text-zinc-400">{{ $exhibit->description ?: 'no description — add one when needed.' }}</p>
                        <p class="text-[10px] text-zinc-500">stored under: <span class="text-zinc-300">{{ $exhibit->media_path }}</span></p>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-2">
                            <span class="text-[10px] text-zinc-500">position: {{ $exhibit->position ?? 0 }}</span>
                            @if ($isOwner)
                                <button type="button" wire:click="delete({{ $exhibit->id }})" class="px-3 py-1 border border-[#f97373]/80 text-[#ffecec] text-[11px] rounded-none hover:bg-[#5b1010]/40">
                                    :: DELETE EXHIBIT
                                </button>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="border border-dashed border-zinc-700 p-6 text-center text-[12px] text-zinc-400 rounded-none">
                        no exhibits yet. upload your first asset on the left.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>
