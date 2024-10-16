<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
       public function __construct()
    {
        Auth::loginUsingId(1); 
        
        $this->authorizeResource(User::class, 'user');
    }
    
    public function index(Request $request)
    {
        try {
            $query = User::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        }

    $users = $query->get();
    return UserResource::collection($users);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve users.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(User $user)
    {
        try {
            return new UserResource($user);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found.'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve the user.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

   public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:8',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
                'role_ids' => 'nullable|array',
                'role_ids.*' => 'integer|exists:roles,id',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            if ($request->has('role_ids')) {
                $user->roles()->attach($validated['role_ids']);
            }

            if ($request->hasFile('image')) {
                $user->addMediaFromRequest('image')->toMediaCollection('images');
            } else {
                $user->addMediaFromUrl('http://127.0.0.1:8000/assets/images/static/person.png')
                    ->toMediaCollection('images');
            }

            return response()->json(['message' => 'User created successfully']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create user.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|max:255|unique:users,email,' . $user->id,
                'password' => 'sometimes|required|string|min:8',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
                'role_ids' => 'nullable|array',
                'role_ids.*' => 'integer|exists:roles,id',
            ]);

            $data = $validated;
            if (isset($validated['password'])) {
                $data['password'] = Hash::make($validated['password']);
            }

            $user->update($data);

            if ($request->has('role_ids')) {
                $user->roles()->sync($validated['role_ids']);
            }

            if ($request->hasFile('image')) {
                $user->clearMediaCollection('images');
                $user->addMediaFromRequest('image')->toMediaCollection('images');
            }

            return new UserResource($user);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found.'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update user.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->clearMediaCollection('images'); 
            $user->delete();
            return response()->json(['message' => 'User deleted successfully'], Response::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found.'], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete user.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
