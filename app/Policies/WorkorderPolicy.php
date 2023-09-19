<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Workorder;
use Illuminate\Auth\Access\Response;

class WorkorderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('workorderView') || $user->id == 1;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Workorder $workorder)
    {
        return $user->hasPermissionTo('workorderView') || $user->id == 1;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('workorderCreate') || $user->id == 1;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Workorder $workorder)
    {
        return $user->hasPermissionTo('workorderEdit') || $user->id == 1;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Workorder $workorder)
    {
        return $user->hasPermissionTo('workorderDelete') || $user->id == 1;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Workorder $workorder)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Workorder $workorder)
    {
        //
    }
}
