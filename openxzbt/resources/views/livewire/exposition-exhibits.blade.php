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
            <h2 class="text-[12px] font-semibold tracking-tight text-zinc-100">upload new exhibit</h2>
            <p class="text-[11px] text-zinc-500">drag a 3d model (glb, fbx, obj). we keep the file path so the 3d client can pull it later.</p>

            <form wire:submit.prevent="save" enctype="multipart/form-data" class="space-y-4">
                <div class="space-y-2">
                    <label for="exhibit-title" class="block text-[11px] text-zinc-400">exhibit title</label>
                    <input
                        id="exhibit-title"
                        type="text"
                        wire:model.defer="title"
                        placeholder="e.g. glitch totem"
                        class="w-full bg-[#050608] border border-zinc-700 focus:border-zinc-300 outline-none px-3 py-2 rounded-none text-[12px] text-zinc-100 placeholder:text-zinc-500"
                    >
                    @error('title')
                        <p class="text-[10px] text-[#f97373]">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="exhibit-description" class="block text-[11px] text-zinc-400">description</label>
                    <textarea
                        id="exhibit-description"
                        rows="4"
                        wire:model.defer="description"
                        placeholder="materials, lighting cues, sound triggers..."
                        class="w-full bg-[#050608] border border-zinc-700 focus:border-zinc-300 outline-none px-3 py-2 rounded-none text-[12px] text-zinc-100 placeholder:text-zinc-500"
                    ></textarea>
                    @error('description')
                        <p class="text-[10px] text-[#f97373]">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="model-file" class="block text-[11px] text-zinc-400">3d model (glb, fbx, obj)</label>
                    <input
                        id="model-file"
                        type="file"
                        wire:model="modelFile"
                        accept=".glb,.fbx,.obj"
                        class="w-full text-[11px] file:mr-3 file:px-3 file:py-1 file:border-0 file:bg-[#072635] file:text-[#bae6fd] file:rounded-none border border-dashed border-zinc-700 bg-[#050608] text-zinc-400"
                    >
                    @error('modelFile')
                        <p class="text-[10px] text-[#f97373]">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="modelFile" class="text-[10px] text-[#38bdf8]">uploading...</div>
                </div>

                <button type="submit" class="w-full px-4 py-2 border border-[#f97373]/80 bg-[#5b1010] text-[#ffecec] rounded-none hover:bg-[#7f1717]">
                    :: UPLOAD EXHIBIT
                </button>
            </form>
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
                            <span>media: {{ strtoupper(pathinfo($exhibit->media_path, PATHINFO_EXTENSION)) }}</span>
                        </div>
                        <h3 class="text-[14px] text-zinc-100 font-semibold tracking-tight">{{ $exhibit->title }}</h3>
                        <p class="text-[11px] text-zinc-400">{{ $exhibit->description ?: 'no description — add one when needed.' }}</p>
                        <p class="text-[10px] text-zinc-500">stored at: <span class="text-zinc-300">{{ $exhibit->media_path }}</span></p>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-2">
                            <span class="text-[10px] text-zinc-500">position: {{ $exhibit->position ?? 0 }}</span>
                            <button type="button" wire:click="delete({{ $exhibit->id }})" class="px-3 py-1 border border-[#f97373]/80 text-[#ffecec] text-[11px] rounded-none hover:bg-[#5b1010]/40">
                                :: DELETE EXHIBIT
                            </button>
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
