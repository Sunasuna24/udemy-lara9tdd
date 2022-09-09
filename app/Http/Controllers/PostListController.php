<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostListController extends Controller
{
    public function index()
    {
        $posts = Post::onlyPublished()->withCount('comments')->with('user')->orderByDesc('comments_count')->get();

        return view('index')->with('posts', $posts);
    }
}
