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
        $request->validate([
            'title' => ['required', 'max:255'],
            'body' => ['required']
        ]);

        $status = boolval($request->status);
        $post = Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'status' => $status,
            'user_id' => Auth::id()
        ]);

        return redirect('mypage/post/edit/'.$post->id);
    }

    public function edit(Post $post)
    {
        if (Auth::user()->isNot($post->user)) {
            abort(403);
        }

        $data = old() ?: $post;

        return view('mypage.posts.edit', compact('post', 'data'));
    }

    public function update(Post $post, Request $request)
    {
        if (Auth::user()->isNot($post->user)) {
            abort(403);
        }

        $post->update([
            'title' => $request->title,
            'body' => $request->body,
            'status' => boolval($request->status)
        ]);

        $update_message = 'ブログを更新しました。';
        return redirect(route('mypage.post.edit', $post->id))->with('status', $update_message);
    }

    public function destroy(Post $post)
    {
        if (Auth::user()->isNot($post->user)) {
            abort(403);
        }

        $post->delete();
        // 投稿に付随するコメントはDBの制約を用いて削除する

        return redirect('mypage/posts');
    }
}
