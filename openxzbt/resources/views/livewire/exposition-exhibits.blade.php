<div>
    <h1>{{ $exposition->title }} Exhibits</h1>
    <p>{{ $exposition->description }}</p>
    <a href="{{ route('expositions.index') }}">&larr; Back to Expositions</a>

    <hr>

    <form wire:submit.prevent="save" enctype="multipart/form-data">
        <div>
            <label for="exhibit-title">Title</label>
            <input id="exhibit-title" type="text" wire:model.defer="title">
            @error('title')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="exhibit-description">Description</label>
            <textarea id="exhibit-description" wire:model.defer="description"></textarea>
            @error('description')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="model-file">3D Model (glb, fbx, obj)</label>
            <input id="model-file" type="file" wire:model="modelFile" accept=".glb,.fbx,.obj">
            @error('modelFile')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">Upload Exhibit</button>
    </form>

    <hr>

    <div>
        @forelse ($exhibits as $exhibit)
            <div>
                <h2>{{ $exhibit->title }}</h2>
                <p>{{ $exhibit->description }}</p>
                <p>Stored at: {{ $exhibit->media_path }}</p>
                <p>Uploaded: {{ $exhibit->created_at->toDateTimeString() }}</p>
                <button type="button" wire:click="delete({{ $exhibit->id }})">Delete</button>
            </div>
            <hr>
        @empty
            <p>No exhibits yet.</p>
        @endforelse
    </div>
</div>
