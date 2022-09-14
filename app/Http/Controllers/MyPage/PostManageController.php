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
        // $posts = Post::where('user_id', Auth::id())->get();
        $posts = auth()->user()->posts;
        return view('mypage.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('mypage.posts.create');
    }

    public function store(Request $request)
    {
        $post = Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'status' => $request->status,
            'user_id' => Auth::id()
        ]);

        return redirect('mypage/post/edit'.$post->id);
    }
}
