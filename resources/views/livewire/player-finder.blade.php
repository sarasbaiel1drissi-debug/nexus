<div>
    <style>
        .player-card { min-height: 390px; }
        .card .player-card-body {
            display: grid !important;
            grid-template-rows: 136px 62px 46px 76px;
            justify-items: center;
            align-items: start;
            gap: 8px;
        }
        .player-photo-status {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 136px;
            width: 100%;
        }
        .player-avatar {
            width: 84px;
            height: 84px;
            object-fit: cover;
            border: 3px solid var(--theme-color);
            background: #f4f6fb;
        }
        .player-identity,
        .player-meta,
        .player-games { width: 100%; }
        .player-name,
        .player-region {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .player-games {
            min-height: 64px;
            align-content: flex-start;
        }
        .player-actions {
            min-height: 72px;
            display: flex;
            align-items: center;
        }
    </style>

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
            <div class="col-sm-6 col-xl-3 mb-4 d-flex">
                <div class="card shadow-xss border-0 rounded-xxl overflow-hidden text-center d-flex flex-column w-100 h-100 player-card">
                    <div class="p-4 flex-grow-1 player-card-body">
                        <div class="player-photo-status">
                            <a href="{{ route('user.profile', $user->username) }}" class="d-inline-flex align-items-center justify-content-center">
                                <img src="{{ $user->profile_picture_url }}"
                                     alt="{{ $user->username }}"
                                     class="rounded-circle player-avatar">
                            </a>
                            @if($user->is_online)
                                <span class="badge bg-success font-xsssss rounded-pill">Online</span>
                            @else
                                <span class="badge bg-greylight text-grey-500 font-xsssss rounded-pill">Offline</span>
                            @endif
                        </div>

                        <div class="player-identity">
                            <h4 class="fw-700 font-xsss text-grey-900 mb-1 player-name">{{ $user->name }}</h4>
                            <a href="{{ route('user.profile', $user->username) }}" class="fw-600 font-xssss text-current d-block text-truncate">&#64;{{ $user->username }}</a>
                        </div>

                        <div class="player-meta">
                            <div class="text-warning fw-700 font-xsssss mb-1">
                                <i class="feather-arrow-up me-1"></i>{{ $user->uplift_count }} Uplifts
                            </div>

                            <p class="font-xssss text-grey-500 mb-0 player-region">
                                @if($user->region || $user->languages)
                                    {{ $user->region }}{{ $user->region && $user->languages ? ' - ' : '' }}{{ $user->languages }}
                                @else
                                    Profile details pending
                                @endif
                            </p>
                        </div>

                        <div class="d-flex flex-wrap justify-content-center gap-1 player-games">
                            @if($user->games->count())
                                @foreach($user->games->take(2) as $userGame)
                                    <span class="badge bg-greylight text-grey-600 fw-600 font-xssss p-2">{{ $userGame->name }}</span>
                                @endforeach
                                @if($user->games->count() > 2)
                                    <span class="badge bg-current text-white fw-600 font-xssss p-2">+{{ $user->games->count() - 2 }} more</span>
                                @endif
                            @else
                                <span class="badge bg-greylight text-grey-500 fw-600 font-xssss p-2">No games listed</span>
                            @endif
                        </div>
                    </div>

                    <div class="border-top p-3 mt-auto player-actions">
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
