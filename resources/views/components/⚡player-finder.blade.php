<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Game;

new class extends Component
{
    use WithPagination;

    public $game_id = '';
    public $rank = '';
    public $role = '';
    public $language = '';
    public $region = '';

    public function with(): array
    {
        $query = User::query()->where('id', '!=', auth()->id());

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

        return [
            'users' => $query->with('games')->paginate(12),
            'availableGames' => Game::orderBy('name')->get()
        ];
    }
};
?>

<div class="space-y-6">
    <!-- Filters -->
    <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-900 dark:text-gray-100">
        <h3 class="font-semibold text-lg mb-4">Search Filters</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <x-input-label for="game_id" value="Game" />
                <select wire:model.live="game_id" id="game_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 shadow-sm rounded-md">
                    <option value="">Any Game</option>
                    @foreach($availableGames as $g)
                        <option value="{{ $g->id }}">{{ $g->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <x-input-label for="rank" value="Rank" />
                <x-text-input wire:model.live.debounce.300ms="rank" id="rank" class="mt-1 block w-full" placeholder="e.g. Diamond" />
            </div>

            <div>
                <x-input-label for="role" value="Role" />
                <x-text-input wire:model.live.debounce.300ms="role" id="role" class="mt-1 block w-full" placeholder="e.g. Support" />
            </div>

            <div>
                <x-input-label for="language" value="Language" />
                <x-text-input wire:model.live.debounce.300ms="language" id="language" class="mt-1 block w-full" placeholder="e.g. English" />
            </div>

            <div>
                <x-input-label for="region" value="Region" />
                <x-text-input wire:model.live.debounce.300ms="region" id="region" class="mt-1 block w-full" placeholder="e.g. NA, EUW" />
            </div>
        </div>
    </div>

    <!-- Results Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($users as $user)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden flex flex-col">
                <div class="p-6 flex flex-col items-center flex-grow text-gray-900 dark:text-gray-100">
                    <img src="{{ $user->profile_picture_url }}" alt="{{ $user->username }}" class="w-24 h-24 rounded-full mb-4 object-cover border-4 border-indigo-500">
                    <h3 class="text-xl font-bold">{{ $user->name }}</h3>
                    <a href="{{ route('user.profile', $user->username) }}" class="text-indigo-500 dark:text-indigo-400 hover:underline mb-2">@<span>{{ $user->username }}</span></a>
                    
                    @if($user->region || $user->languages)
                        <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-3">
                            {{ $user->region }} {{ $user->region && $user->languages ? '•' : '' }} {{ $user->languages }}
                        </p>
                    @endif

                    <div class="w-full flex flex-wrap justify-center gap-1 mb-4">
                        @foreach($user->games->take(3) as $userGame)
                            <span class="px-2 py-1 bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200 text-xs rounded">{{ $userGame->name }}</span>
                        @endforeach
                    </div>
                </div>
                
                <div class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 p-4">
                    <a href="{{ route('user.profile', $user->username) }}" class="w-full inline-flex justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition">
                        View Profile
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-center text-gray-500 dark:text-gray-400">
                No compatible players found based on your filters.
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>