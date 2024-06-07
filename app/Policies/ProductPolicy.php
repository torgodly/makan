<?php

namespace App\Policies;

use App\Models\User;

class ProductPolicy
{
    /**
     * Determine if the given user can view any products.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin;
    }

    /**
     * Determine if the given user can view the product.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->isAdmin;
    }

    /**
     * Determine if the given user can create a product.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->isAdmin;
    }

    /**
     * Determine if the given user can update the product.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function update(User $user)
    {
        return $user->isAdmin;
    }

    /**
     * Determine if the given user can delete the product.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function delete(User $user)
    {
        return $user->isAdmin;
    }

    /**
     * Determine if the given user can restore the product.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function restore(User $user)
    {
        return $user->isAdmin;
    }

    /**
     * Determine if the given user can permanently delete the product.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function forceDelete(User $user)
    {
        return $user->isAdmin;
    }
}
