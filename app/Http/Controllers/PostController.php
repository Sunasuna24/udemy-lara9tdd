<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::onlyPublished()->withCount('comments')->with('user')->orderByDesc('comments_count')->get();

        return view('index')->with('posts', $posts);
    }

    public function show(Post $post)
    {
        if ($post->isClosed()) abort(403);
        return view('posts.show')->with('post', $post);
    }
}
