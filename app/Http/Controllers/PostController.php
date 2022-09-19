<?php

namespace App\Http\Controllers;

use App\Actions\StrRandom;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::onlyPublished()->withCount('comments')->with('user')->orderByDesc('comments_count')->get();

        return view('index')->with('posts', $posts);
    }

    public function show(Post $post, StrRandom $str_random)
    {
        if ($post->isClosed()) {
            abort(403);
        }

        $random = $str_random->get(10);

        return view('posts.show', compact('post', 'random'));
    }
}
