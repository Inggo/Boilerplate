<?php

namespace Inggo\Boilerplate\Policies;

use Inggo\Boilerplate\User;
use Inggo\Boilerplate\Activity;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->isAdmin();
    }
}
