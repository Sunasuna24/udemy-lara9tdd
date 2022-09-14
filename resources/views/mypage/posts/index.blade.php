@extends('layouts.app')

@section('content')
<h1>マイブログ一覧</h1>
<a href="/mypage/post/create">ブログ新規登録</a>
<hr>
<table>
    <tr>
        <th>ブログ名</th>
    </tr>
    @foreach ($posts as $post)
    <tr>
        <td>
            <a href="{{ route('mypage.post.edit', $post->id) }}">{{ $post->title }}</a>
        </td>
        <td>
            <form action="{{ route('mypage.post.delete', $post->id) }}" method="post">
                @csrf
                @method('delete')
                <input type="submit" value="削除">
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection