<?php

namespace App\Policies;

use App\Eloquent\User;
use App\Eloquent\Project;

class ProjectPolicy extends AbstractPolicy
{
    public function read(User $user, Project $ability)
    {
        return true;
    }

    public function update(User $user, Project $ability)
    {
        if (! $ability->where('user_id', $user->id)->count()) {
            return false;
        }

        return true;
    }

    public function delete(User $user, Project $ability)
    {
        if (! $ability->where('user_id', $user->id)->count()) {
            return false;
        }

        return true;
    }
}
