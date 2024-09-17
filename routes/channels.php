<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;


// Pour broadcast pusher sans laravel notification
Broadcast::channel('user.{userId}', function (User $user, Int $userId) {
    return (int) $user->id === $userId ;
});


// Broadcast Pusher avec Laravel Notification
Broadcast::channel('App.Models.User.{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
});