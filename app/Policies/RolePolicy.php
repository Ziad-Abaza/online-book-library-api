<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;

class RolePolicy
{
    /**
     * Determine whether the user can view any roles.
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('view-roles'); 
    }

    /**
     * Determine whether the user can view the role.
     */
    public function view(User $user, Role $role)
    {
        return $user->hasPermission('view-roles');
    }

    /**
     * Determine whether the user can create roles.
     */
    public function create(User $user)
    {
        return $user->hasPermission('create-role');
    }

    /**
     * Determine whether the user can update the role.
     */
    public function update(User $user, Role $role)
    {
        return $user->hasPermission('edit-role');
    }

    /**
     * Determine whether the user can delete the role.
     */
    public function delete(User $user, Role $role)
    {
        return $user->hasPermission('delete-role');
    }
}
