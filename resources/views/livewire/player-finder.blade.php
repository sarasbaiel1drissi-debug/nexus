<div>
    {{-- Filters --}}
    <div class="card shadow-xss border-0 p-4 rounded-xxl mb-4">
        <h3 class="fw-700 font-xss mb-3 text-grey-900">Search Filters</h3>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="fw-600 font-xssss text-grey-600 mb-1 d-block">Game</label>
                <select wire:model.live="game_id" class="form-control rounded-xl border-light font-xssss">
                    <option value="">Any Game</option>
                    @foreach($availableGames as $g)
                        <option value="{{ $g->id }}">{{ $g->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="fw-600 font-xssss text-grey-600 mb-1 d-block">Rank</label>
                <input type="text" wire:model.live.debounce.300ms="rank" class="form-control rounded-xl border-light font-xssss" placeholder="e.g. Diamond">
            </div>

            <div class="col-md-4 mb-3">
                <label class="fw-600 font-xssss text-grey-600 mb-1 d-block">Role</label>
                <input type="text" wire:model.live.debounce.300ms="role" class="form-control rounded-xl border-light font-xssss" placeholder="e.g. Support">
            </div>

            <div class="col-md-4 mb-3">
                <label class="fw-600 font-xssss text-grey-600 mb-1 d-block">Language</label>
                <input type="text" wire:model.live.debounce.300ms="language" class="form-control rounded-xl border-light font-xssss" placeholder="e.g. English">
            </div>

            <div class="col-md-4 mb-3">
                <label class="fw-600 font-xssss text-grey-600 mb-1 d-block">Region</label>
                <input type="text" wire:model.live.debounce.300ms="region" class="form-control rounded-xl border-light font-xssss" placeholder="e.g. NA, EUW">
            </div>
        </div>
    </div>

    {{-- Results Grid --}}
    <div class="row">
        @forelse($users as $user)
            <div class="col-md-4 col-lg-3 mb-4 d-flex">
                <div class="card shadow-xss border-0 rounded-xxl overflow-hidden text-center d-flex flex-column w-100 h-100" style="min-height: 340px;">
                    <div class="p-4 flex-grow-1 d-flex flex-column align-items-center">
                        {{-- Profile Picture --}}
                        <a href="{{ route('user.profile', $user->username) }}">
                            <img src="{{ $user->profile_picture_url }}" 
                                 alt="{{ $user->username }}" 
                                 class="rounded-circle mb-3" 
                                 style="width:80px;height:80px;object-fit:cover;border:3px solid var(--theme-color);">
                        </a>

                        {{-- Online indicator --}}
                        @if($user->is_online)
                        <span class="badge bg-success font-xsssss rounded-pill mb-2">Online</span>
                        @endif

                        <h4 class="fw-700 font-xsss text-grey-900">{{ $user->name }}</h4>
                        <a href="{{ route('user.profile', $user->username) }}" class="fw-600 font-xssss text-current mb-1 d-block">&#64;{{ $user->username }}</a>
                        
                        <div class="text-warning fw-700 font-xsssss mb-2">
                            <i class="feather-arrow-up me-1"></i>{{ $user->uplift_count }} Uplifts
                        </div>

                        @if($user->region || $user->languages)
                            <p class="font-xssss text-grey-500 mb-2">
                                {{ $user->region }} {{ $user->region && $user->languages ? '•' : '' }} {{ $user->languages }}
                            </p>
                        @endif

                        {{-- Games: show first 2, then "+N more" --}}
                        @if($user->games->count())
                        <div class="d-flex flex-wrap justify-content-center gap-1 mb-2">
                            @foreach($user->games->take(2) as $userGame)
                                <span class="badge bg-greylight text-grey-600 fw-600 font-xssss p-2">{{ $userGame->name }}</span>
                            @endforeach
                            @if($user->games->count() > 2)
                                <span class="badge bg-current text-white fw-600 font-xssss p-2">+{{ $user->games->count() - 2 }} more</span>
                            @endif
                        </div>
                        @endif
                    </div>

                    <div class="border-top p-3 mt-auto">
                        <a href="{{ route('user.profile', $user->username) }}" class="btn bg-current text-white font-xssss fw-600 rounded-xl w-100 py-2">
                            View Profile
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card shadow-xss border-0 p-4 rounded-xxl text-center">
                    <img src="{{ asset('images/gaming/empty-state.png') }}" alt="No players found" class="mx-auto mb-3 rounded-xxl" style="max-width:160px;opacity:0.7;">
                    <p class="font-xssss text-grey-500 mb-0">No compatible players found based on your filters.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $users->links() }}
    </div>
</div>
