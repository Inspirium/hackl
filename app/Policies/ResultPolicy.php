<?php

namespace App\Policies;

use App\Models\Result;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResultPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the result.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Result  $result
     * @return mixed
     */
    public function view(User $user, Result $result)
    {
        return true;
    }

    /**
     * Determine whether the user can create results.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return (bool) $user->id;
    }

    /**
     * Determine whether the user can update the result.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Result  $result
     * @return mixed
     */
    public function update(User $user, Result $result)
    {
        if ($user->isSuperAdmin()) {
            return true; //super user can do anything, skip the expensive load
        }
        $result->load('players');

        return $result->players->contains('id', $user->id);
    }

    /**
     * Determine whether the user can delete the result.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Result  $result
     * @return mixed
     */
    public function delete(User $user, Result $result)
    {
        if ($user->isSuperAdmin()) {
            return true; //super user can do anything, skip the expensive load
        }
        if ($result->verified) {
            return false; //if we have verified the result, skip the load
        }
        $result->load('players');

        return $result->players->contains('id', $user->id);
    }
}
