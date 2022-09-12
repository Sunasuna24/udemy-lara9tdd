<?php

use App\Http\Controllers\MyPage\UserLoginController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SignUpController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PostController::class, 'index']);

Route::get('/signup', [SignUpController::class, 'index'])->name('signup');
Route::post('/signup', [SignUpController::class, 'store']);

Route::get('/mypage/login', [UserLoginController::class, 'index'])->name('login');
Route::post('/mypage/login', [UserLoginController::class, 'login']);

Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show')->whereNumber('post');