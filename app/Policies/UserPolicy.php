<?php

namespace Inggo\Boilerplate\Policies;

use Inggo\Boilerplate\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Inggo\Boilerplate\User  $user
     * @param  \Inggo\Boilerplate\User  $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        return $user->id === $model->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Inggo\Boilerplate\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Inggo\Boilerplate\User  $user
     * @param  \Inggo\Boilerplate\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        // Allow self edits
        if ($user->id === $model->id) {
            return true;
        }

        // Other permissions are the same as delete
        return $this->delete($user, $model);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Inggo\Boilerplate\User  $user
     * @param  \Inggo\Boilerplate\User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        // Owners cannot be edited/deleted
        if ($model->isOwner()) {
            return false;
        }

        // Owners can edit/delete other non-owners
        if ($user->isOwner()) {
            return true;
        }

        // Admins cannot be edited/deleted by other admins
        if ($model->isAdmin()) {
            return false;
        }

        // Admins can edit/delete other users
        if ($user->isAdmin()) {
            return true;
        }

        // Managers below can't edit/delete other users
        return false;
    }
}
