<?php

namespace App\Livewire;

use App\Models\Exposition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class ExpositionExhibits extends Component
{
    use WithFileUploads;

    public Exposition $exposition;

    public $exhibits = [];

    public bool $isOwner = false;

    #[Rule('required|string|max:255')]
    public string $title = '';

    #[Rule('nullable|string')]
    public string $description = '';

    #[Rule('required|file|mimes:obj|max:51200')]
    public $modelFile;

    public function mount(Exposition $exposition): void
    {
        $this->exposition = $exposition;
        $this->isOwner = Auth::id() === $this->exposition->user_id;

        if (! $this->isOwner && ! $this->exposition->is_public) {
            abort(403);
        }

        $this->loadExhibits();
    }

    public function render()
    {
        return view('livewire.exposition-exhibits');
    }

    public function save(): void
    {
        $userId = $this->ensureExpositionOwner();
        $this->validate();

        $position = ($this->exposition->exhibits()->max('position') ?? -1) + 1;

        $path = $this->modelFile->store('models', 'public');

        $this->exposition->exhibits()->create([
            'user_id' => $userId,
            'title' => $this->title,
            'description' => $this->description ?: null,
            'media_type' => '3d-model',
            'media_path' => $path,
            'mime_type' => $this->modelFile->getMimeType(),
            'position' => $position,
        ]);

        $this->reset(['title', 'description', 'modelFile']);

        $this->loadExhibits();
        $this->exposition->refresh();
    }

    public function delete(int $exhibitId): void
    {
        $userId = $this->ensureExpositionOwner();
        $exhibit = $this->exposition->exhibits()->whereKey($exhibitId)->first();

        if (! $exhibit) {
            return;
        }

        if ($exhibit->user_id !== $userId) {
            abort(403);
        }

        if ($exhibit->media_path) {
            Storage::disk('public')->delete($exhibit->media_path);
        }

        $exhibit->delete();

        $this->loadExhibits();
        $this->exposition->refresh();
    }

    private function loadExhibits(): void
    {
        $this->exhibits = $this->exposition->exhibits()->get();
    }

    private function ensureExpositionOwner(): int
    {
        $userId = Auth::id();

        if (! $userId || $this->exposition->user_id !== $userId) {
            abort(403);
        }

        return $userId;
    }
}
