<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Books\BookController;
use App\Http\Controllers\Books\CategoryController;
use App\Http\Controllers\Books\CommentController;
use App\Http\Controllers\Books\DownloadController;

/*
|------------------------------------------------------------------
|  -  Books Routes
|------------------------------------------------------------------
*/
Route::prefix('books')->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('books.index');          
    Route::get('/{book}', [BookController::class, 'show'])->name('books.show');       
    Route::post('/', [BookController::class, 'store'])->name('books.store');          
    Route::put('/{book}', [BookController::class, 'update'])->name('books.update');   
    Route::delete('/{book}', [BookController::class, 'destroy'])->name('books.destroy'); 
});

/*
|------------------------------------------------------------------
|  -  categories Routes
|------------------------------------------------------------------
*/
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/{category}', [CategoryController::class, 'show'])->name('categories.show');
    Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

/*
|------------------------------------------------------------------
|  -  comments Routes
|------------------------------------------------------------------
*/
Route::prefix('comments')->group(function () {
    Route::get('/', [CommentController::class, 'index']);
    Route::get('/{comment}', [CommentController::class, 'show']);
    Route::post('/', [CommentController::class, 'store']);
    Route::put('/{comment}', [CommentController::class, 'update']);
    Route::delete('/{comment}', [CommentController::class, 'destroy']);
});

/*
|------------------------------------------------------------------
|  -  downloads Routes
|------------------------------------------------------------------
*/
Route::prefix('downloads')->group(function () {
    Route::get('/', [DownloadController::class, 'index']);
    Route::post('/', [DownloadController::class, 'store']);
    Route::get('/{download}', [DownloadController::class, 'show']);
    Route::put('/{download}', [DownloadController::class, 'update']);
    Route::delete('/{download}', [DownloadController::class, 'destroy']);
});