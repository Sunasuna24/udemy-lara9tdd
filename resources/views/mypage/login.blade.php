@extends('layouts.app')

@section('content')

<h1>ログイン画面</h1>

<form action="" method="post">
    @csrf
    @include('inc.error')
    @include('inc.status')

    <label for="">メールアドレス</label>：<input type="text" name="email" value="{{ old('email') }}">
    <br>
    <label for="">パスワード</label>：<input type="password" name="password">
    <br>
    <br>
    <input type="submit" value="送信する">
</form>

<p style="margin-top: 30px;">
    <a href="/signup">新規ユーザー登録</a>
</p>

@endsection