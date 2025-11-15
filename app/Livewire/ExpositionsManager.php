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
        $userId = Auth::id();

        if (! $userId) {
            abort(403);
        }

        $exposition = Exposition::whereKey($expositionId)->first();

        if (! $exposition) {
            return;
        }

        if ($exposition->user_id !== $userId) {
            abort(403);
        }

        $exposition->delete();

        $this->loadExpositions();
    }

    private function loadExpositions(): void
    {
        $userId = Auth::id();

        $this->expositions = Exposition::query()
            ->when($userId, function ($query) use ($userId) {
                $query->where(function ($query) use ($userId) {
                    $query->where('is_public', true)
                        ->orWhere('user_id', $userId);
                });
            }, function ($query) {
                $query->where('is_public', true);
            })
            ->latest()
            ->with(['user:id,name'])
            ->withCount('exhibits')
            ->get();
    }
}
