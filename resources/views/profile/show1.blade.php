@extends('include.master')
@section('page', 'profile')
 @section('content') 
 
 <!-- main content -->
<div class="main-content right-chat-active">
    <div class="middle-sidebar-bottom">
        <div class="middle-sidebar-left">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card w-100 border-0 p-0 bg-white shadow-xss rounded-xxl">
                        <div class="card-body h250 p-0 rounded-xxl overflow-hidden m-3"><img
                                src="{{ $user->cover_picture ? asset('storage/' . $user->cover_picture) : asset('images/u-bg.jpg') }}"
                                alt="cover" class="w-100 h-100 object-fit-cover"></div>
                        <div class="card-body p-0 position-relative">
                            <figure class="avatar position-absolute w100 z-index-1" style="top:-40px; left: 30px;"> <img
                                    src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default-avatar.png') }}"
                                    alt="avatar" class="float-right p-1 bg-white rounded-circle w-100"></figure>
                            <h4 class="fw-700 font-sm mt-2 mb-lg-5 mb-4 pl-15">{{ $user->name }} <span
                                    class="fw-500 font-xssss text-grey-500 mt-1 mb-3 d-block">{{ $user->username }}</span>
                            </h4>
                            <div
                                class="d-flex align-items-center justify-content-center position-absolute-md right-15 top-0 me-2">
                                <a href="#"
                                    class="d-none d-lg-block bg-success p-3 z-index-1 rounded-3 text-white font-xsssss text-uppercase fw-700 ls-3">Add
                                    Friend</a> <a href="#"
                                    class="d-none d-lg-block bg-greylight btn-round-lg ms-2 rounded-3 text-grey-700"><i
                                        class="feather-mail font-md"></i></a> <a href="#" id="dropdownMenu4"
                                    class="d-none d-lg-block bg-greylight btn-round-lg ms-2 rounded-3 text-grey-700"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                        class="ti-more font-md tetx-dark"></i></a>
                                <div class="dropdown-menu dropdown-menu-end p-4 rounded-xxl border-0 shadow-lg"
                                    aria-labelledby="dropdownMenu4">
                                    <div class="card-body p-0 d-flex"> <i
                                            class="feather-bookmark text-grey-500 me-3 font-lg"></i>
                                        <h4 class="fw-600 text-grey-900 font-xssss mt-0 me-0">Save Link <span
                                                class="d-block font-xsssss fw-500 mt-1 lh-3 text-grey-500">Add this to
                                                your saved items</span></h4>
                                    </div>
                                    <div class="card-body p-0 d-flex mt-2"> <i
                                            class="feather-alert-circle text-grey-500 me-3 font-lg"></i>
                                        <h4 class="fw-600 text-grey-900 font-xssss mt-0 me-0">Hide Post <span
                                                class="d-block font-xsssss fw-500 mt-1 lh-3 text-grey-500">Save to your
                                                saved items</span></h4>
                                    </div>
                                    <div class="card-body p-0 d-flex mt-2"> <i
                                            class="feather-alert-octagon text-grey-500 me-3 font-lg"></i>
                                        <h4 class="fw-600 text-grey-900 font-xssss mt-0 me-0">Hide all from Group <span
                                                class="d-block font-xsssss fw-500 mt-1 lh-3 text-grey-500">Save to your
                                                saved items</span></h4>
                                    </div>
                                    <div class="card-body p-0 d-flex mt-2"> <i
                                            class="feather-lock text-grey-500 me-3 font-lg"></i>
                                        <h4 class="fw-600 mb-0 text-grey-900 font-xssss mt-0 me-0">Unfollow Group <span
                                                class="d-block font-xsssss fw-500 mt-1 lh-3 text-grey-500">Save to your
                                                saved items</span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body d-block w-100 shadow-none mb-0 p-0 border-top-xs">
                            <ul class="nav nav-tabs h55 d-flex product-info-tab border-bottom-0 ps-4" id="pills-tab"
                                role="tablist">
                                <li class="active list-inline-item me-5"><a
                                        class="fw-700 font-xssss text-grey-500 pt-3 pb-3 ls-1 d-inline-block active"
                                        href="#navtabs1" data-toggle="tab">About</a></li>
                                <li class="list-inline-item me-5"><a
                                        class="fw-700 font-xssss text-grey-500 pt-3 pb-3 ls-1 d-inline-block"
                                        href="#navtabs2" data-toggle="tab">Badges</a></li>
                                <li class="list-inline-item me-5"><a
                                        class="fw-700 font-xssss text-grey-500 pt-3 pb-3 ls-1 d-inline-block"
                                        href="#navtabs3" data-toggle="tab">Threads</a></li>
                                <li class="list-inline-item me-5"><a
                                        class="fw-700 font-xssss text-grey-500 pt-3 pb-3 ls-1 d-inline-block"
                                        href="#navtabs4" data-toggle="tab">Content</a></li>
                                <li class="list-inline-item me-5"><a
                                        class="fw-700 me-sm-5 font-xssss text-grey-500 pt-3 pb-3 ls-1 d-inline-block"
                                        href="#navtabs7" data-toggle="tab">Media</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-xxl-3 col-lg-4 pe-0">
                    <div class="card w-100 shadow-xss rounded-xxl border-0 mb-3 mt-3">
                        <div class="card-body p-3 border-0">
                            <div class="row">
                                <div class="col-3">
                                    <div class="chart-container w50 h50">
                                        <div class="chart position-relative" data-percent="78" data-bar-color="#a7d212">
                                            <span class="percent fw-700 font-xsss" data-after="%">Badge photo</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-8 ps-1">
                                    <h4 class="font-xsss d-block fw-700 mt-2 mb-0">Badge name </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card w-100 shadow-xss rounded-xxl border-0 mb-3">
                        <div class="card-body d-block p-4">
                            <h4 class="fw-700 mb-3 font-xsss text-grey-900">About</h4>
                            <p class="fw-500 text-grey-500 lh-24 font-xssss mb-0">{{ $user->bio ?? 'No bio yet' }}</p>
                        </div>
                        <div class="card-body border-top-xs d-flex"> <i
                                class="feather-lock text-grey-500 me-3 font-lg"></i>
                            <h4 class="fw-700 text-grey-900 font-xssss mt-0">Private <span
                                    class="d-block font-xssss fw-500 mt-1 lh-3 text-grey-500">What's up, how are
                                    you?</span></h4>
                        </div>
                        <div class="card-body d-flex pt-0"> <i class="feather-eye text-grey-500 me-3 font-lg"></i>
                            <h4 class="fw-700 text-grey-900 font-xssss mt-0">Visble <span
                                    class="d-block font-xssss fw-500 mt-1 lh-3 text-grey-500">Anyone can find you</span>
                            </h4>
                        </div>
                        <div class="card-body d-flex pt-0"> <i class="feather-map-pin text-grey-500 me-3 font-lg"></i>
                            <h4 class="fw-700 text-grey-900 font-xssss mt-1">{{ $user->location ?? 'unknown' }}</h4>
                        </div>
                        <div class="card-body d-flex pt-0"> <i class="feather-users text-grey-500 me-3 font-lg"></i>
                            <h4 class="fw-700 text-grey-900 font-xssss mt-1">Genarel Group</h4>
                        </div>
                    </div>
                    <div class="card w-100 shadow-xss rounded-xxl border-0 mb-3">
                        <div class="card-body d-flex align-items-center p-4">
                            <h4 class="fw-700 mb-0 font-xssss text-grey-900">Photos</h4> <a href="#"
                                class="fw-600 ms-auto font-xssss text-primary">See all</a>
                        </div>
                        <div class="card-body d-block pt-0 pb-2">
                            <div class="row">
                                <div class="col-6 mb-2 pe-1"><a href="images/e-2.jpg" data-lightbox="roadtrip"><img
                                            src="images/e-2.jpg" alt="image"
                                            class="img-fluid rounded-3 w-100"></a></div>
                                <div class="col-6 mb-2 ps-1"><a href="images/e-3.jpg" data-lightbox="roadtrip"><img
                                            src="images/e-3.jpg" alt="image"
                                            class="img-fluid rounded-3 w-100"></a></div>
                                <div class="col-6 mb-2 pe-1"><a href="images/e-4.jpg" data-lightbox="roadtrip"><img
                                            src="images/e-4.jpg" alt="image"
                                            class="img-fluid rounded-3 w-100"></a></div>
                                <div class="col-6 mb-2 ps-1"><a href="images/e-5.jpg" data-lightbox="roadtrip"><img
                                            src="images/e-5.jpg" alt="image"
                                            class="img-fluid rounded-3 w-100"></a></div>
                                <div class="col-6 mb-2 pe-1"><a href="images/e-2.jpg" data-lightbox="roadtrip"><img
                                            src="images/e-2.jpg" alt="image"
                                            class="img-fluid rounded-3 w-100"></a></div>
                                <div class="col-6 mb-2 ps-1"><a href="images/e-1.jpg" data-lightbox="roadtrip"><img
                                            src="images/e-1.jpg" alt="image"
                                            class="img-fluid rounded-3 w-100"></a></div>
                            </div>
                        </div>
                        <div class="card-body d-block w-100 pt-0"> <a href="#"
                                class="p-2 lh-28 w-100 d-block bg-grey text-grey-800 text-center font-xssss fw-700 rounded-xl"><i
                                    class="feather-external-link font-xss me-2"></i> More</a> </div>
                    </div>
                </div>
                <div class="col-xl-8 col-xxl-9 col-lg-8"> {{-- POST CREATION FORM --}} @if (auth()->check() && auth()->id() == $user->id)
                        <div class="card w-100 shadow-xss rounded-xxl border-0 ps-4 pt-4 pe-4 pb-3 mb-3 mt-3">
                            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data"
                                id="createPostForm"> @csrf <div class="card-body p-0"> <a href="#"
                                        class="font-xssss fw-600 text-grey-500 card-body p-0 d-flex align-items-center">
                                        <i
                                            class="btn-round-sm font-xs text-primary feather-edit-3 me-2 bg-greylight"></i>Create
                                        Post </a> </div>
                                <div class="card-body p-0 mt-3 position-relative">
                                    <figure class="avatar position-absolute ms-2 mt-1 top-5"> <img
                                            src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default-avatar.png') }}"
                                            alt="avatar" class="shadow-sm rounded-circle w30"> </figure>
                                    <textarea name="content"
                                        class="h100 bor-0 w-100 rounded-xxl p-2 ps-5 font-xssss text-grey-500 fw-500 border-light-md theme-dark-bg"
                                        cols="30" rows="3" placeholder="What's on your mind, {{ $user->name }}?">{{ old('content') }}</textarea> @error('content')
                                        <span class="text-danger font-xssss ms-5">{{ $message }}</span>
                                    @enderror
                                </div> {{-- MEDIA PREVIEW --}} <div class="card-body p-0 mt-3"
                                    id="mediaPreviewContainer" style="display: none;">
                                    <div class="row g-1 ps-5" id="mediaPreview"></div>
                                </div>
                                <div class="card-body d-flex p-0 mt-0"> {{-- LIVE VIDEO BUTTON --}} <label
                                        class="d-flex align-items-center font-xssss fw-600 ls-1 text-grey-700 text-dark pe-4 cursor-pointer">
                                        <i class="font-md text-danger feather-video me-2"></i> <span
                                            class="d-none-xs">Live Video</span> </label> {{-- PHOTO/VIDEO UPLOAD BUTTON --}} <label
                                        class="d-flex align-items-center font-xssss fw-600 ls-1 text-grey-700 text-dark pe-4 cursor-pointer">
                                        <i class="font-md text-success feather-image me-2"></i> <span
                                            class="d-none-xs">Photo/Video</span> <input type="file" name="media[]"
                                            multiple id="mediaInput" class="d-none" accept="image/*,video/*"
                                            onchange="previewMedia()"> </label> {{-- FEELING/ACTIVITY --}} <label
                                        class="d-flex align-items-center font-xssss fw-600 ls-1 text-grey-700 text-dark pe-4 cursor-pointer">
                                        <i class="font-md text-warning feather-camera me-2"></i> <span
                                            class="d-none-xs">Feeling/Activity</span> </label> {{-- PRIVACY DROPDOWN --}}
                                    <div class="dropdown ms-auto"> <a href="#" class="dropdown-toggle"
                                            id="privacyDropdown" data-bs-toggle="dropdown" aria-expanded="false"> <i
                                                class="ti-world text-grey-900 btn-round-md bg-greylight font-xss"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end p-3 rounded-xxl border-0 shadow-lg"
                                            aria-labelledby="privacyDropdown">
                                            <li>
                                                <h6 class="fw-600 text-grey-900 font-xsss mb-2">Who can see this?</h6>
                                            </li>
                                            <li> <label class="dropdown-item p-1 font-xssss cursor-pointer"> <input
                                                        class="form-check-input me-2" type="radio" name="privacy"
                                                        id="public" value="public" checked> <i
                                                        class="ti-world me-2"></i> Public </label> </li>
                                            <li> <label class="dropdown-item p-1 font-xssss cursor-pointer"> <input
                                                        class="form-check-input me-2" type="radio" name="privacy"
                                                        id="friends" value="friends"> <i class="ti-user me-2"></i>
                                                    Friends Only </label> </li>
                                            <li> <label class="dropdown-item p-1 font-xssss cursor-pointer"> <input
                                                        class="form-check-input me-2" type="radio" name="privacy"
                                                        id="private" value="private"> <i class="ti-lock me-2"></i>
                                                    Only Me </label> </li>
                                        </ul>
                                    </div> {{-- SUBMIT BUTTON --}} <button type="submit"
                                        class="btn bg-current text-white font-xssss fw-600 ps-3 pe-3 ms-2 rounded-xxl">
                                        Post </button> </div>
                            </form>
                        </div>
                        @endif {{-- DISPLAY USER'S POSTS --}} @forelse($posts as $post)
                            <div class="card w-100 shadow-xss rounded-xxl border-0 p-4 mb-3"> {{-- POST HEADER --}}
                                <div class="card-body p-0 d-flex">
                                    <figure class="avatar me-3"> <img
                                            src="{{ $post->user->profile_picture ? asset('storage/' . $post->user->profile_picture) : asset('images/default-avatar.png') }}"
                                            alt="avatar" class="shadow-sm rounded-circle w45"> </figure>
                                    <h4 class="fw-700 text-grey-900 font-xssss mt-1"> {{ $post->user->name }} <span
                                            class="d-block font-xssss fw-500 mt-1 lh-3 text-grey-500">
                                            {{ $post->created_at->diffForHumans() }} <i
                                                class="ti-{{ $post->privacy == 'public' ? 'world' : ($post->privacy == 'friends' ? 'user' : 'lock') }} ms-2"></i>
                                        </span> </h4> {{-- POST MENU DROPDOWN --}} @if (auth()->check() && auth()->id() == $post->user_id)
                                        <div class="ms-auto"> <a href="#" class="ms-auto" id="dropdownMenu7"
                                                data-bs-toggle="dropdown" aria-expanded="false"> <i
                                                    class="ti-more-alt text-grey-900 btn-round-md bg-greylight font-xss"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-end p-3 rounded-xxl border-0 shadow-lg"
                                                aria-labelledby="dropdownMenu7">
                                                <li>
                                                    <form action="{{ route('posts.destroy', $post) }}" method="POST"
                                                        class="d-inline"> @csrf @method('DELETE') <button
                                                            type="submit"
                                                            class="dropdown-item text-danger font-xssss"
                                                            onclick="return confirm('Delete this post?')"> <i
                                                                class="feather-trash-2 me-2"></i> Delete Post </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                        @endif
                                </div> {{-- POST CONTENT --}} @if ($post->content)
                                    <div class="card-body p-0 me-lg-5 mt-2">
                                        <p class="fw-500 text-grey-700 lh-26 font-xssss w-100">{{ $post->content }}
                                        </p>
                                    </div>
                                    @endif {{-- POST MEDIA --}} @if ($post->media->count() > 0)
                                        <div class="card-body d-block p-0 mt-3">
                                            @if ($post->media->count() == 1)
                                                @php $media = $post->media->first(); @endphp <div class="row ps-2 pe-2">
                                                    <div class="col-12 p-1">
                                                        @if ($media->file_type == 'image')
                                                            <a href="{{ asset('storage/' . $media->file_path) }}"
                                                                data-lightbox="roadtrip"> <img
                                                                    src="{{ asset('storage/' . $media->file_path) }}"
                                                                    class="rounded-3 w-100" alt="image"
                                                                    style="max-height: 500px; object-fit: contain;">
                                                            </a>
                                                        @elseif($media->file_type == 'video')
                                                            <video controls class="rounded-3 w-100"
                                                                style="max-height: 500px;">
                                                                <source
                                                                    src="{{ asset('storage/' . $media->file_path) }}"
                                                                    type="{{ $media->mime_type }}">
                                                            </video>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <div class="row ps-2 pe-2">
                                                    @foreach ($post->media as $index => $media)
                                                        @if ($loop->iteration <= 4)
                                                            {{-- Show max 4 images in grid --}} <div
                                                                class="col-xs-4 col-sm-{{ $post->media->count() == 2 ? '6' : ($post->media->count() == 3 ? '4' : '3') }} p-1">
                                                                @if ($media->file_type == 'image')
                                                                    <a href="{{ asset('storage/' . $media->file_path) }}"
                                                                        data-lightbox="roadtrip"> <img
                                                                            src="{{ asset('storage/' . $media->file_path) }}"
                                                                            class="rounded-3 w-100" alt="image"
                                                                            style="height: 200px; object-fit: cover;">
                                                                    </a>
                                                                @elseif($media->file_type == 'video')
                                                                    <video controls class="rounded-3 w-100"
                                                                        style="height: 200px; object-fit: cover;">
                                                                        <source
                                                                            src="{{ asset('storage/' . $media->file_path) }}"
                                                                            type="{{ $media->mime_type }}">
                                                                    </video>
                                                                    @endif {{-- Show +X overlay if more than 4 images --}} @if ($loop->last && $post->media->count() > 4)
                                                                        <a href="{{ asset('storage/' . $media->file_path) }}"
                                                                            data-lightbox="roadtrip"
                                                                            class="position-relative d-block"> <span
                                                                                class="img-count font-sm text-white ls-3 fw-600 position-absolute top-50 start-50 translate-middle">
                                                                                <b>+{{ $post->media->count() - 4 }}</b>
                                                                            </span> </a>
                                                                    @endif
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        @endif {{-- POST STATS --}} <div class="card-body d-flex p-0 mt-3"> <a
                                                href="javascript:void(0)"
                                                class="like-btn d-flex align-items-center fw-600 text-grey-900 text-dark lh-26 font-xssss me-3"
                                                data-post-id="{{ $post->id }}"> <i
                                                    class="feather-thumbs-up text-white bg-primary-gradiant me-1 btn-round-xs font-xss"></i>
                                                <span
                                                    class="like-count-{{ $post->id }}">{{ $post->likes_count }}</span>
                                                Like{{ $post->likes_count != 1 ? 's' : '' }} </a> <a
                                                href="javascript:void(0)"
                                                class="comment-toggle d-flex align-items-center fw-600 text-grey-900 text-dark lh-26 font-xssss"
                                                data-post-id="{{ $post->id }}"> <i
                                                    class="feather-message-circle text-dark text-grey-900 btn-round-sm font-lg me-1"></i>
                                                <span>{{ $post->comments_count }}</span>
                                                Comment{{ $post->comments_count != 1 ? 's' : '' }} </a> <a
                                                href="#"
                                                class="ms-auto d-flex align-items-center fw-600 text-grey-900 text-dark lh-26 font-xssss">
                                                <i
                                                    class="feather-share-2 text-grey-900 text-dark btn-round-sm font-lg"></i>
                                                <span class="d-none-xs">Share</span> </a> </div> {{-- POST ACTIONS --}}
                                        <div class="card-body d-flex p-0 border-top-xs mt-2"> <button type="button"
                                                class="like-action-btn d-flex align-items-center fw-600 text-grey-900 text-dark lh-28 font-xssss flex-fill text-center {{ $post->isLikedBy(auth()->id()) ? 'text-primary' : '' }}"
                                                data-post-id="{{ $post->id }}"> <i
                                                    class="feather-thumbs-up font-md me-2"></i> <span
                                                    class="like-text-{{ $post->id }}">{{ $post->isLikedBy(auth()->id()) ? 'Liked' : 'Like' }}</span>
                                            </button> <button type="button"
                                                class="comment-action-btn d-flex align-items-center fw-600 text-grey-900 text-dark lh-28 font-xssss flex-fill text-center"
                                                data-post-id="{{ $post->id }}"> <i
                                                    class="feather-message-circle font-md me-2"></i> Comment </button>
                                            <button type="button"
                                                class="d-flex align-items-center fw-600 text-grey-900 text-dark lh-28 font-xssss flex-fill text-center">
                                                <i class="feather-share-2 font-md me-2"></i> Share </button> </div>
                                        {{-- COMMENTS SECTION --}} <div
                                            class="card-body p-0 mt-3 comment-section-{{ $post->id }}"
                                            style="display: none;"> {{-- ADD COMMENT FORM --}} @if (auth()->check())
                                                <form action="{{ route('posts.comment', $post) }}" method="POST"
                                                    class="d-flex align-items-center mb-3"> @csrf <figure
                                                        class="avatar me-2"> <img
                                                            src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : asset('images/default-avatar.png') }}"
                                                            alt="avatar" class="shadow-sm rounded-circle w30">
                                                    </figure>
                                                    <div class="flex-grow-1"> <input type="text" name="content"
                                                            class="form-control rounded-xxl border-0 bg-greylight font-xssss fw-500"
                                                            placeholder="Write a comment..." required> </div> <button
                                                        type="submit"
                                                        class="btn bg-current text-white font-xssss fw-600 ps-3 pe-3 ms-2 rounded-xxl">
                                                        <i class="feather-send font-xs"></i> </button>
                                                </form>
                                                @endif {{-- DISPLAY COMMENTS --}} @foreach ($post->comments->take(3) as $comment)
                                                    <div class="d-flex mb-3">
                                                        <figure class="avatar me-2"> <img
                                                                src="{{ $comment->user->profile_picture ? asset('storage/' . $comment->user->profile_picture) : asset('images/default-avatar.png') }}"
                                                                alt="avatar" class="shadow-sm rounded-circle w30">
                                                        </figure>
                                                        <div class="flex-grow-1">
                                                            <div class="bg-greylight p-3 rounded-xxl">
                                                                <h4 class="fw-700 text-grey-900 font-xssss mt-0">
                                                                    {{ $comment->user->name }} <span
                                                                        class="d-block font-xssss fw-500 mt-1 lh-3 text-grey-500">
                                                                        {{ $comment->created_at->diffForHumans() }}
                                                                    </span> </h4>
                                                                <p class="fw-500 text-grey-700 lh-24 font-xssss mb-0">
                                                                    {{ $comment->content }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach @if ($post->comments_count > 3)
                                                        <div class="text-center"> <a href="javascript:void(0)"
                                                                class="fw-600 text-grey-500 font-xssss view-all-comments"
                                                                data-post-id="{{ $post->id }}"> View all
                                                                {{ $post->comments_count }} comments </a> </div>
                                                        @endif </div>
                            </div> @empty @if (auth()->check() && auth()->id() == $user->id)
                                <div class="card w-100 shadow-xss rounded-xxl border-0 p-4 mb-3 text-center">
                                    <div class="card-body p-4"> <i
                                            class="feather-edit-3 text-grey-500 font-xl d-block mb-3"></i>
                                        <h4 class="fw-700 text-grey-900 font-md">No posts yet</h4>
                                        <p class="fw-500 text-grey-500 lh-24 font-xssss mb-0">Share your first post
                                            with your friends!</p>
                                    </div>
                                </div>
                                @endif @endforelse {{-- PAGINATION --}} @if ($posts->hasPages())
                                    <div class="mt-3"> {{ $posts->links() }} </div>
                                @endif
                                <div class="card w-100 shadow-xss rounded-xxl border-0 p-4 mb-3">
                                    <div class="card-body p-0 d-flex">
                                        <figure class="avatar me-3"> <img
                                                src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default-avatar.png') }}"
                                                alt="avatar" class="shadow-sm rounded-circle w45"></figure>
                                        <h4 class="fw-700 text-grey-900 font-xssss mt-1">{{ $user->name }}<span
                                                class="d-block font-xssss fw-500 mt-1 lh-3 text-grey-500">{{ $user->created_at }}</span>
                                        </h4> <a href="#" class="ms-auto" id="dropdownMenu7"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                                class="ti-more-alt text-grey-900 btn-round-md bg-greylight font-xss"></i></a>
                                        <div class="dropdown-menu dropdown-menu-end p-4 rounded-xxl border-0 shadow-lg"
                                            aria-labelledby="dropdownMenu7">
                                            <div class="card-body p-0 d-flex"> <i
                                                    class="feather-bookmark text-grey-500 me-3 font-lg"></i>
                                                <h4 class="fw-600 text-grey-900 font-xssss mt-0 me-4">Save Link <span
                                                        class="d-block font-xsssss fw-500 mt-1 lh-3 text-grey-500">Add
                                                        this to your saved items</span></h4>
                                            </div>
                                            <div class="card-body p-0 d-flex mt-2"> <i
                                                    class="feather-alert-circle text-grey-500 me-3 font-lg"></i>
                                                <h4 class="fw-600 text-grey-900 font-xssss mt-0 me-4">Hide Post <span
                                                        class="d-block font-xsssss fw-500 mt-1 lh-3 text-grey-500">Save
                                                        to your saved items</span></h4>
                                            </div>
                                            <div class="card-body p-0 d-flex mt-2"> <i
                                                    class="feather-alert-octagon text-grey-500 me-3 font-lg"></i>
                                                <h4 class="fw-600 text-grey-900 font-xssss mt-0 me-4">Hide all from
                                                    Group <span
                                                        class="d-block font-xsssss fw-500 mt-1 lh-3 text-grey-500">Save
                                                        to your saved items</span></h4>
                                            </div>
                                            <div class="card-body p-0 d-flex mt-2"> <i
                                                    class="feather-lock text-grey-500 me-3 font-lg"></i>
                                                <h4 class="fw-600 mb-0 text-grey-900 font-xssss mt-0 me-4">Unfollow
                                                    Group <span
                                                        class="d-block font-xsssss fw-500 mt-1 lh-3 text-grey-500">Save
                                                        to your saved items</span></h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-0 me-lg-5">
                                        <p class="fw-500 text-grey-500 lh-26 font-xssss w-100">Lorem ipsum dolor sit
                                            amet, consectetur adipiscing elit. Morbi nulla dolor, ornare at commodo non,
                                            feugiat non nisi. Phasellus faucibus mollis pharetra. Proin blandit ac massa
                                            sed rhoncus <a href="#" class="fw-600 text-primary ms-2">See
                                                more</a></p>
                                    </div>
                                    <div class="card-body d-block p-0">
                                        <div class="row ps-2 pe-2">
                                            <div class="col-xs-4 col-sm-4 p-1"><a href="images/t-10.jpg"
                                                    data-lightbox="roadtrip"><img src="images/t-10.jpg"
                                                        class="rounded-3 w-100" alt="image"></a></div>
                                            <div class="col-xs-4 col-sm-4 p-1"><a href="images/t-11.jpg"
                                                    data-lightbox="roadtrip"><img src="images/t-11.jpg"
                                                        class="rounded-3 w-100" alt="image"></a></div>
                                            <div class="col-xs-4 col-sm-4 p-1"><a href="images/t-12.jpg"
                                                    data-lightbox="roadtrip" class="position-relative d-block"><img
                                                        src="images/t-12.jpg" class="rounded-3 w-100"
                                                        alt="image"><span
                                                        class="img-count font-sm text-white ls-3 fw-600"><b>+2</b></span></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body d-flex p-0 mt-3"> <a href="#"
                                            class="d-flex align-items-center fw-600 text-grey-900 text-dark lh-26 font-xssss me-3"><i
                                                class="feather-thumbs-up text-white bg-primary-gradiant me-1 btn-round-xs font-xss"></i>
                                            <i
                                                class="feather-heart text-white bg-red-gradiant me-2 btn-round-xs font-xss"></i>2.8K
                                            Like</a> <a href="#"
                                            class="d-flex align-items-center fw-600 text-grey-900 text-dark lh-26 font-xssss"><i
                                                class="feather-message-circle text-dark text-grey-900 btn-round-sm font-lg"></i>22
                                            Comment</a> <a href="#"
                                            class="ms-auto d-flex align-items-center fw-600 text-grey-900 text-dark lh-26 font-xssss"><i
                                                class="feather-share-2 text-grey-900 text-dark btn-round-sm font-lg"></i><span
                                                class="d-none-xs">Share</span></a> </div>
                                </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- main content --> @push('scripts')
    <script>
        // Media Preview Function function previewMedia() { const input = document.getElementById('mediaInput'); const previewContainer = document.getElementById('mediaPreviewContainer'); const preview = document.getElementById('mediaPreview'); preview.innerHTML = ''; previewContainer.style.display = 'none'; if (input.files && input.files.length > 0) { previewContainer.style.display = 'block'; for (let i = 0; i < input.files.length; i++) { const file = input.files[i]; const reader = new FileReader(); reader.onload = function(e) { const col = document.createElement('div'); col.className = 'col-3'; const div = document.createElement('div'); div.className = 'position-relative'; if (file.type.startsWith('image/')) { const img = document.createElement('img'); img.src = e.target.result; img.className = 'img-fluid rounded-3 w-100'; img.style.height = '100px'; img.style.objectFit = 'cover'; div.appendChild(img); } else if (file.type.startsWith('video/')) { const video = document.createElement('video'); video.src = e.target.result; video.className = 'img-fluid rounded-3 w-100'; video.style.height = '100px'; video.style.objectFit = 'cover'; video.controls = true; div.appendChild(video); } // Remove button const removeBtn = document.createElement('button'); removeBtn.type = 'button'; removeBtn.className = 'btn btn-danger btn-xs position-absolute top-0 end-0 m-1 rounded-circle p-0'; removeBtn.innerHTML = '<i class="feather-x font-xssss"></i>'; removeBtn.style.width = '20px'; removeBtn.style.height = '20px'; removeBtn.style.fontSize = '10px'; removeBtn.onclick = function() { col.remove(); if (preview.children.length === 0) { previewContainer.style.display = 'none'; } }; div.appendChild(removeBtn); col.appendChild(div); preview.appendChild(col); } reader.readAsDataURL(file); } } } // Initialize when document is ready document.addEventListener('DOMContentLoaded', function() { // Like buttons document.querySelectorAll('.like-action-btn').forEach(button => { button.addEventListener('click', function() { const postId = this.dataset.postId; likePost(postId); }); }); // Comment toggle buttons document.querySelectorAll('.comment-action-btn').forEach(button => { button.addEventListener('click', function() { const postId = this.dataset.postId; const commentSection = document.querySelector(.comment-section-${postId}); if (commentSection) { commentSection.style.display = commentSection.style.display === 'none' ? 'block' : 'none'; } }); }); // Comment toggle from stats document.querySelectorAll('.comment-toggle').forEach(button => { button.addEventListener('click', function() { const postId = this.dataset.postId; const commentSection = document.querySelector(.comment-section-${postId}); if (commentSection) { commentSection.style.display = 'block'; commentSection.scrollIntoView({ behavior: 'smooth' }); } }); }); // Like from stats document.querySelectorAll('.like-btn').forEach(button => { button.addEventListener('click', function() { const postId = this.dataset.postId; likePost(postId); }); }); }); // AJAX Like Function function likePost(postId) { fetch(/posts/${postId}/like, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, credentials: 'same-origin' }) .then(response => { if (!response.ok) { throw new Error('Network response was not ok'); } return response.json(); }) .then(data => { const likeBtn = document.querySelector(.like-action-btn[data-post-id="${postId}"]); const likeText = document.querySelector(.like-text-${postId}); const likeCount = document.querySelector(.like-count-${postId}); if (likeBtn && likeText && likeCount) { if (data.liked) { likeBtn.classList.add('text-primary'); likeText.textContent = 'Liked'; } else { likeBtn.classList.remove('text-primary'); likeText.textContent = 'Like'; } likeCount.textContent = data.likes_count; } }) .catch(error => { console.error('Error:', error); // Fallback to page reload if AJAX fails window.location.reload(); }); } 
    </script>
    @endpush @endsection
