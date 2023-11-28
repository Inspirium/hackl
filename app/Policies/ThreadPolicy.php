<?php

namespace App\Policies;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThreadPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the thread.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Thread  $thread
     * @return mixed
     */
    public function view(User $user, Thread $thread)
    {
        if ($thread->public) {
            return true;
        }
        if ($user->isSuperAdmin()) {
            return true;
        }
        $thread->load('players');

        return $thread->players->contains('id', $user->id);
    }

    /**
     * Determine whether the user can create threads.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return (bool) $user->id;
    }

    /**
     * Determine whether the user can update the thread.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Thread  $thread
     * @return mixed
     */
    public function update(User $user, Thread $thread)
    {
        if ($thread->public) {
            return true;
        }
        if ($user->isSuperAdmin()) {
            return true;
        }
        $thread->load('players');

        return $thread->players->contains('id', $user->id);
    }

    /**
     * Determine whether the user can delete the thread.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Thread  $thread
     * @return mixed
     */
    public function delete(User $user, Thread $thread)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return false;
        // $thread->load('players');

        // return $thread->players->contains('id', $user->id); //TODO: only owners should be able to delete
    }
}
