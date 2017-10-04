<?php

namespace App\Policies;

use App\Eloquent\User;
use Illuminate\Auth\Access\HandlesAuthorization;

abstract class AbstractPolicy
{
    use HandlesAuthorization;
}
