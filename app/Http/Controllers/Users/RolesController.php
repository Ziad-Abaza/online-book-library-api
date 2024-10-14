<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\RoleResource;

class RolesController extends Controller
{
    public function index()
    {
        try {
            $query = request()->input('search');
            $roles = Role::when($query, function ($queryBuilder) use ($query) {
                return $queryBuilder->where('name', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%');
            })->get(); 

            return RoleResource::collection($roles);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve roles', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:roles,name',
                'description' => 'nullable|string|max:255',
                'permission_ids' => 'required|array', 
                'permission_ids.*' => 'exists:permissions,id' 
            ]);

            $role = Role::create($validated);
            
            if ($request->has('permission_ids')) {
                $role->permissions()->sync($request->permission_ids);
            }

            return new RoleResource($role);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create role', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $role = Role::with('permissions')->findOrFail($id); 
            return new RoleResource($role);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Role not found'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve role', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $role = Role::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255|unique:roles,name,' . $id,
                'description' => 'sometimes|nullable|string|max:255',
                'permission_ids' => 'nullable|array', 
                'permission_ids.*' => 'exists:permissions,id' 
            ]);

            $role->update($validated);

            if ($request->has('permission_ids')) {
                $role->permissions()->sync($request->permission_ids);
            }

            return new RoleResource($role);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Role not found'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update role', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->permissions()->detach();
            
            $role->delete();
            return response()->json(['message' => 'Role deleted successfully'], Response::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Role not found'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete role', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
