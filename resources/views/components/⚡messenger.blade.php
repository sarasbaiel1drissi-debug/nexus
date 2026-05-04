<?php

use Livewire\Volt\Component;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public $userId = null;
    public $activeConversationId = null;
    public $newMessage = '';
    
    public function mount($userId = null)
    {
        $this->userId = $userId;
        
        if ($this->userId) {
            $otherUser = User::find($this->userId);
            if ($otherUser && $otherUser->id != auth()->id()) {
                $conv = Conversation::where(function($q) use ($otherUser) {
                    $q->where('user_one_id', auth()->id())->where('user_two_id', $otherUser->id);
                })->orWhere(function($q) use ($otherUser) {
                    $q->where('user_one_id', $otherUser->id)->where('user_two_id', auth()->id());
                })->first();
                
                if (!$conv) {
                    $conv = Conversation::create([
                        'user_one_id' => auth()->id(),
                        'user_two_id' => $otherUser->id
                    ]);
                }
                
                $this->activeConversationId = $conv->id;
            }
        }
    }
    
    public function selectConversation($id)
    {
        $this->activeConversationId = $id;
    }
    
    public function sendMessage()
    {
        if (trim($this->newMessage) == '' || !$this->activeConversationId) return;
        
        Message::create([
            'conversation_id' => $this->activeConversationId,
            'sender_id' => auth()->id(),
            'body' => $this->newMessage,
        ]);
        
        $this->newMessage = '';
    }
    
    public function with(): array
    {
        $conversations = Conversation::where('user_one_id', auth()->id())
            ->orWhere('user_two_id', auth()->id())
            ->with(['userOne', 'userTwo', 'messages' => function($q) {
                $q->latest()->limit(1);
            }])
            ->get();
            
        $messages = [];
        $activeUser = null;
        
        if ($this->activeConversationId) {
            $messages = Message::where('conversation_id', $this->activeConversationId)
                ->with('sender')
                ->oldest()
                ->get();
                
            $activeConv = $conversations->firstWhere('id', $this->activeConversationId);
            if ($activeConv) {
                $activeUser = $activeConv->user_one_id == auth()->id() ? $activeConv->userTwo : $activeConv->userOne;
            }
        }
            
        return [
            'conversations' => $conversations,
            'messages' => $messages,
            'activeUser' => $activeUser,
        ];
    }
};
?>

<div class="flex h-[calc(100vh-200px)] bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <!-- Left Pane: Conversations list -->
    <div class="w-1/3 border-r border-gray-200 dark:border-gray-700 overflow-y-auto">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 font-bold text-lg">
            Conversations
        </div>
        
        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($conversations as $conv)
                @php
                    $other = $conv->user_one_id === auth()->id() ? $conv->userTwo : $conv->userOne;
                    $isActive = $conv->id === $activeConversationId;
                    $lastMessage = $conv->messages->first();
                @endphp
                <li wire:click="selectConversation({{ $conv->id }})" 
                    class="p-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 {{ $isActive ? 'bg-indigo-50 dark:bg-indigo-900/40' : '' }}">
                    <div class="flex items-center space-x-4">
                        <img src="{{ $other->profile_picture_url }}" alt="{{ $other->name }}" class="w-10 h-10 rounded-full object-cover">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                {{ $other->name }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                {{ $lastMessage ? $lastMessage->body : 'Say hello!' }}
                            </p>
                        </div>
                    </div>
                </li>
            @empty
                <li class="p-4 text-sm text-gray-500 text-center">No conversations yet.</li>
            @endforelse
        </ul>
    </div>

    <!-- Right Pane: Chat History -->
    <div class="w-2/3 flex flex-col bg-gray-50 dark:bg-gray-900">
        @if($activeConversationId && $activeUser)
            <!-- Chat Header -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 flex items-center shadow-sm">
                <img src="{{ $activeUser->profile_picture_url }}" class="w-10 h-10 rounded-full object-cover mr-3">
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-gray-100">{{ $activeUser->name }}</h3>
                    <a href="{{ route('user.profile', $activeUser->username) }}" class="text-xs text-indigo-500 hover:underline">@<span>{{ $activeUser->username }}</span></a>
                </div>
            </div>

            <!-- Messages List -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4" wire:poll.3s>
                @forelse($messages as $msg)
                    @php
                        $isMe = $msg->sender_id === auth()->id();
                    @endphp
                    <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[70%] rounded-lg p-3 {{ $isMe ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-bl-none shadow-sm' }}">
                            <p class="text-sm">{{ $msg->body }}</p>
                            <span class="text-[10px] {{ $isMe ? 'text-indigo-200' : 'text-gray-500' }} block text-right mt-1">{{ $msg->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="h-full flex items-center justify-center text-gray-500 dark:text-gray-400">
                        <p>No messages yet. Start the conversation!</p>
                    </div>
                @endforelse
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                <form wire:submit="sendMessage" class="flex space-x-2">
                    <input type="text" wire:model="newMessage" placeholder="Type your message..." class="flex-1 rounded-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-4">
                    <button type="submit" class="inline-flex items-center justify-center p-2 rounded-full bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                        <span>Send</span>
                    </button>
                </form>
            </div>
        @else
            <!-- Empty State -->
            <div class="flex-1 flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                <p class="text-lg">Select a conversation or start a new one.</p>
            </div>
        @endif
    </div>
</div>