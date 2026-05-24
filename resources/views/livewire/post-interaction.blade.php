<div>
    <style>
        .post-action-button {
            min-height: 34px;
            gap: 6px;
        }

        .post-action-button.is-liked {
            color: #e11d48 !important;
        }

        .post-action-button.is-liked i {
            color: #e11d48 !important;
        }
    </style>

    {{-- Like & Comment Buttons --}}
    <div class="d-flex align-items-center pt-2 border-top-xs">
        <button type="button" wire:click="toggleLike" class="post-action-button d-flex align-items-center me-4 font-xssss fw-600 border-0 bg-transparent {{ $isLiked ? 'is-liked' : 'text-grey-700' }}" style="cursor:pointer;">
            <i class="feather-heart"></i>
            <span>{{ $likesCount }}</span>
        </button>
        <button type="button" wire:click="toggleComments" class="post-action-button d-flex align-items-center font-xssss fw-600 text-grey-700 border-0 bg-transparent" style="cursor:pointer;">
            <i class="feather-message-circle"></i>
            <span>{{ $commentsCount }}</span>
        </button>
    </div>

    {{-- Comments Section --}}
    @if($showComments)
    <div class="mt-3">
        {{-- Existing comments --}}
        @foreach($comments as $comment)
        <div class="d-flex mb-2 align-items-start">
            <img src="{{ $comment->user->profile_picture_url }}" class="rounded-circle me-2" style="width:30px;height:30px;object-fit:cover;">
            <div class="bg-greylight rounded-xxl p-2 flex-grow-1">
                <div class="d-flex align-items-center justify-content-between">
                    <a href="{{ route('user.profile', $comment->user->username) }}" class="fw-700 font-xssss text-grey-900">{{ $comment->user->name }}</a>
                    @if($comment->user_id === auth()->id() || $post->user_id === auth()->id())
                    <button wire:click="deleteComment({{ $comment->id }})" class="border-0 bg-transparent text-grey-500 font-xsssss" title="Delete" style="cursor:pointer;">
                        <i class="feather-x"></i>
                    </button>
                    @endif
                </div>
                <p class="font-xssss text-grey-600 mb-0">{{ $comment->content }}</p>
                <span style="font-size:10px;" class="text-grey-500">{{ $comment->created_at->diffForHumans() }}</span>
            </div>
        </div>
        @endforeach

        {{-- New comment form --}}
        <form wire:submit.prevent="addComment" class="d-flex mt-2 gap-2">
            <img src="{{ auth()->user()->profile_picture_url }}" class="rounded-circle" style="width:30px;height:30px;object-fit:cover;">
            <input type="text" wire:model="commentText" class="form-control form-control-sm rounded-pill border-light font-xssss flex-grow-1" placeholder="Write a comment...">
            <button type="submit" class="btn btn-sm bg-current text-white rounded-pill px-3 font-xssss">Post</button>
        </form>
        @error('commentText') <small class="text-danger font-xsssss">{{ $message }}</small> @enderror
    </div>
    @endif
</div>
