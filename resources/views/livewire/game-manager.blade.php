<div>
    <h3 class="fw-700 font-xss mb-4 text-grey-900">Your Games</h3>

    <div class="row mb-4">
        @forelse($userGames as $game)
            <div class="col-md-6 mb-3" wire:key="user-game-{{ $game->id }}">
                <div class="card shadow-xss border-0 p-3 rounded-xxl">
                    @if($editingGameId === $game->id)
                        {{-- EDITING MODE --}}
                        <h4 class="fw-700 font-xssss text-grey-900 mb-2">{{ $game->name }}</h4>
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="fw-600 font-xsssss text-grey-500 d-block">Rank</label>
                                <input type="text" wire:model="editRank" class="form-control form-control-sm rounded-xl border-light font-xssss">
                            </div>
                            <div class="col-6">
                                <label class="fw-600 font-xsssss text-grey-500 d-block">Role</label>
                                <input type="text" wire:model="editRole" class="form-control form-control-sm rounded-xl border-light font-xssss">
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" wire:click="updateGame" class="btn btn-sm bg-success text-white rounded-xl font-xssss fw-600 px-3">Save</button>
                            <button type="button" wire:click="cancelEdit" class="btn btn-sm bg-greylight text-grey-700 rounded-xl font-xssss fw-600 px-3">Cancel</button>
                        </div>
                    @else
                        {{-- VIEW MODE --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="fw-700 font-xssss text-grey-900 mb-1">{{ $game->name }}</h4>
                                <p class="font-xssss text-grey-500 mb-0">Rank: {{ $game->pivot->rank ?? 'N/A' }} | Role: {{ $game->pivot->preferred_role ?? 'N/A' }}</p>
                            </div>
                            <div class="d-flex gap-1">
                                <button type="button" wire:click="editGame({{ $game->id }})" class="btn btn-sm bg-greylight text-grey-700 rounded-xl font-xssss fw-600 px-2" title="Edit">
                                    <i class="feather-edit-2"></i>
                                </button>
                                <button type="button" wire:click="detachGame({{ $game->id }})" wire:confirm="Remove this game from your profile?" class="btn btn-sm bg-danger text-white rounded-xl font-xssss fw-600 px-2" title="Remove">
                                    <i class="feather-x"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="font-xssss text-grey-500 mb-0">No games added yet.</p>
            </div>
        @endforelse
    </div>

    <hr class="mb-4"/>

    <h3 class="fw-700 font-xss mb-4 text-grey-900">Add a Game</h3>

    @if(session()->has('error'))
        <div class="alert alert-danger font-xssss">{{ session('error') }}</div>
    @endif

    <div class="mb-3">
        <label class="fw-600 font-xssss text-grey-600 mb-1 d-block">Search Game</label>
        <input type="text" wire:model.live.debounce.300ms="search" class="form-control rounded-xl border-light font-xssss" placeholder="Type a game name...">

        @if(count($searchResults) > 0)
            <ul class="w-100 shadow-xss rounded-xxl mt-2 list-unstyled bg-white overflow-hidden border border-light">
                @foreach($searchResults as $game)
                    <li wire:click="selectGame({{ $game['id'] }})" class="p-2 font-xssss text-grey-900" style="cursor:pointer;" onmouseover="this.style.backgroundColor='var(--dark-bg-hover, #f5f5f5)'" onmouseout="this.style.backgroundColor=''">{{ $game['name'] }}</li>
                @endforeach
            </ul>
        @endif

        @if(strlen(trim($search)) > 1 && count($searchResults) === 0 && !$selectedGame)
            <div class="mt-2 font-xssss text-grey-500">
                Game not found.
                <button type="button" wire:click="addNewGame" wire:loading.attr="disabled" wire:target="addNewGame" class="fw-700 text-current border-0 bg-transparent" style="cursor:pointer;">
                    Add "{{ trim($search) }}" to my profile
                </button>
            </div>
        @endif
        @error('search') <small class="text-danger font-xsssss d-block mt-1">{{ $message }}</small> @enderror
        @error('newGameName') <small class="text-danger font-xsssss d-block mt-1">{{ $message }}</small> @enderror
    </div>

    @if($selectedGame)
        <div class="p-3 rounded-xxl mb-3 bg-greylight">
            <span class="font-xssss text-grey-900">Selected: <span class="fw-700">{{ $selectedGame['name'] }}</span></span>
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-6">
            <label class="fw-600 font-xssss text-grey-600 mb-1 d-block">Rank / Skill Level</label>
            <input type="text" wire:model="rank" class="form-control rounded-xl border-light font-xssss" placeholder="e.g. Diamond, Casual">
        </div>
        <div class="col-6">
            <label class="fw-600 font-xssss text-grey-600 mb-1 d-block">Preferred Role</label>
            <input type="text" wire:model="preferred_role" class="form-control rounded-xl border-light font-xssss" placeholder="e.g. Support, Duelist">
        </div>
    </div>

    @error('rank') <small class="text-danger font-xsssss d-block mb-1">{{ $message }}</small> @enderror
    @error('preferred_role') <small class="text-danger font-xsssss d-block mb-1">{{ $message }}</small> @enderror

    <button type="button" wire:click="attachGame" class="btn bg-current text-white font-xssss fw-600 rounded-xl px-4 py-2">
        Add to My Profile
    </button>
</div>
