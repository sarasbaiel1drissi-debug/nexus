<div class="d-flex rounded-xxl shadow-xss overflow-hidden" style="height:calc(100vh - 200px);">
    {{-- Left Pane: Conversations list --}}
    <div class="border-end overflow-auto" style="width:35%;">
        <div class="p-3 border-bottom fw-700 font-xss text-grey-900">
            Conversations
        </div>

        <ul class="list-unstyled mb-0">
            @forelse($conversations as $conv)
                @php
                    $other = $conv->user_one_id === auth()->id() ? $conv->userTwo : $conv->userOne;
                    $isActive = $conv->id === $activeConversationId;
                    $lastMessage = $conv->messages->first();
                    $unread = ($unreadCounts[$conv->id] ?? 0) > 0;
                @endphp
                <li wire:click="selectConversation({{ $conv->id }})"
                    class="p-3 border-bottom {{ $isActive ? 'bg-greylight' : '' }}"
                    style="cursor:pointer;"
                    onmouseover="this.style.backgroundColor='var(--dark-bg-hover, #f5f5f5)'"
                    onmouseout="this.style.backgroundColor='{{ $isActive ? 'var(--dark-bg-tertiary, #f1f1f1)' : '' }}'">
                    <div class="d-flex align-items-center">
                        <div class="position-relative">
                            <img src="{{ $other->profile_picture_url }}" alt="{{ $other->name }}" class="rounded-circle me-3" style="width:40px;height:40px;object-fit:cover;">
                            @if($other->is_online)
                            <span class="position-absolute rounded-circle bg-success" style="width:8px;height:8px;bottom:2px;right:14px;border:1px solid #fff;"></span>
                            @endif
                        </div>
                        <div class="overflow-hidden flex-grow-1">
                            <h5 class="{{ $unread ? 'fw-700' : 'fw-600' }} font-xssss text-grey-900 mb-0 text-truncate">{{ $other->name }}</h5>
                            <p class="{{ $unread ? 'fw-700 text-grey-900' : 'fw-400 text-grey-500' }} font-xssss mb-0 text-truncate">{{ $lastMessage ? $lastMessage->body : 'Say hello!' }}</p>
                        </div>
                        @if($unread)
                        <span class="bg-danger text-white rounded-circle font-xsssss fw-700 d-flex align-items-center justify-content-center ms-2" style="width:18px;height:18px;min-width:18px;">{{ $unreadCounts[$conv->id] }}</span>
                        @endif
                    </div>
                </li>
            @empty
                <li class="p-4 text-center font-xssss text-grey-500">No conversations yet.</li>
            @endforelse
        </ul>
    </div>

    {{-- Right Pane: Chat History --}}
    <div class="d-flex flex-column" style="width:65%;">
        @if($activeConversationId && $activeUser)
            {{-- Chat Header --}}
            <div class="p-3 border-bottom d-flex align-items-center shadow-xs">
                <img src="{{ $activeUser->profile_picture_url }}" class="rounded-circle me-3" style="width:40px;height:40px;object-fit:cover;">
                <div>
                    <h4 class="fw-700 font-xssss text-grey-900 mb-0">{{ $activeUser->name }}</h4>
                    <a href="{{ route('user.profile', $activeUser->username) }}" class="font-xssss text-current">&#64;{{ $activeUser->username }}</a>
                </div>
            </div>

            {{-- Messages List --}}
            <div class="flex-grow-1 overflow-auto p-3" wire:poll.3s>
                @forelse($messages as $msg)
                    @php
                        $isMe = $msg->sender_id === auth()->id();
                    @endphp
                    <div class="d-flex mb-3 {{ $isMe ? 'justify-content-end' : 'justify-content-start' }}">
                        <div class="p-3 rounded-xxl {{ $isMe ? 'text-white' : 'bg-white shadow-xss text-grey-900' }}" style="max-width:70%;{{ $isMe ? 'background:var(--theme-color);' : '' }}">
                            <p class="font-xssss mb-1">{{ $msg->body }}</p>
                            <span class="d-block text-end {{ $isMe ? 'text-white-50' : 'text-grey-500' }}" style="font-size:10px;">{{ $msg->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="d-flex align-items-center justify-content-center h-100 text-grey-500 font-xssss">
                        <p>No messages yet. Start the conversation!</p>
                    </div>
                @endforelse
            </div>

            {{-- Input Area --}}
            <div class="p-3 border-top">
                <form wire:submit="sendMessage" class="d-flex gap-2">
                    <input type="text" wire:model="newMessage" placeholder="Type your message..." class="form-control rounded-xxl border-light font-xssss flex-grow-1">
                    <button type="submit" class="btn bg-current text-white rounded-xxl font-xssss fw-600 px-4">
                        Send
                    </button>
                </form>
            </div>
        @else
            {{-- Empty State --}}
            <div class="d-flex align-items-center justify-content-center h-100 text-grey-500">
                <p class="font-xss">Select a conversation or start a new one.</p>
            </div>
        @endif
    </div>
</div>
