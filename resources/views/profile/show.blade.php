@extends('include.master')
@section('page','profile')
@section('content')

<style>
    .profile-cover-frame {
        height: 250px;
        background: #111827;
    }

    .profile-cover-frame img,
    .profile-avatar-img {
        object-fit: cover;
        user-select: none;
        -webkit-user-drag: none;
        transition: object-position .18s ease, filter .18s ease;
        will-change: object-position;
    }

    .profile-cover-frame.is-editable img,
    .profile-avatar-img.is-editable {
        cursor: grab;
        touch-action: none;
    }

    .profile-cover-frame.is-moving img,
    .profile-avatar-img.is-moving {
        cursor: grabbing;
        filter: saturate(1.05);
        transition: none;
    }

    .profile-avatar-img {
        width: 100px;
        height: 100px;
        border: 4px solid #fff;
        background: #f4f6fb;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .12);
    }

    .profile-header-main {
        min-height: 88px;
        padding-top: 10px;
        padding-bottom: 14px;
    }

    .profile-name-block {
        padding-left: 18px;
    }

    .profile-card-section {
        min-height: 120px;
    }

    .post-privacy-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        border-radius: 999px;
        background: #f3f4f8;
        color: #6b7280;
        font-size: 10px;
        font-weight: 700;
    }

    @media (max-width: 767px) {
        .profile-cover-frame { height: 190px; }
        .profile-header-main {
            margin-left: 0 !important;
            padding-left: 150px !important;
            align-items: flex-start !important;
        }
    }
</style>

<div class="main-content right-chat-active">
    <div class="middle-sidebar-bottom">
        <div class="middle-sidebar-left">
            <div class="row">

                {{-- ================= PROFILE HEADER ================= --}}
                <div class="col-lg-12">
                    <div class="card w-100 border-0 p-0 bg-white shadow-xss rounded-xxl">

                        {{-- COVER --}}
                        <div class="card-body p-0 rounded-xxl overflow-hidden m-3 position-relative profile-cover-frame {{ auth()->id() === $user->id ? 'is-editable' : '' }}" id="coverContainer">
                            <img src="{{ $user->cover_picture ? asset('storage/'.$user->cover_picture) : asset('images/u-bg.jpg') }}"
                                 class="w-100 h-100"
                                 id="coverImage"
                                 style="object-position:{{ $user->cover_position ?? '50% 50%' }};">
                            
                            @if(auth()->id() === $user->id)
                            <div class="position-absolute top-0 end-0 m-3 d-flex gap-2 z-index-1">
                                <button type="button" class="btn btn-sm btn-dark fw-600 rounded-xxl px-3 d-none" id="saveCoverBtn">Save Position</button>
                                <label class="btn btn-sm bg-white fw-600 rounded-xxl px-3 cursor-pointer shadow-sm text-dark mb-0 d-flex align-items-center">
                                    <i class="feather-camera me-1"></i> Edit Cover
                                    <form id="coverForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="d-none">
                                        @csrf @method('PATCH')
                                        <input type="file" name="cover_picture" id="coverInput" accept="image/*">
                                    </form>
                                </label>
                            </div>
                            <form id="coverPositionForm" action="{{ route('profile.update') }}" method="POST" class="d-none">
                                @csrf @method('PATCH')
                                <input type="hidden" name="cover_position" id="coverPositionInput" value="{{ $user->cover_position ?? '50% 50%' }}">
                            </form>
                            @endif
                        </div>

                        {{-- AVATAR + NAME + ONLINE --}}
                        <div class="card-body p-0 position-relative">
                            <figure class="avatar position-absolute z-index-1" style="top:-40px; left:30px; width:100px; height:100px;">
                                <img src="{{ $user->profile_picture_url }}"
                                     id="avatarImage"
                                     class="rounded-circle profile-avatar-img {{ auth()->id() === $user->id ? 'is-editable' : '' }}"
                                     style="object-position: {{ $user->profile_position ?? '50% 50%' }};">
                                @if($user->is_online)
                                <span class="position-absolute rounded-circle bg-success" style="width:14px;height:14px;bottom:5px;right:5px;border:2px solid #fff;"></span>
                                @endif
                                
                                @if(auth()->id() === $user->id)
                                <label class="position-absolute cursor-pointer d-flex align-items-center justify-content-center bg-dark text-white rounded-circle shadow-sm" style="width:30px;height:30px;bottom:0;left:0;opacity:0.8;transition:all 0.2s;z-index:2;">
                                    <i class="feather-camera font-xssss"></i>
                                    <form id="avatarForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="d-none">
                                        @csrf @method('PATCH')
                                        <input type="file" name="profile_picture" id="avatarInput" accept="image/*">
                                    </form>
                                </label>
                                
                                <form id="profilePositionForm" action="{{ route('profile.update') }}" method="POST" class="d-none">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="profile_position" id="profilePositionInput" value="{{ $user->profile_position ?? '50% 50%' }}">
                                </form>
                                <button type="button" id="saveProfileBtn" class="btn bg-current text-white btn-sm position-absolute d-none" style="bottom:-40px; left:50%; transform:translateX(-50%); white-space:nowrap; z-index:10; font-size:10px; padding:2px 8px; border-radius:20px;">Save Position</button>
                                @endif
                            </figure>

                            <div class="d-flex align-items-center justify-content-between ps-5 pe-4 profile-header-main" style="margin-left:105px;">
                                <div class="mt-2 mb-3 profile-name-block">
                                    <h4 class="fw-700 font-sm mb-0">
                                        {{ $user->name }}
                                        @if($user->is_online)
                                        <span class="badge bg-success font-xsssss ms-2 rounded-pill">Online</span>
                                        @endif
                                    </h4>
                                    <span class="fw-500 font-xssss text-grey-500 d-block">{{ '@'.$user->username }}</span>
                                    <div class="d-flex gap-3 mt-1 font-xssss text-grey-500">
                                        <span><strong class="text-grey-900">{{ $user->followers->count() }}</strong> Followers</span>
                                        <span><strong class="text-grey-900">{{ $user->following->count() }}</strong> Following</span>
                                        <span><i class="feather-arrow-up text-warning me-1"></i><strong class="text-grey-900">{{ $user->uplift_count }}</strong> Uplifts</span>
                                    </div>
                                </div>

                                {{-- Action buttons for OTHER users --}}
                                @if(auth()->check() && auth()->id() !== $user->id)
                                <div class="d-flex gap-2 align-items-center">
                                    {{-- Follow --}}
                                    <form action="{{ route('follow', $user) }}" method="POST">
                                        @csrf
                                        <button class="btn {{ auth()->user()->isFollowing($user->id) ? 'bg-greylight text-grey-700' : 'bg-current text-white' }} font-xssss fw-600 rounded-xxl px-4 py-2">
                                            {{ auth()->user()->isFollowing($user->id) ? 'Unfollow' : 'Follow' }}
                                        </button>
                                    </form>
                                    {{-- Uplift --}}
                                    <form action="{{ route('uplift', $user) }}" method="POST">
                                        @csrf
                                        <button class="btn {{ auth()->user()->hasUplifted($user->id) ? 'bg-warning text-white' : 'bg-greylight text-grey-700' }} font-xssss fw-600 rounded-xxl px-3 py-2" title="Uplift this player">
                                            <i class="feather-arrow-up me-1"></i>{{ $user->uplift_count }}
                                        </button>
                                    </form>
                                    {{-- Message --}}
                                    <a href="/messages/{{ $user->id }}" class="btn bg-current text-white font-xssss fw-600 rounded-xxl px-4 py-2">
                                        Message
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- TABS --}}
                        <div class="card-body p-0 border-top-xs d-flex align-items-center">
                            <ul class="nav nav-tabs h55 ps-4 border-bottom-0">
                                <li class="list-inline-item me-5">
                                    <a class="fw-700 font-xssss text-grey-500 active">Posts</a>
                                </li>
                                <li class="list-inline-item me-5">
                                    <a class="fw-700 font-xssss text-grey-500">Media</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>

                {{-- ================= LEFT SIDEBAR ================= --}}
                <div class="col-xl-4 col-lg-4 mt-3">
                    <div class="card shadow-xss rounded-xxl border-0 p-4">
                        <h4 class="fw-700 font-xsss mb-2">About</h4>
                        <p class="font-xssss text-grey-500">
                            {{ $user->bio ?? 'No bio yet.' }}
                        </p>
                    </div>

                    {{-- XP & Badges --}}
                    <div class="card shadow-xss rounded-xxl border-0 p-4 mt-3">
                        <h4 class="fw-700 font-xsss mb-3"><i class="feather-zap text-warning me-1"></i> XP & Badges</h4>
                        
                        {{-- XP Bar --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fw-700 font-xss text-warning">{{ number_format($user->xp) }} XP</span>
                            @php
                                $tiers = [100 => 'Rookie', 500 => 'Rising Star', 1000 => 'Veteran', 5000 => 'Legend'];
                                $nextTier = null;
                                $nextTierXp = null;
                                foreach ($tiers as $xpThreshold => $tierName) {
                                    if ($user->xp < $xpThreshold) {
                                        $nextTier = $tierName;
                                        $nextTierXp = $xpThreshold;
                                        break;
                                    }
                                }
                            @endphp
                            @if($nextTier)
                                <span class="font-xsssss text-grey-500">Next: {{ $nextTier }} ({{ $nextTierXp }} XP)</span>
                            @else
                                <span class="font-xsssss text-warning fw-600">MAX LEVEL</span>
                            @endif
                        </div>
                        @if($nextTierXp)
                        <div class="bg-greylight rounded-pill overflow-hidden mb-3" style="height:6px;">
                            <div class="rounded-pill h-100" style="width:{{ ($user->xp / $nextTierXp) * 100 }}%;background:linear-gradient(90deg,#f5a623,#f7c948);transition:width 0.4s ease;"></div>
                        </div>
                        @endif

                        {{-- Badges --}}
                        @if($user->badges->count())
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($user->badges as $badge)
                            <div class="d-flex align-items-center bg-greylight rounded-pill px-3 py-2" title="{{ $badge->description }}">
                                <i class="{{ $badge->icon ?? 'feather-award' }} me-1 text-current" style="font-size:14px;"></i>
                                <span class="fw-600 font-xsssss text-grey-700">{{ $badge->name }}</span>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="font-xssss text-grey-400 mb-0">No badges earned yet.</p>
                        @endif
                    </div>

                    <div class="card shadow-xss rounded-xxl border-0 p-4 mt-3">
                        <h4 class="fw-700 font-xsss mb-3">Gamer Profile</h4>
                        
                        @if($user->region || $user->languages)
                        <div class="mb-3">
                            <h5 class="fw-700 font-xssss text-grey-600 mb-1">Region & Languages</h5>
                            <p class="font-xssss text-grey-500 mb-0">{{ $user->region ?? 'Not set' }} - {{ $user->languages ?? 'Not set' }}</p>
                        </div>
                        @endif

                        @if($user->availability)
                        <div class="mb-3">
                            <h5 class="fw-700 font-xssss text-grey-600 mb-1">Availability</h5>
                            <p class="font-xssss text-grey-500 mb-0">{{ $user->availability }}</p>
                        </div>
                        @endif

                        @if($user->playstyle_tags)
                        <div class="mb-3">
                            <h5 class="fw-700 font-xssss text-grey-600 mb-1">Playstyle</h5>
                            <div class="d-flex flex-wrap mb-0">
                            @foreach(is_array($user->playstyle_tags) ? $user->playstyle_tags : json_decode($user->playstyle_tags) ?? [] as $tag)
                                <span class="badge bg-greylight text-grey-600 fw-600 me-2 mb-2 p-2">{{ $tag }}</span>
                            @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="card shadow-xss rounded-xxl border-0 p-4 mt-3">
                        <h4 class="fw-700 font-xsss mb-3">Games Played</h4>
                        @forelse($user->games as $game)
                        <div class="d-flex align-items-center mb-3">
                            <div>
                                <h5 class="fw-700 font-xssss text-grey-900 mb-1">{{ $game->name }}</h5>
                                <p class="font-xssss text-grey-500 mb-0">Rank: {{ $game->pivot->rank ?? 'N/A' }} | Role: {{ $game->pivot->preferred_role ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="font-xssss text-grey-500">No games added yet.</p>
                        @endforelse
                    </div>

                    {{-- FOLLOWING SECTION --}}
                    @if($user->following->count())
                    <div class="card shadow-xss rounded-xxl border-0 p-4 mt-3">
                        <h4 class="fw-700 font-xsss mb-3">Following</h4>
                        @foreach($user->following->take(8) as $f)
                        <a href="{{ route('user.profile', $f->following->username) }}" class="d-flex align-items-center mb-3 text-decoration-none">
                            <img src="{{ $f->following->profile_picture_url }}"
                                 class="rounded-circle me-3" 
                                 style="width:35px;height:35px;object-fit:cover;">
                            <div>
                                <h5 class="fw-700 font-xssss text-grey-900 mb-0">{{ $f->following->name }}</h5>
                                <span class="font-xssss text-grey-500">{{ '@'.$f->following->username }}</span>
                            </div>
                            @if($f->following->is_online)
                            <span class="ms-auto rounded-circle bg-success" style="width:8px;height:8px;"></span>
                            @endif
                        </a>
                        @endforeach
                    </div>
                    @endif

                    {{-- FOLLOWERS SECTION --}}
                    @if($user->followers->count())
                    <div class="card shadow-xss rounded-xxl border-0 p-4 mt-3">
                        <h4 class="fw-700 font-xsss mb-3">Followers</h4>
                        @foreach($user->followers->take(8) as $f)
                        <a href="{{ route('user.profile', $f->follower->username) }}" class="d-flex align-items-center mb-3 text-decoration-none">
                            <img src="{{ $f->follower->profile_picture_url }}"
                                 class="rounded-circle me-3"
                                 style="width:35px;height:35px;object-fit:cover;">
                            <div>
                                <h5 class="fw-700 font-xssss text-grey-900 mb-0">{{ $f->follower->name }}</h5>
                                <span class="font-xssss text-grey-500">{{ '@'.$f->follower->username }}</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- ================= MAIN FEED ================= --}}
                <div class="col-xl-8 col-lg-8 mt-3">

                    {{-- ============ CREATE POST ============ --}}
                    @if(auth()->id() === $user->id)
                    <div class="card w-100 shadow-xss rounded-xxl border-0 p-4 mb-3">
                        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="postForm">
                            @csrf

                            <textarea name="content"
                                      class="w-100 border-0 rounded-xxl p-3 font-xssss text-grey-700 bg-greylight"
                                      rows="3"
                                      placeholder="Share an update">{{ old('content') }}</textarea>

                            <div class="mt-3" id="mediaPreview"></div>

                            <div class="d-flex align-items-center mt-3 pt-2 border-top">
                                <div class="me-auto">
                                    <label class="btn btn-sm bg-greylight rounded-xxl px-3 py-2 font-xssss fw-600 text-grey-700 cursor-pointer mb-0">
                                        <i class="feather-image text-success me-1"></i> Add Media
                                        <input type="file" name="media[]" id="mediaInput" multiple
                                               accept="image/*,video/*" class="d-none">
                                    </label>
                                    <button type="button" onclick="clearAllMedia()" 
                                            class="btn btn-sm btn-outline-danger font-xssss ms-2 d-none" 
                                            id="clearAllBtn">Clear All</button>
                                    <span class="font-xssss text-grey-500 ms-2" id="fileCount"></span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <select name="privacy" class="form-select form-select-sm w-auto me-2 font-xssss">
                                        <option value="public">Public</option>
                                        <option value="followers">Followers only</option>
                                    </select>
                                    <button type="submit" class="btn bg-current text-white font-xssss fw-600 rounded-xxl px-3">Post</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const mediaInput = document.getElementById('mediaInput');
                        const mediaPreview = document.getElementById('mediaPreview');
                        const clearAllBtn = document.getElementById('clearAllBtn');
                        const fileCount = document.getElementById('fileCount');
                        const postForm = document.getElementById('postForm');
                        let selectedFiles = [];
                        
                        mediaInput.addEventListener('change', function(e) { handleFileSelection(e.target.files); });
                        
                        function handleFileSelection(files) {
                            const validTypes = ['image/jpeg','image/jpg','image/png','image/gif','image/webp','video/mp4','video/mov','video/avi','video/quicktime','video/x-msvideo'];
                            const maxFiles = 4, maxSize = 20*1024*1024;
                            const newFiles = Array.from(files);
                            if (selectedFiles.length + newFiles.length > maxFiles) { alert(`Maximum ${maxFiles} files allowed.`); return; }
                            newFiles.forEach(file => {
                                if (!validTypes.includes(file.type)) { alert(`"${file.name}" is not supported.`); return; }
                                if (file.size > maxSize) { alert(`"${file.name}" is too large.`); return; }
                                selectedFiles.push(file);
                            });
                            updatePreview(); updateFileInput();
                        }
                        
                        function updatePreview() {
                            mediaPreview.innerHTML = '';
                            if (selectedFiles.length === 0) { clearAllBtn.classList.add('d-none'); fileCount.textContent = ''; return; }
                            fileCount.textContent = `${selectedFiles.length} file(s) selected`;
                            clearAllBtn.classList.remove('d-none');
                            const grid = document.createElement('div'); grid.className = 'row g-2';
                            selectedFiles.forEach((file, index) => {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    const col = document.createElement('div'); col.className = 'col-6 col-md-3';
                                    const isImage = file.type.startsWith('image/');
                                    col.innerHTML = `<div class="position-relative"><div class="rounded-3 overflow-hidden border bg-light" style="height:120px;">${isImage ? `<img src="${e.target.result}" class="w-100 h-100" style="object-fit:cover;">` : `<div class="w-100 h-100 bg-dark d-flex align-items-center justify-content-center"><i class="feather-play text-white fs-3"></i></div>`}</div><button type="button" class="btn btn-danger btn-xs position-absolute top-0 end-0 rounded-circle" onclick="removeFile(${index})" style="width:20px;height:20px;padding:0;font-size:14px;transform:translate(30%,-30%);">×</button></div>`;
                                    grid.appendChild(col);
                                };
                                reader.readAsDataURL(file);
                            });
                            mediaPreview.appendChild(grid);
                        }
                        
                        window.removeFile = function(i) { selectedFiles.splice(i,1); updatePreview(); updateFileInput(); };
                        window.clearAllMedia = function() { if(confirm('Remove all files?')) { selectedFiles=[]; updatePreview(); updateFileInput(); } };
                        function updateFileInput() { const dt = new DataTransfer(); selectedFiles.forEach(f => dt.items.add(f)); mediaInput.files = dt.files; }
                        
                        postForm.addEventListener('submit', function(e) {
                            const content = this.querySelector('textarea[name="content"]').value.trim();
                            if (content === '' && selectedFiles.length === 0) { e.preventDefault(); alert('Please add some text or media.'); return; }
                            updateFileInput();
                        });
                    });
                    </script>
                    @endif

                    {{-- ============ POSTS LIST ============ --}}
                    @forelse($posts as $post)
                    <div class="card w-100 shadow-xss rounded-xxl border-0 p-4 mb-3" id="post-{{ $post->id }}">
                        <div class="d-flex mb-3 align-items-center">
                            <img src="{{ $post->user->profile_picture_url }}"
                                 class="rounded-circle me-2" style="width:45px;height:45px;object-fit:cover;">
                            <div>
                                <h4 class="fw-700 font-xssss mb-0">{{ $post->user->name }}</h4>
                                <span class="font-xssss text-grey-500">{{ $post->created_at->diffForHumans() }}</span>
                                <span class="post-privacy-pill ms-2">
                                    <i class="{{ in_array($post->privacy, ['followers', 'friends'], true) ? 'feather-users' : 'feather-globe' }}"></i>
                                    {{ in_array($post->privacy, ['followers', 'friends'], true) ? 'Followers only' : 'Public' }}
                                </span>
                            </div>
                            
                            @if($post->user_id === auth()->id())
                            <div class="ms-auto">
                                <a href="#" class="ms-auto" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti-more-alt text-grey-900 btn-round-md bg-greylight font-xss"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end p-3 rounded-xxl border-0 shadow-lg">
                                    <li>
                                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger font-xssss" onclick="return confirm('Delete this post?')">
                                                <i class="feather-trash-2 me-2"></i> Delete Post
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                            @endif
                        </div>

                        @if($post->content)
                        <p class="font-xssss text-grey-700 lh-26 mb-3">{{ $post->content }}</p>
                        @endif

                        @if($post->media->count())
                        <div class="row g-2 mt-2">
                            @foreach($post->media as $media)
                                <div class="col-12 col-md-{{ $post->media->count() == 1 ? '12' : ($post->media->count() == 2 ? '6' : '4') }} p-1">
                                    @if($media->file_type === 'image')
                                        <img src="{{ asset('storage/'.$media->file_path) }}"
                                             class="rounded-3 w-100"
                                             style="{{ $post->media->count() > 1 ? 'height:250px;' : 'max-height:600px;' }} object-fit:cover; cursor:pointer;"
                                             onclick="openMediaModal('{{ asset('storage/'.$media->file_path) }}', 'image')">
                                    @else
                                        <video controls class="rounded-3 w-100" style="max-height:500px;">
                                            <source src="{{ asset('storage/'.$media->file_path) }}" type="{{ $media->mime_type }}">
                                        </video>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @endif

                        {{-- Livewire Post Interactions --}}
                        @auth
                        <livewire:post-interaction :post="$post" :key="'post-'.$post->id" />
                        @endauth
                    </div>
                    @empty
                        <div class="text-center font-xssss text-grey-500 py-4">
                            <i class="feather-edit-3 d-block fs-2 mb-2 text-grey-300"></i>
                            No posts yet.
                        </div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Media Modal --}}
<div class="modal fade" id="mediaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid rounded" style="max-height:70vh;">
                <video id="modalVideo" controls class="w-100 rounded" style="display:none;"></video>
            </div>
        </div>
    </div>
</div>

<script>
function openMediaModal(src, type) {
    const modalEl = document.getElementById('mediaModal');
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    const img = document.getElementById('modalImage'), vid = document.getElementById('modalVideo');
    if (type === 'image') { img.src = src; img.style.display='block'; vid.style.display='none'; }
    else { vid.src = src; vid.style.display='block'; img.style.display='none'; }
    modal.show();
}
</script>

@if(auth()->id() === $user->id)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // ---- Cover Dragging Logic ----
    const img = document.getElementById('coverImage');
    const container = document.getElementById('coverContainer');
    const saveBtn = document.getElementById('saveCoverBtn');
    const posInput = document.getElementById('coverPositionInput');
    const positionForm = document.getElementById('coverPositionForm');
    
    let isDragging = false;
    let startY = 0;
    let currentYPercent = parseFloat((img.style.objectPosition || '50% 50%').split(' ')[1]) || 50;

    img.addEventListener('mousedown', (e) => {
        isDragging = true;
        startY = e.clientY;
        img.style.cursor = 'grabbing';
        container.classList.add('is-moving');
        e.preventDefault();
    });

    window.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        const deltaY = e.clientY - startY;
        const sensitivity = 12;
        let newPercent = currentYPercent - (deltaY / sensitivity);
        newPercent = Math.max(0, Math.min(100, newPercent));
        img.style.objectPosition = `50% ${newPercent}%`;
        saveBtn.classList.remove('d-none');
    });

    window.addEventListener('mouseup', (e) => {
        if (isDragging) {
            isDragging = false;
            img.style.cursor = 'grab';
            container.classList.remove('is-moving');
            currentYPercent = parseFloat(img.style.objectPosition.split(' ')[1]) || 50;
            posInput.value = `50% ${currentYPercent}%`;
        }
    });

    saveBtn.addEventListener('click', () => {
        saveBtn.innerHTML = '<i class="feather-loader animation-spin"></i>';
        const formData = new FormData(positionForm);
        formData.append('name', @json($user->name));
        formData.append('username', @json($user->username));
        formData.append('email', @json($user->email));
        
        fetch(positionForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        }).then(async res => {
            if (res.ok) {
                saveBtn.innerHTML = 'Saved';
                setTimeout(() => saveBtn.classList.add('d-none'), 2000);
            } else {
                const data = await res.json().catch(() => ({}));
                alert(data.errors ? Object.values(data.errors)[0][0] : 'Failed to save position.');
                saveBtn.innerHTML = 'Save Position';
            }
        });
    });

    // ---- Avatar Dragging Logic ----
    const avatarImg = document.getElementById('avatarImage');
    const saveProfileBtn = document.getElementById('saveProfileBtn');
    const profilePosInput = document.getElementById('profilePositionInput');
    const profilePositionForm = document.getElementById('profilePositionForm');
    
    if (avatarImg) {
        let isAvatarDragging = false;
        let startAvatarX = 0;
        let startAvatarY = 0;
        
        let currentPos = (avatarImg.style.objectPosition || '50% 50%').split(' ');
        let currentAvatarXPercent = parseFloat(currentPos[0]) || 50;
        let currentAvatarYPercent = parseFloat(currentPos[1]) || 50;

        avatarImg.addEventListener('mousedown', (e) => {
            isAvatarDragging = true;
            startAvatarX = e.clientX;
            startAvatarY = e.clientY;
            avatarImg.style.cursor = 'grabbing';
            avatarImg.classList.add('is-moving');
            // Prevent default drag
            e.preventDefault();
        });

        window.addEventListener('mousemove', (e) => {
            if (!isAvatarDragging) return;
            const deltaX = e.clientX - startAvatarX;
            const deltaY = e.clientY - startAvatarY;
            const sensitivity = 5;
            
            let newXPercent = currentAvatarXPercent - (deltaX / sensitivity);
            let newYPercent = currentAvatarYPercent - (deltaY / sensitivity);
            
            newXPercent = Math.max(0, Math.min(100, newXPercent));
            newYPercent = Math.max(0, Math.min(100, newYPercent));
            
            avatarImg.style.objectPosition = `${newXPercent}% ${newYPercent}%`;
            saveProfileBtn.classList.remove('d-none');
            saveProfileBtn.classList.add('d-block');
        });

        window.addEventListener('mouseup', (e) => {
            if (isAvatarDragging) {
                isAvatarDragging = false;
                avatarImg.style.cursor = 'grab';
                avatarImg.classList.remove('is-moving');
                let newPos = avatarImg.style.objectPosition.split(' ');
                currentAvatarXPercent = parseFloat(newPos[0]) || 50;
                currentAvatarYPercent = parseFloat(newPos[1]) || 50;
                profilePosInput.value = `${currentAvatarXPercent}% ${currentAvatarYPercent}%`;
            }
        });

        saveProfileBtn.addEventListener('click', () => {
            saveProfileBtn.innerHTML = '<i class="feather-loader animation-spin"></i>';
            const formData = new FormData(profilePositionForm);
            formData.append('name', @json($user->name));
            formData.append('username', @json($user->username));
            formData.append('email', @json($user->email));
            
            fetch(profilePositionForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            }).then(async res => {
                if (res.ok) {
                    saveProfileBtn.innerHTML = 'Saved';
                    setTimeout(() => {
                        saveProfileBtn.classList.remove('d-block');
                        saveProfileBtn.classList.add('d-none');
                        saveProfileBtn.innerHTML = 'Save Position';
                    }, 2000);
                } else {
                    const data = await res.json().catch(() => ({}));
                    alert(data.errors ? Object.values(data.errors)[0][0] : 'Failed to save profile position.');
                    saveProfileBtn.innerHTML = 'Save Position';
                }
            });
        });
    }

    // ---- AJAX File Uploads (Avatar & Cover) ----
    const avatarInput = document.getElementById('avatarInput');
    const coverInput = document.getElementById('coverInput');

    function uploadImage(formId, inputElement, targetImgSelector) {
        const form = document.getElementById(formId);
        if(!form) return;
        
        const file = inputElement.files[0];
        if(!file) return;

        // Preview locally immediately
        const reader = new FileReader();
        reader.onload = (e) => {
            document.querySelector(targetImgSelector).src = e.target.result;
        };
        reader.readAsDataURL(file);

        // Upload in background
        const formData = new FormData(form);
        formData.append('name', @json($user->name));
        formData.append('username', @json($user->username));
        formData.append('email', @json($user->email));

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        }).then(async res => {
            if(!res.ok) {
                const data = await res.json().catch(() => ({}));
                let msg = 'Failed to upload image. Make sure it is under 10MB.';
                if (data.errors) {
                    msg = Object.values(data.errors)[0][0];
                } else if (data.message) {
                    msg = data.message;
                }
                alert(msg);
                
                // Revert preview on failure
                if (targetImgSelector === '#coverImage') {
                    document.querySelector(targetImgSelector).src = "{{ $user->cover_picture ? asset('storage/'.$user->cover_picture) : asset('images/u-bg.jpg') }}";
                } else {
                    document.querySelector(targetImgSelector).src = "{{ $user->profile_picture_url }}";
                }
            }
        }).catch(err => {
            console.error('Upload error:', err);
        });
    }

    if(avatarInput) {
        avatarInput.addEventListener('change', () => {
            uploadImage('avatarForm', avatarInput, '.avatar img');
        });
    }
    
    if(coverInput) {
        coverInput.addEventListener('change', () => {
            uploadImage('coverForm', coverInput, '#coverImage');
        });
    }
});
</script>
@endif

@endsection
