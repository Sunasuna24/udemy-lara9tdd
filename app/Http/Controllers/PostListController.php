<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostListController extends Controller
{
    public function index()
    {
        $posts = Post::orderByDesc('comments_count')->withCount('comments')->get();

        return view('index')->with('posts', $posts);
    }
}
