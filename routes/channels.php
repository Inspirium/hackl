<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('result.{id}', function ($user, $id) {
    return true;
});

Broadcast::channel('classified.{id}', function ($user, $id) {
    return true;
});

Broadcast::channel('thread.{thread}', function ($user, App\Models\Thread $thread) {
    if ($thread->public) {
        return true;
    }

    return $thread->players->contains('id', $user->id);
});

Broadcast::channel('live.{id}', function ($user, $id) {
    return true;
});

Broadcast::channel('courts.{date}', function ($user, $date) {
    return true;
});

Broadcast::channel('courts.{club}.{date}', function ($user,$club, $date) {
    return true;
});


Broadcast::channel('league.{league}', function ($user, $league) {
    return true;
});
