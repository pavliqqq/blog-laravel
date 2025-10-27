<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Comment\CommentController;
use App\Http\Controllers\API\Post\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
Route::post('/posts', [PostController::class, 'store'])->middleware(['auth:sanctum'])->name('posts.store');
Route::put('/posts/{id}', [PostController::class, 'update'])->middleware(['auth:sanctum'])->name('posts.update');
Route::delete('/posts/{id}', [PostController::class, 'destroy'])->middleware(['auth:sanctum'])->name('posts.destroy');

Route::get('/posts/{id}/comments', [CommentController::class, 'index'])->name('comments.index');
Route::post('/posts/{id}/comments', [CommentController::class, 'store'])->middleware(['auth:sanctum'])->name('comments.store');
Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->middleware(['auth:sanctum'])->name('comments.destroy');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);
