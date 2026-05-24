<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Services\TaskAutoCompleteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatController extends Controller
{
    public function index(?int $userId = null)
    {
        return view('messages', ['userId' => $userId]);
    }

    public function conversations()
    {
        $conversations = Conversation::where('user_one_id', Auth::id())
            ->orWhere('user_two_id', Auth::id())
            ->with(['userOne', 'userTwo', 'messages' => fn ($query) => $query->latest()->limit(1)])
            ->latest('updated_at')
            ->get()
            ->map(fn (Conversation $conversation) => $this->conversationPayload($conversation));

        return response()->json($conversations);
    }

    public function open(User $user)
    {
        abort_if($user->is(Auth::user()), 404);

        $conversation = Conversation::where(function ($query) use ($user) {
            $query->where('user_one_id', Auth::id())->where('user_two_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('user_one_id', $user->id)->where('user_two_id', Auth::id());
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one_id' => Auth::id(),
                'user_two_id' => $user->id,
            ]);
        }

        $this->markAsRead($conversation);

        return response()->json($this->conversationPayload($conversation->load(['userOne', 'userTwo'])));
    }

    public function messages(Request $request, Conversation $conversation)
    {
        $this->authorizeConversation($conversation);
        $this->markAsRead($conversation);

        $messagesQuery = $conversation->messages()
            ->with('sender')
            ->oldest();

        if ($request->filled('after_id')) {
            $messagesQuery->where('id', '>', (int) $request->query('after_id'));
        }

        $messages = $messagesQuery->get()
            ->map(fn (Message $message) => $this->messagePayload($message));

        return response()->json($messages);
    }

    public function send(Request $request, Conversation $conversation)
    {
        $this->authorizeConversation($conversation);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
            'client_id' => ['nullable', 'string', 'max:100'],
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'body' => $validated['body'],
        ]);

        $conversation->touch();
        $user = Auth::user();
        defer(fn () => app(TaskAutoCompleteService::class)->check($user, 'message'));

        try {
            broadcast(new MessageSent($message->load('sender'), $validated['client_id'] ?? null))->toOthers();
        } catch (\Throwable $exception) {
            Log::warning('Message broadcast failed', [
                'conversation_id' => $conversation->id,
                'message_id' => $message->id,
                'error' => $exception->getMessage(),
            ]);
        }

        return response()->json([
            ...$this->messagePayload($message->load('sender')),
            'client_id' => $validated['client_id'] ?? null,
        ], 201);
    }

    public function read(Conversation $conversation)
    {
        $this->authorizeConversation($conversation);
        $this->markAsRead($conversation);

        return response()->json([
            'conversation_id' => $conversation->id,
            'unread_count' => 0,
            'total_unread' => Auth::user()->unread_message_count,
        ]);
    }

    public function stream(Request $request, Conversation $conversation): StreamedResponse
    {
        $this->authorizeConversation($conversation);

        return response()->stream(function () use ($request, $conversation) {
            $lastId = (int) $request->query('last_id', 0);
            $startedAt = time();

            while (!connection_aborted() && time() - $startedAt < 60) {
                $messages = $conversation->messages()
                    ->with('sender')
                    ->where('id', '>', $lastId)
                    ->oldest()
                    ->get();

                foreach ($messages as $message) {
                    $lastId = $message->id;

                    if ($message->sender_id !== Auth::id()) {
                        $message->forceFill(['read_at' => now()])->save();
                    }

                    echo 'event: message' . "\n";
                    echo 'data: ' . json_encode($this->messagePayload($message)) . "\n\n";
                }

                if ($messages->isEmpty()) {
                    echo ": keep-alive\n\n";
                }

                @ob_flush();
                flush();
                sleep(1);
            }
        }, 200, [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'text/event-stream',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    private function authorizeConversation(Conversation $conversation): void
    {
        abort_unless(
            $conversation->user_one_id === Auth::id() || $conversation->user_two_id === Auth::id(),
            403
        );
    }

    private function markAsRead(Conversation $conversation): void
    {
        $conversation->messages()
            ->where('sender_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    private function conversationPayload(Conversation $conversation): array
    {
        $other = $conversation->user_one_id === Auth::id()
            ? $conversation->userTwo
            : $conversation->userOne;

        $lastMessage = $conversation->messages->first();

        return [
            'id' => $conversation->id,
            'user' => [
                'id' => $other->id,
                'name' => $other->name,
                'username' => $other->username,
                'avatar' => $other->profile_picture_url,
                'is_online' => (bool) $other->is_online,
                'profile_url' => route('user.profile', $other->username),
            ],
            'last_message' => $lastMessage ? $this->messagePayload($lastMessage) : null,
            'unread_count' => $conversation->messages()
                ->where('sender_id', '!=', Auth::id())
                ->whereNull('read_at')
                ->count(),
        ];
    }

    private function messagePayload(Message $message): array
    {
        return [
            'id' => $message->id,
            'conversation_id' => $message->conversation_id,
            'sender_id' => $message->sender_id,
            'body' => $message->body,
            'created_at' => $message->created_at?->format('H:i'),
            'is_me' => $message->sender_id === Auth::id(),
            'sender' => [
                'id' => $message->sender?->id,
                'name' => $message->sender?->name,
                'username' => $message->sender?->username,
                'avatar' => $message->sender?->profile_picture_url,
            ],
        ];
    }
}
