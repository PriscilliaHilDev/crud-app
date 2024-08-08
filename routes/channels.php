<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user.{userId}', function (User $user, Int $userId) {
    return (int) $user->id === $userId ;
});