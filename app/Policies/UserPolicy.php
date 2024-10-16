<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function view(User $user, User $model)
    {
        if ($user->id === $model->id) {
            return true;
        }

        if ($user->getRoleLevel() > $model->getRoleLevel()) {
            return true;
        }

        return $user->hasPermission('view-users');
    }

    public function viewAny(User $user)
    {
        return $user->hasPermission('view-users');
    }

    public function update(User $user, User $model)
    {
        if ($model->hasRole('superAdmin')) {
            return false;
        }

        if ($user->id === $model->id) {
            return false;
        }

        if ($user->getRoleLevel() <= $model->getRoleLevel()) {
            return false;
        }

        return $user->hasPermission('edit-user');
    }

    public function create(User $user)
    {
        return $user->hasPermission('create-user');
    }

    public function delete(User $user, User $model)
    {
        if ($model->hasRole('superAdmin')) {
            return false;
        }

        if ($user->id === $model->id) {
            return false;
        }

        if ($user->getRoleLevel() <= $model->getRoleLevel()) {
            return false;
        }

        return $user->hasPermission('delete-user');
    }
}
