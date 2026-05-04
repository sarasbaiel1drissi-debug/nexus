<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Game;

class PlayerFinder extends Component
{
    use WithPagination;

    public $game_id = '';
    public $rank = '';
    public $role = '';
    public $language = '';
    public $region = '';

    // Reset pagination when filters change
    public function updatingGameId() { $this->resetPage(); }
    public function updatingRank() { $this->resetPage(); }
    public function updatingRole() { $this->resetPage(); }
    public function updatingLanguage() { $this->resetPage(); }
    public function updatingRegion() { $this->resetPage(); }

    public function render()
    {
        $query = User::query()->where('id', '!=', auth()->id())->withCount('upliftsReceived')->orderByDesc('uplifts_received_count');

        if ($this->game_id) {
            $query->whereHas('games', function ($q) {
                $q->where('games.id', $this->game_id);
                if ($this->rank) {
                    $q->where('game_user.rank', 'like', '%' . $this->rank . '%');
                }
                if ($this->role) {
                    $q->where('game_user.preferred_role', 'like', '%' . $this->role . '%');
                }
            });
        }

        if ($this->language) {
            $query->where('languages', 'like', '%' . $this->language . '%');
        }

        if ($this->region) {
            $query->where('region', 'like', '%' . $this->region . '%');
        }

        return view('livewire.player-finder', [
            'users' => $query->with('games')->paginate(12),
            'availableGames' => Game::orderBy('name')->get(),
        ]);
    }
}
