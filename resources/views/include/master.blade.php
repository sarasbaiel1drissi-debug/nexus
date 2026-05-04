
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('page') | {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/feather.css') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <link rel="stylesheet" href="{{asset('css/style.css') }}">
    <link rel="stylesheet" href="{{asset('css/dark-mode.css') }}">
    <link rel="stylesheet" href="{{asset('css/emoji.css') }}">
    <link rel="stylesheet" href="{{asset('css/lightbox.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Global Vertical Centering --}}
    <style>
        /* Vertical center text content within cards and content wrappers */
        .card-body,
        .card > .p-4,
        .card > .p-3 {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        /* Cards with d-flex already set should keep their row direction */
        .card-body.d-flex {
            justify-content: center;
        }

        /* ── Select / Dropdown elements ── */
        .form-group,
        .form-group select,
        .form-group .form-select,
        .style2-input {
            display: flex;
            align-items: center;
        }
        select.form-control,
        select.form-select,
        .custom-select {
            display: flex;
            align-items: center;
            text-align-last: left;
        }

        /* ── Nav & menu items ── */
        .nav-content-bttn,
        .nav-wrap .mb-1 li a,
        .dropdown-menu a,
        .dropdown-menu .d-flex,
        .dropdown-item {
            display: flex;
            align-items: center;
        }

        /* ── Sidebar navigation items ── */
        .navigation.scroll-bar li a,
        .navigation li a {
            display: flex;
            align-items: center;
        }
        .navigation li a i,
        .navigation li a span {
            display: inline-flex;
            align-items: center;
        }

        /* ── Notification & message dropdown items ── */
        .dropdown-menu-lg .dropdown-item,
        .dropdown-menu-lg .d-flex {
            display: flex;
            align-items: center;
        }

        /* ── Player / user cards ── */
        .card .d-flex.align-items-center {
            min-height: 0;
        }

        /* ── Stat counters on profiles ── */
        .count-box,
        .user-count,
        .figure-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* ── List group items ── */
        .list-group-item {
            display: flex;
            align-items: center;
        }

        /* ── Badge pills and tags ── */
        .badge,
        .rounded-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* ── Middle sidebar: don't force min-height (breaks scrolling), just ensure flow ── */
        .middle-sidebar-left .row {
            align-items: flex-start;
        }

        /* ── File input / select buttons ── */
        input[type="file"],
        .form-file,
        .custom-file,
        .custom-file-label,
        .form-control[type="file"] {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        input[type="file"]::file-selector-button {
            display: inline-flex;
            align-items: center;
            vertical-align: middle;
        }
        /* Generic file/image selector wrappers */
        .profile-picture-upload,
        .cover-picture-upload,
        .file-upload-wrapper,
        [class*="upload"] label,
        [class*="picture"] label {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>

@livewireStyles
    @auth
    @endauth
</head>

<body class="color-theme-white mont-font theme-dark">
    <div class="preloader"></div>
        <div class="main-wrapper">
            <div class="nav-header shadow-xs border-0">
                <div class="nav-top">
                    <a href="{{ route('dashboard') }}"><i class="feather-crosshair text-white display1-size me-2 ms-0" style="text-shadow: 0 0 15px rgba(255, 255, 255, 0.5);"></i><span class="d-inline-block fredoka-font ls-3 fw-600 text-white font-xxl logo-text mb-0">NEXUS </span> </a>
                    <a href="{{ route('messages') }}" class="mob-menu ms-auto me-2 chat-active-btn"><i class="feather-message-circle text-grey-900 font-sm btn-round-md bg-greylight"></i></a>
                    <a href="default-video.html" class="mob-menu me-2"><i class="feather-video text-grey-900 font-sm btn-round-md bg-greylight"></i></a>
                    <a href="#" class="me-2 menu-search-icon mob-menu"><i class="feather-search text-grey-900 font-sm btn-round-md bg-greylight"></i></a>
                    <button class="nav-menu me-0 ms-2"></button>
                </div>
                
                <form action="#" class="float-left header-search">
                    <div class="form-group mb-0 icon-input">
                        <i class="feather-search font-sm text-grey-400"></i>
                        <input type="text" placeholder="Start typing to search.." class="bg-grey border-0 lh-32 pt-2 pb-2 ps-5 pe-3 font-xssss fw-500 rounded-xl w350 theme-dark-bg">
                    </div>
                </form>
                <a href="{{ route('dashboard') }}" class="p-2 text-center ms-3 menu-icon center-menu-icon" title="Home"><i class="feather-home font-lg alert-primary btn-round-lg theme-dark-bg text-current"></i></a>
                <a href="{{ route('tasks') }}" class="p-2 text-center ms-0 menu-icon center-menu-icon" title="Daily Tasks"><i class="feather-check-square font-lg bg-greylight btn-round-lg theme-dark-bg text-grey-500"></i></a>
                <a href="{{ route('find-players') }}" class="p-2 text-center ms-0 menu-icon center-menu-icon" title="Find Players"><i class="feather-search font-lg bg-greylight btn-round-lg theme-dark-bg text-grey-500"></i></a>
                <a href="{{ route('connections') }}" class="p-2 text-center ms-0 menu-icon center-menu-icon" title="Connections"><i class="feather-users font-lg bg-greylight btn-round-lg theme-dark-bg text-grey-500"></i></a>

                {{-- ═══ NOTIFICATION BELL DROPDOWN ═══ --}}
                @auth
                @php
                    $headerNotifs = \App\Models\Notification::where('user_id', auth()->id())->with('fromUser')->latest()->take(3)->get();
                    $unreadNotifCount = \App\Models\Notification::where('user_id', auth()->id())->where('read', false)->count();
                @endphp
                @endauth
                <a href="#" class="p-2 text-center ms-auto menu-icon" id="dropdownMenu3" data-bs-toggle="dropdown" aria-expanded="false">
                    @auth @if($unreadNotifCount > 0)<span class="dot-count bg-warning"></span>@endif @endauth
                    <i class="feather-bell font-xl text-current" title="notifications"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end p-4 rounded-3 border-0 shadow-lg" aria-labelledby="dropdownMenu3" style="min-width:320px;">
                    <h4 class="fw-700 font-xss mb-3">Notifications</h4>
                    @auth
                    @forelse($headerNotifs as $hn)
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $hn->fromUser->profile_picture_url }}" class="rounded-circle me-2" style="width:36px;height:36px;object-fit:cover;">
                        <div>
                            <p class="fw-600 font-xssss text-grey-900 mb-0 lh-3">
                                <strong>{{ $hn->fromUser->name }}</strong>
                                @if($hn->type === 'follow') started following you
                                @elseif($hn->type === 'uplift') uplifted your profile
                                @elseif($hn->type === 'like') liked your post
                                @elseif($hn->type === 'comment') commented on your post
                                @endif
                            </p>
                            <span class="font-xsssss text-grey-500">{{ $hn->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @empty
                    <p class="font-xssss text-grey-500 mb-0">No notifications yet.</p>
                    @endforelse
                    <a href="{{ route('notifications') }}" class="d-block text-center fw-600 font-xssss text-current pt-2 border-top">See All Notifications</a>
                    @endauth
                </div>

                {{-- ═══ MESSAGES DROPDOWN ═══ --}}
                @auth
                @php
                    $headerConvos = \App\Models\Conversation::where('user_one_id', auth()->id())
                        ->orWhere('user_two_id', auth()->id())
                        ->with(['userOne', 'userTwo', 'messages' => fn($q) => $q->latest()->limit(1)])
                        ->latest('updated_at')
                        ->take(3)->get();
                    $totalUnread = auth()->user()->unread_message_count;
                @endphp
                @endauth
                <a href="#" class="p-2 text-center ms-3 menu-icon" id="dropdownMenu4" data-bs-toggle="dropdown" aria-expanded="false">
                    @auth @if($totalUnread > 0)<span class="dot-count bg-danger"></span>@endif @endauth
                    <i class="feather-message-square font-xl text-current" title="messages"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end p-4 rounded-3 border-0 shadow-lg" aria-labelledby="dropdownMenu4" style="min-width:320px;">
                    <h4 class="fw-700 font-xss mb-3">Messages</h4>
                    @auth
                    @forelse($headerConvos as $hc)
                    @php
                        $otherUser = $hc->user_one_id === auth()->id() ? $hc->userTwo : $hc->userOne;
                        $lastMsg = $hc->messages->first();
                        $convUnread = \App\Models\Message::where('conversation_id', $hc->id)->where('sender_id', '!=', auth()->id())->whereNull('read_at')->count();
                    @endphp
                    <a href="{{ route('messages', $otherUser->id) }}" class="d-flex align-items-center mb-3 text-decoration-none">
                        <img src="{{ $otherUser->profile_picture_url }}" class="rounded-circle me-2" style="width:36px;height:36px;object-fit:cover;">
                        <div class="flex-grow-1 overflow-hidden">
                            <h5 class="{{ $convUnread > 0 ? 'fw-700' : 'fw-600' }} font-xssss text-grey-900 mb-0 text-truncate">{{ $otherUser->name }}</h5>
                            <p class="{{ $convUnread > 0 ? 'fw-700 text-grey-900' : 'fw-400 text-grey-500' }} font-xsssss mb-0 text-truncate">{{ $lastMsg ? $lastMsg->body : 'Start a conversation' }}</p>
                        </div>
                        @if($convUnread > 0)
                        <span class="bg-danger text-white rounded-circle font-xsssss fw-700 d-flex align-items-center justify-content-center ms-2" style="width:18px;height:18px;min-width:18px;">{{ $convUnread }}</span>
                        @endif
                    </a>
                    @empty
                    <p class="font-xssss text-grey-500 mb-0">No conversations yet.</p>
                    @endforelse
                    <a href="{{ route('messages') }}" class="d-block text-center fw-600 font-xssss text-current pt-2 border-top">See More</a>
                    @endauth
                </div>

                {{-- ═══ USER AVATAR ═══ --}}
                {{-- @auth
                <a href="{{ route('user.profile', auth()->user()->username) }}" class="p-0 ms-3 menu-icon">
                    <img src="{{ auth()->user()->profile_picture_url }}" class="rounded-circle" style="width:36px;height:36px;object-fit:cover;" title="{{ auth()->user()->name }}">
                </a>
                @endauth --}}
                <div class="p-2 text-center ms-3 position-relative dropdown-menu-icon menu-icon">
                    <i class="feather-settings animation-spin d-inline-block font-xl text-current cursor-pointer" title="colors"></i>
                    <div class="dropdown-menu-settings switchcolor-wrap">
                        <h4 class="fw-700 font-sm mb-4">Settings</h4>
                        <div class="bg-white rounded-3 shadow-sm overflow-hidden">
                            {{-- Profile --}}
                            <a href="{{ route('profile.edit') }}" class="d-flex align-items-center justify-content-between px-4 py-3 text-dark fw-600 text-decoration-none border-bottom hover-bg-light">
                                <div class="d-flex align-items-center gap-3"><i class="ti-user text-secondary"></i><span>Profile</span></div>
                                <i class="ti-angle-right text-muted"></i>
                            </a>

                            {{-- Admin Panel (admin only) --}}
                            @auth
                            @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.index') }}" class="d-flex align-items-center justify-content-between px-4 py-3 text-dark fw-600 text-decoration-none border-bottom hover-bg-light">
                                <div class="d-flex align-items-center gap-3"><i class="ti-settings text-warning"></i><span>Admin Panel</span></div>
                                <i class="ti-angle-right text-muted"></i>
                            </a>
                            @endif
                            @endauth

                            {{-- Logout --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-100 d-flex align-items-center justify-content-between px-4 py-3 bg-transparent border-0 fw-600 text-danger hover-bg-light">
                                    <div class="d-flex align-items-center gap-3"><i class="ti-power-off"></i><span>Log out</span></div>
                                </button>
                            </form>
                        </div>
                    </div>
</div>
            
    @auth
    <a href="{{ route('user.profile', auth()->user()->username) }}" class="p-0 ms-3 menu-icon">
        <img src="{{ auth()->user()->profile_picture_url }}" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;" title="{{ auth()->user()->name }}">
    </a>
    @endauth
            </div>
        <nav class="navigation scroll-bar">
            <div class="container ps-0 pe-0">
                <div class="nav-content">
                    {{-- Gaming Hub removed by user request --}}

                    <div class="nav-wrap bg-white bg-transparent-card rounded-xxl shadow-xss pt-3 pb-1 mb-2">
                        <div class="nav-caption fw-600 font-xssss text-grey-500"><span>New </span></div>
                        <ul class="mb-3">
                            <li><a href="{{ route('find-players') }}" class="nav-content-bttn open-font"><i class="font-xl text-current feather-search me-3"></i><span>Find Players</span></a></li>
                        </ul>
                    </div>
                    <div class="nav-wrap bg-white bg-transparent-card rounded-xxl shadow-xss pt-3 pb-1">
                        <div class="nav-caption fw-600 font-xssss text-grey-500"><span></span> Account</div>
                        <ul class="mb-1">
                            <li><a href="{{ route('messages') }}" class="nav-content-bttn open-font h-auto pt-2 pb-2 position-relative"><i class="font-sm feather-message-square me-3 text-grey-500"></i><span>Chat</span>@auth @if(auth()->user()->unread_message_count > 0)<span class="bg-danger text-white rounded-circle position-absolute font-xsssss fw-700 d-flex align-items-center justify-content-center" style="width:16px;height:16px;top:8px;left:20px;">{{ auth()->user()->unread_message_count > 9 ? '9+' : auth()->user()->unread_message_count }}</span>@endif @endauth</a></li>
                            <li class="logo d-none d-xl-block d-lg-block"></li>
                            <li><a href="{{ route('notifications') }}" class="nav-content-bttn open-font h-auto pt-2 pb-2 position-relative"><i class="font-sm feather-bell me-3 text-grey-500"></i><span>Notifications</span>@auth @php $unreadNotifs = \App\Models\Notification::where('user_id', auth()->id())->where('read', false)->count(); @endphp @if($unreadNotifs > 0)<span class="bg-danger text-white rounded-circle position-absolute font-xsssss fw-700 d-flex align-items-center justify-content-center" style="width:16px;height:16px;top:8px;left:20px;">{{ $unreadNotifs > 9 ? '9+' : $unreadNotifs }}</span>@endif @endauth</a></li>
                            <li><a href="{{ route('profile.edit') }}" class="nav-content-bttn open-font h-auto pt-2 pb-2"><i class="font-sm feather-settings me-3 text-grey-500"></i><span>Settings</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <div class="main-content right-chat-active">       
        </div>

        @yield('content')
    </div> 

 
    
    <script src="{{ asset('js/plugin.js') }}"></script>
    <script src="{{ asset('js/lightbox.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    @livewireScripts
</body>

</html>