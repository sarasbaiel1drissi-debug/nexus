<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Game;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GameManager extends Component
{
    public $search = '';
    public $searchResults = [];
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
        if (strlen($this->search) > 1) {
            $this->searchResults = Game::where('name', 'like', '%' . $this->search . '%')->take(5)->get()->toArray();
            $this->newGameName = $this->search;
        } else {
            $this->searchResults = [];
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
        $this->searchResults = [];
    }

    public function addNewGame()
    {
        $this->validate([
            'newGameName' => 'required|string|max:255|unique:games,name',
        ]);

        $game = Game::create([
            'name' => $this->newGameName,
            'slug' => Str::slug($this->newGameName),
        ]);

        $this->selectedGame = $game->toArray();
        $this->search = $game->name;
        $this->searchResults = [];
    }

    public function attachGame()
    {
        if (!$this->selectedGame) return;

        $user = Auth::user();
        $gameId = $this->selectedGame['id'];

        if (!$user->games->contains($gameId)) {
            $user->games()->attach($gameId, [
                'rank' => $this->rank,
                'preferred_role' => $this->preferred_role,
            ]);

            $this->reset(['search', 'selectedGame', 'rank', 'preferred_role', 'searchResults']);
        } else {
            session()->flash('error', 'You already added this game.');
        }
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

    public function render()
    {
        return view('livewire.game-manager', [
            'userGames' => Auth::user()->games,
        ]);
    }
}
