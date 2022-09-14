<?php

use App\Http\Controllers\MyPage\PostManageController;
use App\Http\Controllers\MyPage\UserLoginController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SignUpController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PostController::class, 'index']);

Route::get('/signup', [SignUpController::class, 'index'])->name('signup');
Route::post('/signup', [SignUpController::class, 'store']);

Route::get('/mypage/login', [UserLoginController::class, 'index'])->name('login');
Route::post('/mypage/login', [UserLoginController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::post('/mypage/logout', [UserLoginController::class, 'logout'])->name('logout');
    Route::get('/mypage/posts', [PostManageController::class, 'index'])->name('mypage.posts');
    Route::get('/mypage/post/create', [PostManageController::class, 'create']);
    Route::post('/mypage/post/create', [PostManageController::class, 'store']);
    Route::get('/mypage/post/edit/{post}', [PostManageController::class, 'edit'])->name('mypage.post.edit');
});

Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show')->whereNumber('post');