<div class="col-md-4 col-lg-3 mb-4">
    <div class="card shadow-xss border-0 rounded-xxl overflow-hidden text-center">
        <div class="p-3">
            <a href="{{ route('user.profile', $user->username) }}">
                <img src="{{ $user->profile_picture_url }}" class="rounded-circle mb-2" style="width:70px;height:70px;object-fit:cover;border:3px solid var(--theme-color);">
            </a>
            @if($user->is_online)
            <span class="badge bg-success font-xsssss rounded-pill mb-1 d-block mx-auto" style="width:fit-content;">Online</span>
            @endif
            <h4 class="fw-700 font-xssss text-grey-900 mb-0">{{ $user->name }}</h4>
            <a href="{{ route('user.profile', $user->username) }}" class="fw-600 font-xsssss text-current d-block mb-1">&#64;{{ $user->username }}</a>
            <span class="badge {{ $label === 'Friend' ? 'bg-success' : ($label === 'Following' ? 'bg-primary' : 'bg-secondary') }} text-white font-xsssss px-2 py-1 rounded-pill">{{ $label }}</span>
        </div>
        <div class="border-top p-2">
            <a href="{{ route('user.profile', $user->username) }}" class="btn bg-current text-white font-xsssss fw-600 rounded-xl w-100 py-1">View Profile</a>
        </div>
    </div>
</div>
