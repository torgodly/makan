<?php

namespace App\Policies;

use App\Models\User;

class OrderPolicy
{
    /**
     * Determine if the given user can view any orders.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine if the given user can view the order.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function view(User $user)
    {
        return true;
    }

    /**
     * Determine if the given user can create an order.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine if the given user can update the order.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function update(User $user)
    {
        return true;
    }

    /**
     * Determine if the given user can delete the order.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function delete(User $user)
    {
        return $user->isAdmin;
    }

    /**
     * Determine if the given user can restore the order.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function restore(User $user)
    {
        return $user->isAdmin;
    }

    /**
     * Determine if the given user can permanently delete the order.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function forceDelete(User $user)
    {
        return $user->isAdmin;
    }
}
