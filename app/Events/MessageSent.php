<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $queue = 'broadcasts';

    /**
     * Create a new event instance.
     */
    public function __construct(public Message $message, public ?string $clientId = null)
    {
        $this->message->loadMissing('sender');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversations.' . $this->message->conversation_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'conversation_id' => $this->message->conversation_id,
                'sender_id' => $this->message->sender_id,
                'body' => $this->message->body,
                'created_at' => $this->message->created_at?->format('H:i'),
                'sender' => [
                    'id' => $this->message->sender?->id,
                    'name' => $this->message->sender?->name,
                    'username' => $this->message->sender?->username,
                    'profile_picture_url' => $this->message->sender?->profile_picture_url,
                ],
            ],
            'client_id' => $this->clientId,
        ];
    }
}
