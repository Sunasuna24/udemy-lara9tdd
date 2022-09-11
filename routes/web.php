<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\SignUpController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PostController::class, 'index']);

Route::get('/signup', [SignUpController::class, 'index']);
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show')->whereNumber('post');