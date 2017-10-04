<?php

namespace App\Policies;

use App\Eloquent\User;
use App\Eloquent\Endpoint;

class EndpointPolicy extends AbstractPolicy
{
    public function read(User $user, Endpoint $ability)
    {
        return true;
    }

    public function update(User $user, Endpoint $ability)
    {
        if (! $ability->project()->where('user_id', $user->id)->count()) {
            return false;
        }

        return true;
    }

    public function delete(User $user, Endpoint $ability)
    {
        if (! $ability->project()->where('user_id', $user->id)->count()) {
            return false;
        }

        return true;
    }
}
