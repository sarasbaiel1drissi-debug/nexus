<?php

use Livewire\Volt\Component;
use App\Models\Game;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

new class extends Component
{
    public $search = '';
    public $games = [];
    public $selectedGame = null;
    
    public $rank = '';
    public $preferred_role = '';
    
    public $newGameName = '';

    public function updatedSearch()
    {
        if (strlen($this->search) > 1) {
            $this->games = Game::where('name', 'like', '%' . $this->search . '%')->take(5)->get();
            $this->newGameName = $this->search;
        } else {
            $this->games = [];
        }
        if ($this->selectedGame && $this->search !== $this->selectedGame->name) {
            $this->selectedGame = null;
        }
    }

    public function selectGame($gameId)
    {
        $this->selectedGame = Game::find($gameId);
        $this->search = $this->selectedGame->name;
        $this->games = [];
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
        
        $this->selectedGame = $game;
        $this->search = $game->name;
        $this->games = [];
    }
    
    public function attachGame()
    {
        if (!$this->selectedGame) return;
        
        $user = Auth::user();
        
        // check if already attached
        if (!$user->games->contains($this->selectedGame->id)) {
            $user->games()->attach($this->selectedGame->id, [
                'rank' => $this->rank,
                'preferred_role' => $this->preferred_role,
            ]);
            
            $this->reset(['search', 'selectedGame', 'rank', 'preferred_role']);
            $this->dispatch('game-attached');
        } else {
            session()->flash('error', 'You already added this game.');
        }
    }
    
    public function detachGame($gameId)
    {
        Auth::user()->games()->detach($gameId);
    }
};
?>

<div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-900 dark:text-gray-100">
    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Your Games</h3>
    
    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach(auth()->user()->games as $game)
            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded flex justify-between items-center">
                <div>
                    <h4 class="font-bold">{{ $game->name }}</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Rank: {{ $game->pivot->rank ?? 'N/A' }} | Role: {{ $game->pivot->preferred_role ?? 'N/A' }}</p>
                </div>
                <button wire:click="detachGame({{ $game->id }})" class="text-red-500 hover:text-red-400">Delete</button>
            </div>
        @endforeach
    </div>

    <hr class="border-gray-200 dark:border-gray-700 mb-6"/>

    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Add a Game</h3>
    
    @if(session()->has('error'))
        <div class="text-red-500 mb-4">{{ session('error') }}</div>
    @endif

    <div class="space-y-4">
        <!-- Search -->
        <div class="relative">
            <x-input-label for="search" value="Search Game" />
            <x-text-input id="search" type="text" wire:model.live.debounce.300ms="search" class="mt-1 block w-full" placeholder="Type a game name..." />
            
            @if(!empty($games))
                <ul class="absolute z-10 w-full bg-white dark:bg-gray-700 mt-1 rounded-md shadow-lg border border-gray-200 dark:border-gray-600">
                    @foreach($games as $game)
                        <li wire:click="selectGame({{ $game->id }})" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer">{{ $game->name }}</li>
                    @endforeach
                </ul>
            @endif
            
            @if(strlen($search) > 1 && empty($games) && !$selectedGame)
                <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Game not found. <button wire:click="addNewGame" class="text-indigo-600 dark:text-indigo-400 hover:underline">Add "{{ $search }}" to database</button>
                </div>
            @endif
        </div>

        @if($selectedGame)
            <div class="bg-indigo-50 dark:bg-indigo-900/50 p-3 rounded text-sm text-indigo-800 dark:text-indigo-200 flex justify-between">
                <span>Selected: <span class="font-bold">{{ $selectedGame->name }}</span></span>
                <button wire:click="$set('selectedGame', null); $set('search', '')" class="text-red-500 hover:text-red-400">Clear</button>
            </div>
        @endif

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="rank" value="Rank / Skill Level" />
                <x-text-input id="rank" type="text" wire:model="rank" class="mt-1 block w-full" placeholder="e.g. Diamond, Casual" />
            </div>
            <div>
                <x-input-label for="preferred_role" value="Preferred Role" />
                <x-text-input id="preferred_role" type="text" wire:model="preferred_role" class="mt-1 block w-full" placeholder="e.g. Support, Duelist" />
            </div>
        </div>

        <x-primary-button wire:click="attachGame">
            Add to My Profile
        </x-primary-button>
    </div>
</div>