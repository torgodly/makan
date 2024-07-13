<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the given user can view any customers.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return $user->is_admin;
    }

    /**
     * Determine if the given user can view the customer.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->is_admin;
    }

    /**
     * Determine if the given user can create a customer.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->is_admin;
    }

    /**
     * Determine if the given user can update the customer.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function update(User $user)
    {
        return $user->is_admin;
    }

    /**
     * Determine if the given user can delete the customer.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function delete(User $user)
    {
        return $user->is_admin;
    }

    /**
     * Determine if the given user can restore the customer.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function restore(User $user)
    {
        return $user->is_admin;
    }

    /**
     * Determine if the given user can permanently delete the customer.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function forceDelete(User $user)
    {
        return $user->is_admin;
    }
}
