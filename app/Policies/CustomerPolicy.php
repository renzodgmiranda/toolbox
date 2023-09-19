<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CustomerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('customerView') || $user->id == 1;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Customer $customer)
    {
        return $user->hasPermissionTo('customerView') || $user->id == 1;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('customerCreate') || $user->id == 1;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Customer $customer)
    {
        return $user->hasPermissionTo('customerEdit') || $user->id == 1;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Customer $customer)
    {
        return $user->hasPermissionTo('customerDelete') || $user->id == 1;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Customer $customer)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Customer $customer)
    {
        //
    }
}
