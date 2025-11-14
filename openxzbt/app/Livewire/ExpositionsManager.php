<?php

namespace App\Livewire;

use App\Models\Exposition;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;

class ExpositionsManager extends Component
{
    /**
     * Displayable list of expositions.
     */
    public $expositions = [];

    #[Rule('required|string|max:255')]
    public string $title = '';

    #[Rule('nullable|string')]
    public string $description = '';

    #[Rule('boolean')]
    public bool $is_public = true;

    public function mount(): void
    {
        $this->loadExpositions();
    }

    public function render()
    {
        return view('livewire.expositions-manager');
    }

    public function save(): void
    {
        $this->validate();

        $userId = Auth::id();

        if (! $userId) {
            $this->addError('title', 'You must be logged in to create an exposition.');
            return;
        }

        Exposition::create([
            'user_id' => $userId,
            'title' => $this->title,
            'description' => $this->description ?: null,
            'is_public' => $this->is_public,
        ]);

        $this->reset(['title', 'description', 'is_public']);
        $this->is_public = true;

        $this->loadExpositions();
    }

    public function delete(int $expositionId): void
    {
        Exposition::whereKey($expositionId)->delete();

        $this->loadExpositions();
    }

    private function loadExpositions(): void
    {
        $this->expositions = Exposition::query()
            ->latest()
            ->withCount('exhibits')
            ->get();
    }
}
