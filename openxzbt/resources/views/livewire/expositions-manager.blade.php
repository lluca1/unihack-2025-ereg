<div>
    <h1>Expositions</h1>

    <form wire:submit.prevent="save">
        <div>
            <label for="title">Title</label>
            <input id="title" type="text" wire:model.defer="title">
            @error('title')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="description">Description</label>
            <textarea id="description" wire:model.defer="description"></textarea>
            @error('description')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label>
                <input type="checkbox" wire:model="is_public">
                Public
            </label>
        </div>

        <button type="submit">Create Exposition</button>
    </form>

    <hr>

    <div>
        @forelse ($expositions as $exposition)
            <div>
                <h2>{{ $exposition->title }}</h2>
                <p>{{ $exposition->description }}</p>
                <p>Status: {{ $exposition->is_public ? 'Public' : 'Private' }}</p>
                <p>Exhibits: {{ $exposition->exhibits_count }}</p>
                <div>
                    <a href="{{ route('expositions.show', $exposition) }}">Manage Exhibits</a>
                    <button type="button" wire:click="delete({{ $exposition->id }})">Delete</button>
                </div>
            </div>
            <hr>
        @empty
            <p>No expositions yet.</p>
        @endforelse
    </div>
</div>
