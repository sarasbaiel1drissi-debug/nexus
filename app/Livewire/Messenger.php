<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Services\TaskAutoCompleteService;
use Illuminate\Support\Facades\Auth;

class Messenger extends Component
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
                $conv = Conversation::where(function ($q) use ($otherUser) {
                    $q->where('user_one_id', auth()->id())->where('user_two_id', $otherUser->id);
                })->orWhere(function ($q) use ($otherUser) {
                    $q->where('user_one_id', $otherUser->id)->where('user_two_id', auth()->id());
                })->first();

                if (!$conv) {
                    $conv = Conversation::create([
                        'user_one_id' => auth()->id(),
                        'user_two_id' => $otherUser->id,
                    ]);
                }

                $this->activeConversationId = $conv->id;
            }
        }
    }

    public function selectConversation($id)
    {
        $this->activeConversationId = $id;
        $this->markAsRead();
    }

    public function sendMessage()
    {
        if (trim($this->newMessage) == '' || !$this->activeConversationId)
            return;

        $message = Message::create([
            'conversation_id' => $this->activeConversationId,
            'sender_id' => auth()->id(),
            'body' => $this->newMessage,
        ]);

        $message->conversation()->touch();

        // Auto-complete message tasks
        app(TaskAutoCompleteService::class)->check(auth()->user(), 'message');

        $this->newMessage = '';
    }

    /**
     * Mark all messages in the active conversation as read (for the current user).
     */
    private function markAsRead()
    {
        if (!$this->activeConversationId)
            return;

        Message::where('conversation_id', $this->activeConversationId)
            ->where('sender_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function render()
    {
        // Mark messages as read on every render (including poll)
        $this->markAsRead();

        $conversations = Conversation::where('user_one_id', auth()->id())
            ->orWhere('user_two_id', auth()->id())
            ->with([
                'userOne',
                'userTwo',
                'messages' => function ($q) {
                    $q->latest()->limit(1);
                }
            ])
            ->get();

        // Calculate unread count per conversation
        $unreadCounts = [];
        foreach ($conversations as $conv) {
            $unreadCounts[$conv->id] = Message::where('conversation_id', $conv->id)
                ->where('sender_id', '!=', auth()->id())
                ->whereNull('read_at')
                ->count();
        }

        $messages = collect();
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

        return view('livewire.messenger', [
            'conversations' => $conversations,
            'messages' => $messages,
            'activeUser' => $activeUser,
            'unreadCounts' => $unreadCounts,
        ]);
    }
}
