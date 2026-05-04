@extends('include.master')
@section('page', 'Notifications')
@section('content')

<div class="main-content right-chat-active">
    <div class="middle-sidebar-bottom">
        <div class="middle-sidebar-left">
            <div class="row">
                <div class="col-xl-8 col-xxl-9 col-lg-8 mx-auto">
                    <div class="card shadow-xss w-100 d-block d-flex border-0 p-4 mb-3">
                        <h2 class="fw-700 mb-0 font-md text-grey-900">Notifications</h2>
                    </div>

                    @forelse($notifications as $notif)
                    <div class="card shadow-xss rounded-xxl border-0 p-4 mb-2 {{ !$notif->read ? 'bg-greylight' : '' }}">
                        <div class="d-flex align-items-center">
                            <img src="{{ $notif->fromUser->profile_picture_url }}" 
                                 class="rounded-circle me-3" style="width:40px;height:40px;object-fit:cover;">
                            <div>
                                <p class="fw-600 font-xssss text-grey-900 mb-0">
                                    <a href="{{ route('user.profile', $notif->fromUser->username) }}" class="fw-700 text-grey-900">{{ $notif->fromUser->name }}</a>
                                    @if($notif->type === 'follow')
                                        started following you
                                    @elseif($notif->type === 'uplift')
                                        uplifted your profile <i class="feather-arrow-up text-warning"></i>
                                    @elseif($notif->type === 'like')
                                        @if($notif->post_id)
                                            <a href="{{ route('user.profile', auth()->user()->username) }}#post-{{ $notif->post_id }}" class="text-current">liked your post</a>
                                        @else
                                            liked your post
                                        @endif
                                        <i class="feather-thumbs-up text-primary"></i>
                                    @elseif($notif->type === 'comment')
                                        @if($notif->post_id)
                                            <a href="{{ route('user.profile', auth()->user()->username) }}#post-{{ $notif->post_id }}" class="text-current">commented on your post</a>
                                        @else
                                            commented on your post
                                        @endif
                                        <i class="feather-message-circle text-success"></i>
                                    @endif
                                </p>
                                <span class="font-xsssss text-grey-500">{{ $notif->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="card shadow-xss rounded-xxl border-0 p-4 text-center">
                        <p class="font-xssss text-grey-500 mb-0">No notifications yet.</p>
                    </div>
                    @endforelse

                    <div class="mt-3">{{ $notifications->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
