<section class="max-w-6xl mx-auto px-6 pb-20 space-y-10">
    <form wire:submit.prevent="save">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 text-xs">
            <div class="lg:col-span-2 space-y-6">
                <div class="border border-zinc-700 bg-[#050608] rounded-none p-4 space-y-4">
                    <div class="space-y-2">
                        <label class="block text-[11px] text-zinc-400" for="title">exposition title</label>
                        <input
                            id="title"
                            type="text"
                            placeholder="e.g. neon vault, echo forest"
                            wire:model.live.debounce.300ms="title"
                            class="w-full bg-[#050608] border border-zinc-700 focus:border-zinc-300 outline-none px-3 py-2 rounded-none text-[12px] text-zinc-100 placeholder:text-zinc-500"
                        >
                        @error('title')
                            <p class="text-[10px] text-[#f97373]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] text-zinc-400" for="thumbnail">thumbnail image</label>
                        <input
                            id="thumbnail"
                            type="file"
                            accept="image/*"
                            wire:model="thumbnail"
                            class="w-full bg-[#050608] border border-dashed border-zinc-700 focus:border-zinc-300 outline-none px-3 py-2 rounded-none text-[12px] text-zinc-100"
                        >
                        <p class="text-[10px] text-zinc-500">4:3 aspect ratio. max 4 MB.</p>
                        @error('thumbnail')
                            <p class="text-[10px] text-[#f97373]">{{ $message }}</p>
                        @enderror
                        @if ($thumbnail)
                            <div class="mt-2 border border-zinc-700 rounded-none p-2">
                                <p class="text-[10px] text-zinc-500 mb-2">live preview</p>
                                <div class="w-full bg-zinc-900 border border-dashed border-zinc-700 rounded-none flex items-center justify-center overflow-hidden" style="aspect-ratio: 4 / 3;">
                                    <img
                                        src="{{ $thumbnail->temporaryUrl() }}"
                                        alt="thumbnail preview"
                                        class="w-full h-full object-cover"
                                    >
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] text-zinc-400" for="description">short description</label>
                        <textarea
                            id="description"
                            rows="4"
                            placeholder="what kind of 3d space is this? mood, materials, pacing..."
                            wire:model.live.debounce.300ms="description"
                            class="w-full bg-[#050608] border border-zinc-700 focus:border-zinc-300 outline-none px-3 py-2 rounded-none text-[12px] text-zinc-100 placeholder:text-zinc-500 resize-none"
                        ></textarea>
                        @error('description')
                            <p class="text-[10px] text-[#f97373]">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="border border-zinc-700 bg-[#050608] rounded-none p-4 space-y-4">
                    <div class="space-y-2">
                        <label class="block text-[11px] text-zinc-400">visibility</label>
                        <div class="flex flex-wrap gap-2 text-[11px]">
                            <button type="button" wire:click="$set('is_public', true)" class="px-3 py-1 border rounded-none {{ $is_public ? 'border-[#22c55e]/60 bg-[#052713] text-[#bbf7d0]' : 'border-white/20 text-white/50 hover:text-white' }}">
                                public (featured)
                            </button>
                            <button type="button" wire:click="$set('is_public', false)" class="px-3 py-1 border rounded-none {{ ! $is_public ? 'border-[#f97373]/80 bg-[#5b1010] text-[#ffecec]' : 'border-white/20 text-white/50 hover:text-white' }}">
                                private (only you)
                            </button>
                        </div>
                        <p class="text-[10px] text-zinc-500">
                            private expositions stay hidden from recommendations until you toggle them public.
                        </p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] text-zinc-400">preset theme</label>
                        <div class="flex flex-wrap gap-2 text-[11px]">
                            <button type="button" wire:click="$set('preset_theme', -1)" class="px-3 py-1 border rounded-none {{ $preset_theme === -1 ? 'border-zinc-400 bg-zinc-800/50 text-zinc-200' : 'border-white/20 text-white/50 hover:text-white' }}">
                                custom (-1)
                            </button>
                            <button type="button" wire:click="$set('preset_theme', 0)" class="px-3 py-1 border rounded-none {{ $preset_theme === 0 ? 'border-zinc-300 bg-zinc-800/50 text-zinc-200' : 'border-white/20 text-white/50 hover:text-white' }}">
                                0 classic
                            </button>
                            <button type="button" wire:click="$set('preset_theme', 1)" class="px-3 py-1 border rounded-none {{ $preset_theme === 1 ? 'border-zinc-300 bg-zinc-800/50 text-zinc-200' : 'border-white/20 text-white/50 hover:text-white' }}">
                                1 medieval
                            </button>
                            <button type="button" wire:click="$set('preset_theme', 2)" class="px-3 py-1 border rounded-none {{ $preset_theme === 2 ? 'border-zinc-300 bg-zinc-800/50 text-zinc-200' : 'border-white/20 text-white/50 hover:text-white' }}">
                                2 scifi
                            </button>
                        </div>
                        @error('preset_theme')
                            <p class="text-[10px] text-[#f97373]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] text-zinc-400">exposition actions</label>
                        <div class="flex flex-col md:flex-row gap-2">
                            <button type="submit" class="px-4 py-2 border border-[#f97373]/80 bg-[#5b1010] text-[#ffecec] rounded-none hover:bg-[#7f1717]">
                                :: SAVE EXPOSITION
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="space-y-4">
                <div class="border border-zinc-700 bg-[#050608] rounded-none p-4 space-y-3">
                    <h2 class="text-[12px] font-semibold tracking-tight text-zinc-100">exposition summary</h2>
                    <p class="text-[11px] text-zinc-400">
                        snapshot of what you are about to save. private entries stay visible only to you.
                    </p>
                    <div class="mt-2 border border-zinc-700 rounded-none p-3 text-[10px] text-zinc-400 space-y-1">
                        <p>title: <span class="text-zinc-300">{{ $title !== '' ? $title : 'pending…' }}</span></p>
                        <p>status: <span class="text-zinc-300">{{ $is_public ? 'public' : 'private' }}</span></p>
                        <p>description: <span class="text-zinc-300">{{ $description !== '' ? \Illuminate\Support\Str::limit($description, 60) : 'add a short description' }}</span></p>
                        @php($themeLabels = [-1=>'default',0=>'classic',1=>'medieval',2=>'scifi'])
                        <p>preset theme: <span class="text-zinc-300">{{ $themeLabels[$preset_theme] ?? 'default' }} ({{ $preset_theme }})</span></p>
                        <p>thumbnail: <span class="text-zinc-300">{{ $thumbnail ? 'ready to upload' : 'none yet' }}</span></p>
                        <p>expositions total: <span class="text-zinc-300">{{ $expositions->count() }}</span></p>
                    </div>
                </div>

                <div class="border border-zinc-700 bg-[#050608] rounded-none p-4 space-y-3">
                    <h2 class="text-[12px] font-semibold tracking-tight text-zinc-100">notes</h2>
                    <p class="text-[11px] text-zinc-400">
                        the 3d client points here using your exposition id. deleting an exposition removes its 3d references.
                    </p>
                </div>
            </aside>
        </div>
    </form>

    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold tracking-tight text-zinc-100">ACTIVE EXPOSITIONS</h2>
            <span class="text-[11px] text-zinc-500">{{ $expositions->count() }} total</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 text-xs">
            @forelse ($expositions as $exposition)
                <article class="border border-zinc-700 hover:border-zinc-300 transition bg-[#050608] rounded-none p-4 flex flex-col gap-3" wire:key="exposition-{{ $exposition->id }}">
                    @php($isOwner = auth()->id() === $exposition->user_id)
                    <div class="w-full bg-zinc-900 border border-dashed border-zinc-700 rounded-none flex items-center justify-center text-[10px] text-zinc-500 overflow-hidden" style="aspect-ratio: 4 / 3;">
                        @if ($exposition->cover_image_path)
                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::url($exposition->cover_image_path) }}"
                                alt="{{ $exposition->title }} cover"
                                class="w-full h-full object-cover"
                            >
                        @else
                            preview_placeholder
                        @endif
                    </div>
                    <span class="text-zinc-200">{{ '['.str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) . ']' }} {{ strtoupper($exposition->title) }}</span>
                    <p class="text-[11px] text-zinc-400 line-clamp-3">
                        {{ $exposition->description ?: 'no description yet — add a short note.' }}
                    </p>
                    <div class="flex flex-col gap-1 text-[10px] text-zinc-500">
                        <span>curator: <span class="text-zinc-300">{{ '@'.($exposition->user?->name ? \Illuminate\Support\Str::slug($exposition->user->name, '_') : 'anonymous') }}</span></span>
                        <span>exhibits: {{ $exposition->exhibits_count }}</span>
                        <span>status: {{ $exposition->is_public ? 'public' : 'private' }}</span>
                    </div>
                    <div class="flex flex-col gap-2 mt-2">
                        @if ($isOwner)
                            <a href="{{ route('expositions.show', $exposition) }}" class="border border-zinc-600 hover:border-zinc-300 px-3 py-1 text-left rounded-none">
                                :: MANAGE EXHIBITS
                            </a>
                            <button type="button" wire:click="delete({{ $exposition->id }})" class="border border-[#f97373]/80 text-[#ffecec] px-3 py-1 rounded-none hover:bg-[#5b1010]/50">
                                :: DELETE EXPOSITION
                            </button>
                        @else
                            <p class="text-[11px] text-zinc-500 border border-dashed border-zinc-700 rounded-none px-3 py-2">
                                view only
                            </p>
                        @endif
                    </div>
                </article>
            @empty
                <div class="col-span-full border border-dashed border-zinc-700 rounded-none p-6 text-center text-[12px] text-zinc-400">
                    no expositions yet. start by creating one above.
                </div>
            @endforelse
        </div>
    </div>
</section>
