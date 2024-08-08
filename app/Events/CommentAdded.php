<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Comment;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class CommentAdded implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;

    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->comment->post->user_id);
    }

    public function broadcastAs()
    {
        return 'comment-added';
    }

    public function broadcastWith()
    {
        return [
            'comment' => [
                'id' => $this->comment->id,
                'message' => $this->comment->content,
                'author' => $this->comment->user->name,
                'createdAt' => $this->comment->created_at,
                'post' => $this->comment->post
            ],
        ];
    }
}
