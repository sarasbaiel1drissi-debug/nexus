<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Game;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GameManager extends Component
{
    public $search = '';
    public $selectedGame = null;

    public $rank = '';
    public $preferred_role = '';

    public $newGameName = '';

    // Editing state
    public $editingGameId = null;
    public $editRank = '';
    public $editRole = '';

    public function updatedSearch()
    {
        $term = trim($this->search);

        $this->resetErrorBag(['search', 'newGameName']);
        $this->newGameName = $term;

        if (strlen($term) < 2) {
            $this->newGameName = '';
        }

        if ($this->selectedGame && $this->search !== ($this->selectedGame['name'] ?? '')) {
            $this->selectedGame = null;
        }
    }

    public function selectGame($gameId)
    {
        $game = Game::find($gameId);
        $this->selectedGame = $game ? $game->toArray() : null;
        $this->search = $game ? $game->name : '';
    }

    public function addNewGame()
    {
        $this->newGameName = trim($this->newGameName ?: $this->search);

        $this->validate([
            'newGameName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('games', 'name'),
            ],
        ]);

        $game = Game::create([
            'name' => $this->newGameName,
            'slug' => $this->uniqueSlug($this->newGameName),
        ]);

        $this->selectedGame = $game->toArray();
        $this->search = $game->name;

        $this->attachSelectedGame();
    }

    public function attachGame()
    {
        $this->validate([
            'rank' => ['nullable', 'string', 'max:100'],
            'preferred_role' => ['nullable', 'string', 'max:100'],
        ]);

        if (!$this->selectedGame) {
            $this->addError('search', 'Select or add a game first.');
            return;
        }

        $this->attachSelectedGame();
    }

    public function editGame($gameId)
    {
        $user = Auth::user();
        $game = $user->games->firstWhere('id', $gameId);
        if ($game) {
            $this->editingGameId = $gameId;
            $this->editRank = $game->pivot->rank ?? '';
            $this->editRole = $game->pivot->preferred_role ?? '';
        }
    }

    public function updateGame()
    {
        if (!$this->editingGameId) return;

        $this->validate([
            'editRank' => ['nullable', 'string', 'max:100'],
            'editRole' => ['nullable', 'string', 'max:100'],
        ]);

        Auth::user()->games()->updateExistingPivot($this->editingGameId, [
            'rank' => $this->editRank,
            'preferred_role' => $this->editRole,
        ]);

        $this->editingGameId = null;
        $this->editRank = '';
        $this->editRole = '';
    }

    public function cancelEdit()
    {
        $this->editingGameId = null;
        $this->editRank = '';
        $this->editRole = '';
    }

    public function detachGame($gameId)
    {
        Auth::user()->games()->detach($gameId);
    }

    private function attachSelectedGame(): void
    {
        if (!$this->selectedGame) {
            return;
        }

        $user = Auth::user();
        $gameId = $this->selectedGame['id'];

        if ($user->games()->whereKey($gameId)->exists()) {
            session()->flash('error', 'You already added this game.');
            return;
        }

        $user->games()->attach($gameId, [
            'rank' => $this->rank,
            'preferred_role' => $this->preferred_role,
        ]);

        $this->reset(['search', 'selectedGame', 'rank', 'preferred_role', 'newGameName']);
    }

    private function uniqueSlug(string $name): string
    {
        $baseSlug = Str::slug($name) ?: 'game';
        $slug = $baseSlug;
        $count = 2;

        while (Game::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function render()
    {
        $term = trim($this->search);
        $searchResults = strlen($term) > 1 && !$this->selectedGame
            ? Game::query()
                ->where('name', 'like', '%' . $term . '%')
                ->orderBy('name')
                ->get(['id', 'name'])
                ->toArray()
            : [];

        return view('livewire.game-manager', [
            'userGames' => Auth::user()->games()->orderBy('name')->get(),
            'searchResults' => $searchResults,
        ]);
    }
}
