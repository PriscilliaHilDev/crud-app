<?php

namespace App\Notifications;

use App\Models\Comment;
// use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommentNotification extends Notification
{
    // use Queueable;

    protected $comment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['database']; // Envoi dans la base de données et via broadcast (Pusher)
    }

    /**
     * Get the array representation of the notification for the database.
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'Nouveau commentaire sur votre post',
            'comment_id' => $this->comment->id,
            'content' => $this->comment->content,
            'author' => $this->comment->user->name,
            'post' => $this->comment->post,
            'post_title' => $this->comment->post->title,
            'createdAt' => $this->comment->created_at,
        ];
    }

}
