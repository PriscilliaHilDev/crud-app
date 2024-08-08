<?php

namespace App\Broadcasting;

use App\Models\User;
use App\Models\Comment;

class CommentUserChannell
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user, Comment $comment): array|bool
    {
        return $user->id === $comment->post->user_id;
    }
}
