<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\RoleController;
use App\Http\Controllers\Users\UserController;


// Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    /*
    |------------------------------------------------------------------
    | -  User Routes
    |------------------------------------------------------------------
     */
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{user}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{user}', [UserController::class, 'update']);
        Route::delete('/{user}', [UserController::class, 'destroy']);
    });
    /*
    |------------------------------------------------------------------
    |  -  Role Routes
    |------------------------------------------------------------------
     */
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/{role}', [RoleController::class, 'show'])->name('roles.show');
        Route::post('/', [RoleController::class, 'store'])->name('roles.store');
        Route::put('/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    });
// });

require __DIR__.'/site.php';
require __DIR__.'/auth.php';
