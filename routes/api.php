<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\RolesController;
use App\Http\Controllers\Users\UserController;


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

    /*
    |------------------------------------------------------------------
    | -  User Routes
    |------------------------------------------------------------------
     */
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->middleware('permission:view-users');
        Route::get('/{user}', [UserController::class, 'show'])->middleware('permission:view-users');
        Route::post('/', [UserController::class, 'store'])->middleware('permission:create-user');
        Route::post('/{user}', [UserController::class, 'update'])->middleware('permission:edit-user');
        Route::delete('/{user}', [UserController::class, 'destroy'])->middleware('permission:delete-user');
    });

    /*
    |------------------------------------------------------------------
    | -  User Routes
    |------------------------------------------------------------------
     */
    Route::prefix('roles')->group(function () {
        Route::get('/', [RolesController::class, 'index'])->middleware('permission:view-roles');
        Route::get('/{role}', [RolesController::class, 'show'])->middleware('permission:view-roles');
        Route::post('/', [RolesController::class, 'store'])->middleware('permission:create-role');
        Route::post('/{role}', [RolesController::class, 'update'])->middleware('permission:edit-role');
        Route::delete('/{role}', [RolesController::class, 'destroy'])->middleware('permission:delete-role');
    });


require __DIR__.'/site.php';
require __DIR__.'/auth.php';
