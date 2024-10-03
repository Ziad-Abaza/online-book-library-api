<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasPermissions
{

    public function authorizePermissions(string $permissionClass, callable $permissionsCallback = null)
    {
        $this->middleware('auth');

        $permissions = $this->getDefaultPermissions($permissionClass);
        
        if ($permissionsCallback) {
            $permissions = $permissionsCallback($permissions);
        }

        foreach ($permissions as $action => $permission) {
            $this->middleware("permission:{$permission}", ['only' => [$action]]);
        }
    }

    protected function getDefaultPermissions(string $permissionClass): array
    {
        return [
            'index' => 'create-' . $permissionClass . '|edit-' . $permissionClass . '|delete-' . $permissionClass,
            'show' => 'create-' . $permissionClass . '|edit-' . $permissionClass . '|delete-' . $permissionClass,
            'create' => 'create-' . $permissionClass,
            'store' => 'create-' . $permissionClass,
            'edit' => 'edit-' . $permissionClass,
            'update' => 'edit-' . $permissionClass,
            'destroy' => 'delete-' . $permissionClass,
        ];
    }
}

/*
*************************************************************
* Example 1 
*************************************************************
    $this->authorizePermissions('user', function($permissions) {
        $permissions['index'] = 'view-user';
        return $permissions;
    });
*************************************************************
* Example 2
*************************************************************
$this->authorizePermissions('user');

*/