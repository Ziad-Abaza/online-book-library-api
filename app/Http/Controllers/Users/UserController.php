<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Traits\HasPermissions;
use App\Traits\CrudOperationsTrait;  
use App\Traits\HandleFile;          

class UserController extends Controller
{
    use HasPermissions, CrudOperationsTrait, HandleFile; 

    /*
    |-------------------------------------------------------------------------- 
    | Constructor Method
    |-------------------------------------------------------------------------- 
    */
    public function __construct()
    {
        $this->authorizePermissions('user');
    }

    /*
    |--------------------------------------------------------------------------
    | Validate Request Data Function
    |--------------------------------------------------------------------------
    */
    private function validator($request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'image' => 'nullable',
            'roles' => 'required|array',
            'is_active' => 'nullable|boolean',
        ];
        return $this->validateRequestData($request, $rules);
    }

    /*
    |-------------------------------------------------------------------------- 
    | Display a listing of users.
    |-------------------------------------------------------------------------- 
    */
    public function index()
    {
        try {
            $users = User::latest('id')->paginate(3);
            return UserResource::collection($users); 
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch users. Please try again later.'], 500);
        }
    }

    /*
    |-------------------------------------------------------------------------- 
    | Display the currently authenticated user.
    |-------------------------------------------------------------------------- 
    */
    public function show()
    {
        try {
            $user = auth()->user(); 
            return new UserResource($user);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch user data. Please try again later.'], 500);
        }
    }

    /*
    |-------------------------------------------------------------------------- 
    | Store a newly created user in the database.
    |-------------------------------------------------------------------------- 
    */
    public function store(StoreUserRequest $request)
    {
        $validationResult = $this->validator($request);
        if ($validationResult !== null) {
            return $validationResult;
        }

        $input = $request->all();
        $input['password'] = Hash::make($request->password);
        $input['token'] = null; 
        $input['token_expiration'] = null; 

        if ($request->hasFile('image')) {
            $input['image'] = $this->UploadFiles($request->file('image'), $request->name, 'image'); 
        }

        try {
            $user = $this->createRecord(new User, $input);
            $user->assignRole($request->roles);
            return new UserResource($user); 
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to add user. Please try again later.'], 500);
        }
    }

    /*
    |-------------------------------------------------------------------------- 
    | Update the specified user in the database.
    |-------------------------------------------------------------------------- 
    */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validationResult = $this->validator($request);
        if ($validationResult !== null) {
            return $validationResult;
        }

        $input = $request->all();

        if (!empty($request->password)) {
            $input['password'] = Hash::make($request->password);
        } else {
            $input = $request->except('password');
        }

        $input['image'] = $this->updateFile($request, 'image', $user->image, null, 'image'); 

        try {
            $this->updateRecord(new User, $user->id, $input);
            $user->syncRoles($request->roles);
            return new UserResource($user); 
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to update user. Please try again later.'], 500);
        }
    }

    /*
    |-------------------------------------------------------------------------- 
    | Remove the specified user from the database.
    |-------------------------------------------------------------------------- 
    */
    public function destroy(User $user)
    {
        if ($user->hasRole('Super Admin') || $user->id == auth()->user()->id) {
            return response()->json(['error' => 'USER DOES NOT HAVE THE RIGHT PERMISSIONS'], 403);
        }

        try {
            $user->syncRoles([]);
            $this->deleteRecord(new User, $user->id);
            return response()->json(['message' => 'User is deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to delete user. Please try again later.'], 500);
        }
    }
}
