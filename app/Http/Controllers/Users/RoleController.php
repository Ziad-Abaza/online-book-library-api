<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\HasPermissions;
use App\Http\Resources\RoleResource; 

class RoleController extends Controller
{
    use HasRoles, HasPermissions;

    /*
    |-------------------------------------------------------------------------- 
    | Constructor Method
    |-------------------------------------------------------------------------- 
    */
    public function __construct()
    {
        // $this->authorizePermissions('role');
    }

    /*
    |-------------------------------------------------------------------------- 
    | Display a listing of roles.
    |-------------------------------------------------------------------------- 
    */
    public function index()
    {
        try {
            $roles = Role::orderBy('id', 'DESC')->paginate(3);
            return RoleResource::collection($roles); 
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch roles. Please try again later.'], 500);
        }
    }

    /*
    |-------------------------------------------------------------------------- 
    | Show the form for creating a new role.
    |-------------------------------------------------------------------------- 
    */
    public function create()
    {
        try {
            $permissions = Permission::all();
            return response()->json($permissions);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to load permissions. Please try again later.'], 500);
        }
    }

    /*
    |-------------------------------------------------------------------------- 
    | Store a newly created role in the database.
    |-------------------------------------------------------------------------- 
    */
    public function store(StoreRoleRequest $request)
    {
        try {
            $role = Role::create(['name' => $request->name]);

            $permissions = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();
            $role->syncPermissions($permissions);

            return response()->json(['message' => 'New role is added successfully.'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to add role. Please try again later.'], 500);
        }
    }

    /*
    |-------------------------------------------------------------------------- 
    | Display the specified role.
    |-------------------------------------------------------------------------- 
    */
    public function show(Role $role)
    {
        try {
            return new RoleResource($role);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch role details. Please try again later.'], 500);
        }
    }

    /*
    |-------------------------------------------------------------------------- 
    | Show the form for editing the specified role.
    |-------------------------------------------------------------------------- 
    */
    public function edit(Role $role)
    {
        if ($role->name === 'Super Admin') {
            return response()->json(['error' => 'SUPER ADMIN ROLE CAN NOT BE EDITED'], 403);
        }

        $rolePermissions = DB::table("role_has_permissions")->where("role_id", $role->id)->pluck('permission_id')->all();

        try {
            $permissions = Permission::all();
            return response()->json(['role' => $role, 'permissions' => $permissions, 'rolePermissions' => $rolePermissions]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to load role data. Please try again later.'], 500);
        }
    }

    /*
    |-------------------------------------------------------------------------- 
    | Update the specified role in the database.
    |-------------------------------------------------------------------------- 
    */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        if ($role->name === 'Super Admin') {
            return response()->json(['error' => 'SUPER ADMIN ROLE CAN NOT BE UPDATED'], 403);
        }

        try {
            $role->update($request->only('name'));

            $permissions = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();
            $role->syncPermissions($permissions);

            return response()->json(['message' => 'Role is updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to update role. Please try again later.'], 500);
        }
    }

    /*
    |-------------------------------------------------------------------------- 
    | Remove the specified role from the database.
    |-------------------------------------------------------------------------- 
    */
    public function destroy(Role $role)
    {
        if ($role->name === 'Super Admin') {
            return response()->json(['error' => 'SUPER ADMIN ROLE CAN NOT BE DELETED'], 403);
        }

        if (auth()->user()->hasRole($role->name)) {
            return response()->json(['error' => 'CAN NOT DELETE SELF ASSIGNED ROLE'], 403);
        }

        try {
            $role->delete();
            return response()->json(['message' => 'Role is deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to delete role. Please try again later.'], 500);
        }
    }
}
