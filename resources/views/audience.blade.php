@extends('include.master')
@section('page', 'Connections')
@section('content')

<div class="main-content right-chat-active">
    <div class="middle-sidebar-bottom">
        <div class="middle-sidebar-left">
            <div class="row">
                <div class="col-xl-10 col-xxl-9 mx-auto">
                    <div class="card shadow-xss w-100 d-block border-0 p-4 mb-3">
                        <h2 class="fw-700 mb-0 font-md text-grey-900">
                            <i class="feather-users me-2 text-current"></i> Connections
                        </h2>
                    </div>

                    {{-- Tab Navigation --}}
                    <ul class="nav nav-tabs border-0 mb-3" id="connectionsTab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active fw-600 font-xssss rounded-xxl px-3 py-2 me-2 bg-greylight text-grey-900 border-0" id="mutual-tab" data-bs-toggle="tab" data-bs-target="#mutual" type="button">
                                <i class="feather-heart me-1"></i> Mutual Friends <span class="badge bg-current ms-1">{{ $mutuals->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-600 font-xssss rounded-xxl px-3 py-2 me-2 bg-greylight text-grey-900 border-0" id="following-tab" data-bs-toggle="tab" data-bs-target="#following" type="button">
                                <i class="feather-user-plus me-1"></i> Following <span class="badge bg-greylight text-grey-700 ms-1">{{ $following->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-600 font-xssss rounded-xxl px-3 py-2 bg-greylight text-grey-900 border-0" id="followers-tab" data-bs-toggle="tab" data-bs-target="#followers" type="button">
                                <i class="feather-user-check me-1"></i> Followers <span class="badge bg-greylight text-grey-700 ms-1">{{ $followers->count() }}</span>
                            </button>
                        </li>
                    </ul>

                    {{-- Tab Content --}}
                    <div class="tab-content">
                        {{-- MUTUAL --}}
                        <div class="tab-pane fade show active" id="mutual" role="tabpanel">
                            <div class="row align-items-stretch">
                                @forelse($mutuals as $user)
                                    @include('partials.connection-card', ['user' => $user, 'label' => 'Friend'])
                                @empty
                                    <div class="col-12"><div class="card shadow-xss border-0 p-4 rounded-xxl text-center"><p class="font-xssss text-grey-500 mb-0">No mutual friends yet. Follow people who follow you!</p></div></div>
                                @endforelse
                            </div>
                        </div>

                        {{-- FOLLOWING --}}
                        <div class="tab-pane fade" id="following" role="tabpanel">
                            <div class="row align-items-stretch">
                                @forelse($following as $user)
                                    @include('partials.connection-card', ['user' => $user, 'label' => 'Following'])
                                @empty
                                    <div class="col-12"><div class="card shadow-xss border-0 p-4 rounded-xxl text-center"><p class="font-xssss text-grey-500 mb-0">You're not following anyone yet.</p></div></div>
                                @endforelse
                            </div>
                        </div>

                        {{-- FOLLOWERS --}}
                        <div class="tab-pane fade" id="followers" role="tabpanel">
                            <div class="row align-items-stretch">
                                @forelse($followers as $user)
                                    @include('partials.connection-card', ['user' => $user, 'label' => 'Follower'])
                                @empty
                                    <div class="col-12"><div class="card shadow-xss border-0 p-4 rounded-xxl text-center"><p class="font-xssss text-grey-500 mb-0">No one is following you yet. Stay active!</p></div></div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
