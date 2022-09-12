<?php

namespace App\Http\Controllers\MyPage;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostManageController extends Controller
{
    public function index()
    {
        $posts = Post::where('user_id', Auth::id())->get();
        return view('mypage.posts.index', compact('posts'));
    }
}
