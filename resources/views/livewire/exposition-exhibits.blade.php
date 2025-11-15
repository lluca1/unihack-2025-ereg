<section class="max-w-6xl mx-auto px-6 pb-20 space-y-10">
    {{-- HEADER / SUMMARY --}}
    <div class="border border-zinc-800 bg-[#050608] rounded-none p-6 flex flex-col gap-3">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-[11px] text-zinc-500">
                    {{ $exposition->is_public ? 'public exposition' : 'private' }}
                    ·
                    {{ $exposition->exhibits->count() }} exhibits
                </p>
                <h1 class="text-3xl font-semibold tracking-tight">{{ $exposition->title }}</h1>
                <p class="text-[12px] text-zinc-400 mt-1">
                    {{ $exposition->description ?: 'no description — add context to set the mood.' }}
                </p>
            </div>

            <a href="{{ route('expositions.index') }}"
               class="self-start md:self-auto px-3 py-1 border border-[#38bdf8]/80 bg-[#072635] text-[#bae6fd] text-xs tracking-tight rounded-none">
                &larr; back to all expositions
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 text-xs">
        {{-- LEFT: SETTINGS / UPLOAD / THUMBNAIL --}}
        <div class="border border-zinc-700 bg-[#050608] rounded-none p-4 space-y-4">
            @if ($isOwner)
                {{-- THUMBNAIL EDITOR --}}
                <div class="space-y-2">
                    <label class="block text-[11px] text-zinc-400" for="thumbnail">
                        exposition thumbnail
                    </label>

                    <input
                        id="thumbnail"
                        type="file"
                        accept="image/*"
                        wire:model="thumbnail"
                        class="w-full bg-[#050608] border border-dashed border-zinc-700 focus:border-zinc-300 outline-none px-3 py-2 rounded-none text-[12px] text-zinc-100"
                    >

                    <p class="text-[10px] text-zinc-500">
                        used on cards & listings. 4:3 aspect ratio recommended. max 4 MB.
                    </p>

                    @error('thumbnail')
                        <p class="text-[10px] text-[#f97373]">{{ $message }}</p>
                    @enderror

                    {{-- PREVIEW: NEW UPLOAD OR EXISTING COVER --}}
                    <div class="mt-2 border border-zinc-700 rounded-none p-2">
                        <p class="text-[10px] text-zinc-500 mb-2">thumbnail preview</p>

                        <div class="w-full bg-zinc-900 border border-dashed border-zinc-700 rounded-none flex items-center justify-center overflow-hidden"
                             style="aspect-ratio: 4 / 3;">
                            @if ($thumbnail)
                                {{-- livewire temporary upload preview --}}
                                <img
                                    src="{{ $thumbnail->temporaryUrl() }}"
                                    alt="thumbnail preview"
                                    class="w-full h-full object-cover"
                                >
                            @elseif ($exposition->cover_image_path)
                                {{-- existing saved cover --}}
                                <img
                                    src="{{ \Illuminate\Support\Facades\Storage::url($exposition->cover_image_path) }}"
                                    alt="{{ $exposition->title }} cover"
                                    class="w-full h-full object-cover"
                                >
                            @else
                                <span class="text-[10px] text-zinc-500">no thumbnail yet</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex gap-2 pt-1">
                        <button
                            type="button"
                            wire:click="saveThumbnail"
                            class="px-3 py-1 border border-[#f97373]/80 bg-[#5b1010] text-[#ffecec] rounded-none hover:bg-[#7f1717] text-[11px]"
                        >
                            :: UPDATE THUMBNAIL
                        </button>

                        @if ($exposition->cover_image_path)
                            <button
                                type="button"
                                wire:click="clearThumbnail"
                                class="px-3 py-1 border border-zinc-600 text-zinc-200 rounded-none hover:bg-zinc-800/60 text-[11px]"
                            >
                                :: REMOVE THUMBNAIL
                            </button>
                        @endif
                    </div>
                </div>

                {{-- THEME PRESET --}}
                @php($themeLabels = [-1=>'default',0=>'classic',1=>'medieval',2=>'scifi'])
                <div class="space-y-2 mt-6">
                    <h2 class="text-[12px] font-semibold tracking-tight text-zinc-100">exposition theme preset</h2>
                    <p class="text-[11px] text-zinc-500">
                        current:
                        <span class="text-zinc-300">
                            {{ $themeLabels[$exposition->preset_theme] ?? 'default' }}
                        </span>
                    </p>
                    <div class="flex flex-wrap gap-2 text-[11px]">
                        <button type="button"
                                wire:click="setPresetTheme(-1)"
                                class="px-3 py-1 border rounded-none {{ ($exposition->preset_theme ?? -1) === -1 ? 'border-zinc-400 bg-zinc-800/50 text-zinc-200' : 'border-white/20 text-white/50 hover:text-white' }}">
                            custom
                        </button>
                        <button type="button"
                                wire:click="setPresetTheme(0)"
                                class="px-3 py-1 border rounded-none {{ $exposition->preset_theme === 0 ? 'border-zinc-300 bg-zinc-800/50 text-zinc-200' : 'border-white/20 text-white/50 hover:text-white' }}">
                            classic
                        </button>
                        <button type="button"
                                wire:click="setPresetTheme(1)"
                                class="px-3 py-1 border rounded-none {{ $exposition->preset_theme === 1 ? 'border-zinc-300 bg-zinc-800/50 text-zinc-200' : 'border-white/20 text-white/50 hover:text-white' }}">
                            medieval
                        </button>
                        <button type="button"
                                wire:click="setPresetTheme(2)"
                                class="px-3 py-1 border rounded-none {{ $exposition->preset_theme === 2 ? 'border-zinc-300 bg-zinc-800/50 text-zinc-200' : 'border-white/20 text-white/50 hover:text-white' }}">
                            scifi
                        </button>
                    </div>
                </div>

                {{-- EXHIBIT UPLOAD FORM --}}
                <x-expositions.upload-form />
            @else
                {{-- READ ONLY --}}
                <h2 class="text-[12px] font-semibold tracking-tight text-zinc-100">read-only mode</h2>
                <p class="text-[11px] text-zinc-500">
                    you can browse these exhibits, but only
                    <span class="text-zinc-300">
                        {{ '@'.($exposition->user?->name ? \Illuminate\Support\Str::slug($exposition->user->name, '_') : 'its_curator') }}
                    </span>
                    can upload new assets here.
                </p>
            @endif
        </div>

        {{-- RIGHT: EXHIBITS LIST --}}
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

                        <h3 class="text-[14px] text-zinc-100 font-semibold tracking-tight">
                            {{ $exhibit->title }}
                        </h3>

                        <p class="text-[11px] text-zinc-400">
                            {{ $exhibit->description ?: 'no description — add one when needed.' }}
                        </p>

                        <p class="text-[10px] text-zinc-500">
                            stored under:
                            <span class="text-zinc-300">{{ $exhibit->media_path }}</span>
                        </p>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-2">
                            <span class="text-[10px] text-zinc-500">position: {{ $exhibit->position ?? 0 }}</span>

                            @if ($isOwner)
                                <button
                                    type="button"
                                    wire:click="delete({{ $exhibit->id }})"
                                    class="px-3 py-1 border border-[#f97373]/80 text-[#ffecec] text-[11px] rounded-none hover:bg-[#5b1010]/40"
                                >
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
