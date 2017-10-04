<?php

namespace App\Policies;

use App\Eloquent\User;
use App\Eloquent\Field;

class FieldPolicy extends AbstractPolicy
{
    public function read(User $user, Field $ability)
    {
        return true;
    }

    public function update(User $user, Field $ability)
    {
        if (! $ability->endpoint()->project()->where('user_id', $user->id)->count()) {
            return false;
        }

        return true;
    }

    public function delete(User $user, Field $ability)
    {
        if (! $ability->endpoint()->project()->where('user_id', $user->id)->count()) {
            return false;
        }

        return true;
    }
}
